<?php

namespace Test\ConnectionTest;

use OdooRPCClient\Client;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function test_it_connects_to_odoo_and_does_login(): void
    {   
        $data = [
            'host' => 'http://localhost',
            'database' => 'odoo',
            'login' => 'admin',
            'password' => 'admin'
        ];

        $odoo = new Client($data['host']);

        $odoo->login($data['database'], $data['login'], $data['password']);
        $version = $odoo->version();
        $this->assertArrayHasKey('protocol_version',$version);
        $this->assertTrue(is_string($odoo->env->user->name));
    }
}