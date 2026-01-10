<?php
// tests/ApiTest.php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ApiTest extends TestCase
{
    private Client $client;
    private string $password = 'test123';

    protected function setUp(): void
    {
        $baseUri = getenv('API_BASE_URI') ?: 'http://localhost/stacks/backend-api/public/';
        $this->client = new Client([
            'base_uri'    => $baseUri,
            'http_errors' => false,
        ]);
    }

    public function testRegisterSuccess(): void
    {
        // generate a fresh username each run
        $username = 'phpunit_' . uniqid();

        $res = $this->client->get('register_api_get.php', [
            'query' => [
                'username'         => $username,
                'password'         => $this->password,
                'confirm_password' => $this->password,
            ],
        ]);

        // your API returns 200 for success
        $this->assertEquals(200, $res->getStatusCode());

        $data = json_decode((string)$res->getBody(), true);
        $this->assertTrue($data['success'], "Expected registration to succeed for $username");
    }

    public function testLoginSuccess(): void
    {
        // register a new user right here
        $username = 'phpunit_' . uniqid();
        $reg = $this->client->get('register_api_get.php', [
            'query' => [
                'username'         => $username,
                'password'         => $this->password,
                'confirm_password' => $this->password,
            ],
        ]);
        $this->assertTrue(json_decode((string)$reg->getBody(), true)['success']);

        // now login with that same user
        $res = $this->client->get('login_api_get.php', [
            'query' => [
                'username' => $username,
                'password' => $this->password,
            ],
        ]);
        $this->assertEquals(200, $res->getStatusCode());

        $data = json_decode((string)$res->getBody(), true);
        $this->assertTrue($data['success'], "Expected login to succeed for $username");
    }

    public function testLoginFailure(): void
    {
        $res = $this->client->get('login_api_get.php', [
            'query' => ['username' => 'no_such_user', 'password' => 'wrongpass'],
        ]);
        $this->assertEquals(200, $res->getStatusCode());

        $data = json_decode((string)$res->getBody(), true);
        $this->assertFalse($data['success'], "Expected login to fail for invalid credentials");
    }

    public function testGetPurchasesEmpty(): void
    {
        // register a fresh user with no purchases
        $username = 'phpunit_' . uniqid();
        $reg = $this->client->get('register_api_get.php', [
            'query' => [
                'username'         => $username,
                'password'         => $this->password,
                'confirm_password' => $this->password,
            ],
        ]);
        $this->assertTrue(json_decode((string)$reg->getBody(), true)['success']);

        // fetching purchases returns an empty array
        $res = $this->client->get('getPurchase_api.php', [
            'query' => ['username' => $username],
        ]);
        $this->assertEquals(200, $res->getStatusCode());

        $data = json_decode((string)$res->getBody(), true);
        $this->assertIsArray($data['purchases'], "Expected 'purchases' to be an array");
        $this->assertCount(0, $data['purchases'], "New user should have zero purchases");
    }
}
