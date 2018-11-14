<?php

namespace Test\ResourcesTest;

use OdooRPCClient\Client;
use PHPUnit\Framework\TestCase;

class ResourcesTest extends TestCase
{
    public function test_it_counts_admin_users(): void
    {   
        $data = [
            'host' => 'http://localhost',
            'database' => 'odoo',
            'login' => 'admin',
            'password' => 'admin'
        ];

        $client = new Client($data['host'], $data['database'], $data['login'], $data['password']);

        $count = $client['res.users']->search_count([['name','=','admin']]);
        $this->assertArrayHasKey($count, 1);
    }
}