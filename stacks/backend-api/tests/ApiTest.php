<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/** Register and login flow. */
final class ApiTest extends TestCase
{
    private Client $client;
    private string $password = 'test12345';

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri'    => getenv('API_BASE_URI') ?: 'http://localhost:8000/',
            'http_errors' => false,
        ]);
    }

    private function newUsername(): string
    {
        return 'pu_' . substr(uniqid(), -10);
    }

    private function register(string $username, ?string $confirm = null): array
    {
        $res = $this->client->post('register_api_get.php', ['json' => [
            'username'         => $username,
            'password'         => $this->password,
            'confirm_password' => $confirm ?? $this->password,
        ]]);
        return [$res->getStatusCode(), json_decode((string)$res->getBody(), true)];
    }

    public function testRegisterSuccess(): void
    {
        [$status, $data] = $this->register($this->newUsername());
        $this->assertSame(201, $status);
        $this->assertTrue($data['success']);
    }

    public function testRegisterDoesNotLeakOtherUsers(): void
    {
        [, $data] = $this->register($this->newUsername());
        $this->assertArrayNotHasKey('debug', $data);
    }

    public function testRegisterDuplicate(): void
    {
        $username = $this->newUsername();
        $this->register($username);
        [$status, $data] = $this->register($username);
        $this->assertSame(409, $status);
        $this->assertFalse($data['success']);
    }

    public function testRegisterPasswordMismatch(): void
    {
        [$status, $data] = $this->register($this->newUsername(), 'different123');
        $this->assertSame(400, $status);
        $this->assertFalse($data['success']);
    }

    public function testCredentialsInUrlAreRejected(): void
    {
        $res = $this->client->get('login_api_get.php', ['query' => ['username' => 'a', 'password' => 'b']]);
        $this->assertSame(405, $res->getStatusCode());
    }

    public function testLoginReturnsToken(): void
    {
        $username = $this->newUsername();
        $this->register($username);

        $res  = $this->client->post('login_api_get.php', ['json' => ['username' => $username, 'password' => $this->password]]);
        $data = json_decode((string)$res->getBody(), true);

        $this->assertSame(200, $res->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertCount(3, explode('.', $data['token']));
    }

    public function testLoginWrongPassword(): void
    {
        $username = $this->newUsername();
        $this->register($username);

        $res  = $this->client->post('login_api_get.php', ['json' => ['username' => $username, 'password' => 'wrongpass1']]);
        $data = json_decode((string)$res->getBody(), true);

        $this->assertSame(401, $res->getStatusCode());
        $this->assertFalse($data['success']);
    }
}
