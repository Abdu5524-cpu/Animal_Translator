<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/** Purchase endpoints: auth required, and users can only touch their own purchases. */
final class PurchaseApiTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri'    => getenv('API_BASE_URI') ?: 'http://localhost:8000/',
            'http_errors' => false,
        ]);
    }

    private function userToken(): string
    {
        $username = 'pu_' . substr(uniqid(), -10);
        $password = 'test12345';
        $this->client->post('register_api_get.php', ['json' => [
            'username' => $username, 'password' => $password, 'confirm_password' => $password,
        ]]);
        $res = $this->client->post('login_api_get.php', ['json' => ['username' => $username, 'password' => $password]]);
        return json_decode((string)$res->getBody(), true)['token'];
    }

    private function call(string $method, string $path, ?string $token, array $json = []): array
    {
        $options = $token ? ['headers' => ['Authorization' => "Bearer $token"]] : [];
        if ($json) {
            $options['json'] = $json;
        }
        $res = $this->client->request($method, $path, $options);
        return [$res->getStatusCode(), json_decode((string)$res->getBody(), true)];
    }

    public function testPurchaseRequiresLogin(): void
    {
        [$status, $data] = $this->call('POST', 'makePurchase_api.php', null, ['animal' => 'Cat']);
        $this->assertSame(401, $status);
        $this->assertFalse($data['success']);
    }

    public function testForgedTokenIsRejected(): void
    {
        [$status] = $this->call('GET', 'getPurchase_api.php', 'eyJhbGciOiJub25lIn0.eyJzdWIiOiJ4IiwiZXhwIjo5OTk5OTk5OTk5fQ.');
        $this->assertSame(401, $status);
    }

    public function testFullPurchaseLifecycle(): void
    {
        $token = $this->userToken();

        [$status, $made] = $this->call('POST', 'makePurchase_api.php', $token, ['animal' => 'Cat']);
        $this->assertSame(201, $status);
        $id = $made['purchase_id'];
        $this->assertNotFalse(strtotime($made['time_date']));

        [, $list] = $this->call('GET', 'getPurchase_api.php', $token);
        $this->assertSame([$id], array_column($list['purchases'], 'purchase_id'));
        $this->assertStringContainsString('T', $list['purchases'][0]['time_date']);

        [$status] = $this->call('POST', 'updatePurchase_api.php', $token, ['purchase_id' => $id, 'animal' => 'Dog']);
        $this->assertSame(200, $status);

        // Saving the same value again is still a success.
        [$status] = $this->call('POST', 'updatePurchase_api.php', $token, ['purchase_id' => $id, 'animal' => 'Dog']);
        $this->assertSame(200, $status);

        [$status] = $this->call('POST', 'refundPurchase_api.php', $token, ['purchase_id' => $id]);
        $this->assertSame(200, $status);

        [, $list] = $this->call('GET', 'getPurchase_api.php', $token);
        $this->assertSame([], $list['purchases']);
    }

    public function testCannotTouchAnotherUsersPurchase(): void
    {
        $owner    = $this->userToken();
        $attacker = $this->userToken();

        [, $made] = $this->call('POST', 'makePurchase_api.php', $owner, ['animal' => 'Horse']);
        $id = $made['purchase_id'];

        [$status] = $this->call('POST', 'updatePurchase_api.php', $attacker, ['purchase_id' => $id, 'animal' => 'Pig']);
        $this->assertSame(404, $status);

        [$status] = $this->call('POST', 'refundPurchase_api.php', $attacker, ['purchase_id' => $id]);
        $this->assertSame(404, $status);

        [, $list] = $this->call('GET', 'getPurchase_api.php', $attacker);
        $this->assertSame([], $list['purchases']);

        [, $list] = $this->call('GET', 'getPurchase_api.php', $owner);
        $this->assertSame('Horse', $list['purchases'][0]['animal']);
    }

    public function testAnimalNameTooLong(): void
    {
        [$status] = $this->call('POST', 'makePurchase_api.php', $this->userToken(), ['animal' => str_repeat('x', 21)]);
        $this->assertSame(400, $status);
    }

    public function testPreflightIsAnswered(): void
    {
        $res = $this->client->request('OPTIONS', 'getPurchase_api.php');
        $this->assertSame(204, $res->getStatusCode());
        $this->assertStringContainsString('Authorization', $res->getHeaderLine('Access-Control-Allow-Headers'));
    }

    public function testDemoLoginResetsSampleData(): void
    {
        $res   = $this->client->post('demo_login_api.php');
        $token = json_decode((string)$res->getBody(), true)['token'];
        [, $list] = $this->call('GET', 'getPurchase_api.php', $token);
        $this->assertCount(4, $list['purchases']);

        // A visitor refunds everything...
        foreach ($list['purchases'] as $p) {
            $this->call('POST', 'refundPurchase_api.php', $token, ['purchase_id' => $p['purchase_id']]);
        }
        // ...and the next visitor still gets the full sample list.
        $res   = $this->client->post('demo_login_api.php');
        $token = json_decode((string)$res->getBody(), true)['token'];
        [, $list] = $this->call('GET', 'getPurchase_api.php', $token);
        $this->assertSame(['Bird', 'Horse', 'Cat', 'Dog'], array_column($list['purchases'], 'animal'));
    }

    public function testDemoUsernameCannotBeRegistered(): void
    {
        $res = $this->client->post('register_api_get.php', ['json' => [
            'username' => 'demo', 'password' => 'test12345', 'confirm_password' => 'test12345',
        ]]);
        $this->assertSame(409, $res->getStatusCode());
    }

    public function testHealthCheck(): void
    {
        $res = $this->client->get('health.php');
        $this->assertSame(200, $res->getStatusCode());
        $this->assertTrue(json_decode((string)$res->getBody(), true)['ok']);
    }
}
