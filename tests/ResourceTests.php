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
            'database' => 'demo',
            'login' => 'admin',
            'password' => 'admin'
        ];

        $odoo = new Client($data['host'], $data['database'], $data['login'], $data['password']);

        $count = $odoo['res.users']->search_count([['login','=', $data['login'] ]]);
        $this->assertEquals($count, 1);
    }

    public function test_it_fetchs_all_users(): void
    {
        $data = [
            'host' => 'http://localhost',
            'database' => 'demo',
            'login' => 'admin',
            'password' => 'admin'
        ];

        $odoo = new Client($data['host'], $data['database'], $data['login'], $data['password']);
        $users = $odoo['res.users']->search([['active','=',True]]);
        $this->assertGreaterThan(0, count($users));
    }

    public function test_it_create_edit_delete_partner(): void
    {
        $data = [
            'host' => 'http://localhost',
            'database' => 'demo',
            'login' => 'admin',
            'password' => 'admin'
        ];

        $odoo = new Client($data['host'], $data['database'], $data['login'], $data['password']);
        $vals = [
            'firstname' => 'Pippo',
            'lastname' => 'Pluto',
            'email' => 'mario.rossi.123456@rossi.it'
        ];

        $partner_id = $odoo['res.partner']->create($vals);

        $this->assertTrue(is_integer($partner_id));

        $partner = $odoo['res.partner']->browse($partner_id);

        $this->assertTrue(is_string($partner->name));

        $count = $odoo['res.partner']->search_count([
            ['firstname', '=', $vals['firstname']],
            ['lastname', '=', $vals['lastname']],
            ['email', '=', $vals['email']]
        ]);

        $this->assertEquals(1, $count);

        $partner->unlink();

        $count = $odoo['res.partner']->search_count([
            ['firstname', '=', $vals['firstname']],
            ['lastname', '=', $vals['lastname']],
            ['email', '=', $vals['email']]
        ]);

        $this->assertEquals(0, $count);
    }
}