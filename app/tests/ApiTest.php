<?php

namespace App\Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private Client $client;


    protected function setUp(): void
    {
        $this->client = new Client(['base_uri' => 'http://nginx:80']);
    }

    public function testGetToDoList()
    {
        $response = $this->client->request("GET","/api/todo-lists");
        $this->assertEquals(200,$response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testDeleteToDoList()
    {
        $data = array(
            "id"=>"22",
        );
        $response = $this->client->post("/api/delete-todo-list", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result"=>"todo list removed"));
        $this->assertEquals($expected,$body);
    }

    public function testDeleteToDoListThatNotExist()
    {
        $data = array(
            "id"=>"10000",
        );
        $response = $this->client->post("/api/delete-todo-list", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result"=>"item doesn't exist"));
        $this->assertEquals($expected,$body);
    }

    public function testCreateToDoList()
    {
        $data = array(
            "name"=>"testApi",
        );
        $response = $this->client->post("/api/add-todo-list", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result"=>"todo list created"));
        $this->assertEquals($expected,$body);
    }
}