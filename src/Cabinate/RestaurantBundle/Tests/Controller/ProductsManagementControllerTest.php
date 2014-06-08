<?php

namespace Cabinate\RestaurantBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsManagementControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/productsmanagement/index');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/productsmanagement/list');
    }

    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'productsmanagement/add');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/productsmanagement/delete');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/productsmanagement/update');
    }

}
