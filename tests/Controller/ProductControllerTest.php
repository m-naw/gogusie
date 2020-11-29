<?php

namespace Gog\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testListProducts()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(3, $data);
        $this->assertEquals(1.99, $data[0]['priceAmountScaled']);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('Fallout', $data[0]['title']);
        $this->assertEquals(199, $data[0]['priceAmount']);
        $this->assertEquals('USD', $data[0]['priceCurrency']);

        $client->request('GET', '/api/products?page=2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(2, $data);
    }

    public function testCreateProduct()
    {
        $client = static::createClient();

        $input = [
            'title' => 'Cyberpunk 2077',
            'priceAmount' => 19900
        ];

        $client->request('POST', '/api/products', [], [], [], json_encode($input));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(199.0, $data['priceAmountScaled']);
        $this->assertEquals('Cyberpunk 2077', $data['title']);
        $this->assertEquals(19900, $data['priceAmount']);
        $this->assertEquals('USD', $data['priceCurrency']);

        $input = [
            'title' => 'Cyberpunk 2077',
            'priceAmount' => 19900
        ];

        $client->request('POST', '/api/products', [], [], [], json_encode($input));

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals("Validation Failed", $data["form"]['message']);

        $input = [
            'title' => 'Final Fantasy 8',
            'priceAmount' => -1
        ];

        $client->request('POST', '/api/products', [], [], [], json_encode($input));

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals("Validation Failed", $data["form"]['message']);

        $input = [
            'title' => 'Final Fantasy 8',
        ];

        $client->request('POST', '/api/products', [], [], [], json_encode($input));

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals("Validation Failed", $data["form"]['message']);

        $input = [
            'price' => 10000,
        ];

        $client->request('POST', '/api/products', [], [], [], json_encode($input));

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals("Validation Failed", $data["form"]['message']);
    }

    public function testUpdateProduct()
    {
        $client = static::createClient();

        $input = [
            'title' => 'Cyberpunk',
            'priceAmount' => 18800
        ];

        $client->request('PUT', '/api/products/6', [], [], [], json_encode($input));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(188.0, $data['priceAmountScaled']);
        $this->assertEquals('Cyberpunk', $data['title']);
        $this->assertEquals(18800, $data['priceAmount']);
        $this->assertEquals('USD', $data['priceCurrency']);

        $input = [
            'priceAmount' => 19900
        ];

        $client->request('PUT', '/api/products/6', [], [], [], json_encode($input));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(199.0, $data['priceAmountScaled']);
        $this->assertEquals('Cyberpunk', $data['title']);
        $this->assertEquals(19900, $data['priceAmount']);
        $this->assertEquals('USD', $data['priceCurrency']);

        $input = [
            'title' => 'Cyberpunk 2077',
        ];

        $client->request('PUT', '/api/products/6', [], [], [], json_encode($input));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(199.0, $data['priceAmountScaled']);
        $this->assertEquals('Cyberpunk 2077', $data['title']);
        $this->assertEquals(19900, $data['priceAmount']);
        $this->assertEquals('USD', $data['priceCurrency']);

        $input = [
            'title' => 'Fallout',
        ];

        $client->request('PUT', '/api/products/6', [], [], [], json_encode($input));

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals("Validation Failed", $data["form"]['message']);

        $input = [
            'priceAmount' => -1
        ];

        $client->request('PUT', '/api/products/6', [], [], [], json_encode($input));

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals("Validation Failed", $data["form"]['message']);

        $client->request('PUT', '/api/products/7', [], [], [], json_encode($input));

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testRemoveProduct()
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/products/6');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $client->request('DELETE', '/api/products/1');

        $this->assertEquals(422, $client->getResponse()->getStatusCode());

        $client->request('DELETE', '/api/products/7');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}