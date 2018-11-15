<?php

namespace Test\ResourcesTest;

use OdooRPCClient\Client;
use PHPUnit\Framework\TestCase;

class ResourcesTest extends TestCase
{
    public function getClient() 
    {
        $data = [
            'host' => 'http://localhost',
            'database' => 'demo_enterprise',
            'login' => 'admin',
            'password' => 'admin'
        ];

        $odoo = new Client($data['host']);
        $odoo->login($data['database'], $data['login'], $data['password']);
        return $odoo;
    }

    public function test_it_counts_admin_users(): void
    {   
        $odoo = $this->getClient();

        $count = $odoo->env['res.users']->search_count([['login','=', 'admin' ]]);
        $this->assertEquals($count, 1);
    }

    public function test_it_fetchs_all_users(): void
    {
        $odoo = $this->getClient();

        $users = $odoo->env['res.users']->search([['active','=',True]]);
        $this->assertGreaterThan(0, count($users));
    }

    public function test_it_create_delete_partner(): void
    {
        $odoo = $this->getClient();

        $vals = [
            'firstname' => 'Pippo',
            'lastname' => 'Pluto',
            'email' => 'mario.rossi.123456@rossi.it'
        ];

        $partner_id = $odoo->env['res.partner']->create($vals);

        $this->assertTrue(is_integer($partner_id));

        $partner = $odoo->env['res.partner']->browse($partner_id);

        $this->assertTrue(is_string($partner->name));

        $count = $odoo->env['res.partner']->search_count([
            ['firstname', '=', $vals['firstname']],
            ['lastname', '=', $vals['lastname']],
            ['email', '=', $vals['email']]
        ]);

        $this->assertEquals(1, $count);

        $partner->unlink();

        $count = $odoo->env['res.partner']->search_count([
            ['firstname', '=', $vals['firstname']],
            ['lastname', '=', $vals['lastname']],
            ['email', '=', $vals['email']]
        ]);

        $this->assertEquals(0, $count);

        $partners = $odoo->env['res.partner']->search([
            ['firstname', '=', $vals['firstname']],
            ['lastname', '=', $vals['lastname']],
            ['email', '=', $vals['email']]
        ]);

        $this->assertEquals(0, count($partners));
    }

    public function test_it_browse_not_existent_resource(): void
    {
        $odoo = $this->getClient();

        $partner = $odoo->env['res.partner']->browse(99999999999999999);

        $this->assertEquals(0, count($partner));
    }

    public function test_it_calls_custom_method_on_res_country(): void
    {
        $odoo = $this->getClient();
        $country_ids = $odoo->env['res.country']->search();
        $country = $odoo->env['res.country']->browse($country_ids[0]);
        $fields = $country->get_address_fields();
        $this->assertContains('street', $fields);
    }
}