<?php

namespace Test\ConnectionTest;

use OdooRPCClient\Client;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function test_it_connects_to_odoo_and_does_login(): void
    {   
        $data = [
            'host' => 'https://demo3.odoo.com',
            'database' => 'demo',
            'login' => 'admin',
            'password' => 'admin'
        ];

        $client = new Client($data['host'], $data['database'], $data['login'], $data['password']);
        $version = $client->version();
        $this->assertArrayHasKey('protocol_version',$version);
    }
}