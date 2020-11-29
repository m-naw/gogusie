<?php

namespace Gog\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testCreateCart()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(1, $data['id']);
    }

    public function testAddProductToCart()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts/1/product/5');

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(1, count($data['products']));
        $this->assertEquals(5, $data['products'][0]['productId']);
        $this->assertEquals(1, $data['products'][0]['quantity']);
        $this->assertEquals(5.99, $data['totalPrice']);

        $client->request('POST', '/api/carts/1/product/5');

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(1, count($data['products']));
        $this->assertEquals(2, $data['products'][0]['quantity']);
        $this->assertEquals(11.98, $data['totalPrice']);

        $client->request('POST', '/api/carts/1/product/4');

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(2, count($data['products']));
        $this->assertEquals(4, $data['products'][1]['productId']);
        $this->assertEquals(1, $data['products'][1]['quantity']);
        $this->assertEquals(16.97, $data['totalPrice']);

        $client->request('POST', '/api/carts/1/product/3');

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(3, count($data['products']));
        $this->assertEquals(20.96, $data['totalPrice']);
    }

    public function testAddProductToCartWithError()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts/1/product/2');

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals("Cart can contain maximum 3 products", $data[0]['message']);

        for ($i = 0; $i < 10; $i++ ) {
            $client->request('POST', '/api/carts/1/product/3');
        }

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals("Cart can contain maximum 10 units of the same product", $data[0]['message']);

        $client->request('POST', '/api/carts/1/product/10');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetCart()
    {
        $client = static::createClient();

        $client->request('GET', '/api/carts/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(3, count($data['products']));
        $this->assertEquals(5, $data['products'][2]['productId']);
        $this->assertEquals(2, $data['products'][2]['quantity']);
        $this->assertEquals(4, $data['products'][1]['productId']);
        $this->assertEquals(1, $data['products'][1]['quantity']);
        $this->assertEquals(3, $data['products'][0]['productId']);
        $this->assertEquals(10, $data['products'][0]['quantity']);
        $this->assertEquals(56.87, $data['totalPrice']);
    }

    public function testGetCartWithError()
    {
        $client = static::createClient();

        $client->request('GET', '/api/carts/10');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testRemoveProductFromCart()
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/carts/1/product/4');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('DELETE', '/api/carts/1/product/3');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('GET', '/api/carts/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(2, count($data['products']));
        $this->assertEquals(5, $data['products'][1]['productId']);
        $this->assertEquals(2, $data['products'][1]['quantity']);
        $this->assertEquals(3, $data['products'][0]['productId']);
        $this->assertEquals(9, $data['products'][0]['quantity']);
        $this->assertEquals(47.89, $data['totalPrice']);
    }

    public function testRemoveProductFromCartWithError()
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/carts/1/product/10');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}