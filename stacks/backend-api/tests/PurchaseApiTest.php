<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class PurchaseApiTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $baseUri = getenv('API_BASE_URI') ?: 'http://localhost/stacks/backend-api/public/';
        $this->client = new Client([
            'base_uri'    => $baseUri,
            'http_errors' => false,
        ]);
    }

    /**
     * Test makePurchase_api.php without providing the username parameter.
     *
     * @return void
     */
    public function testMakePurchaseMissingUsername(): void
    {
        // Send request with only the animal parameter
        $response = $this->client->get('makePurchase_api.php', [
            'query' => ['animal' => 'Cat'],
        ]);

        // Assert HTTP status is 200
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            'Expected HTTP status code 200 when username is missing.'
        );

        // Decode JSON response
        $data = json_decode((string)$response->getBody(), true);

        // Assert success field exists and is false
        $this->assertArrayHasKey('success', $data, 'Response should contain a success field.');
        $this->assertFalse(
            $data['success'],
            'Expected success to be false when username is not provided.'
        );

        // Assert message field exists and mentions the missing username
        $this->assertArrayHasKey('message', $data, 'Response should contain a message field.');
        $this->assertStringContainsString(
            'username',
            strtolower($data['message']),
            'Error message should mention that the username is required.'
        );
    }
}
