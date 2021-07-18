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
            "id"=>"25",
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

    public function testAddItemTodoList()
    {
        $data = array(
            "todolist_id"=>"1",
            "item_name"=>"newItem",
            "item_content"=>"newItemContent",
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $response = $this->client->post("/api/add-todo-item", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result" => "item successfully added"));
        $this->assertEquals($expected,$body);
    }

    public function testAddItemTodoListWithoutId()
    {
        $data = array(
            "item_name"=>"newItem",
            "item_content"=>"newItemContent",
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $response = $this->client->post("/api/add-todo-item", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result"=>"todo list id not specified"));
        $this->assertEquals($expected,$body);
    }

    public function testAddItemTodoListThatNotExist()
    {
        $data = array(
            "todolist_id"=>"10000",
            "item_name"=>"newItem",
            "item_content"=>"newItemContent",
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $response = $this->client->post("/api/add-todo-item", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result" => "todo list not found"));
        $this->assertEquals($expected,$body);
    }

    public function testAddExistingItemTodoList()
    {
        $data = array(
            "todolist_id"=>"1",
            "item_name"=>"newItem",
            "item_content"=>"newItemContent",
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $response = $this->client->post("/api/add-todo-item", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result" => "item name already exists"));
        $this->assertEquals($expected,$body);
    }

    public function testAddItemTodoListTooLong()
    {
        $data = array(
            "todolist_id"=>"1",
            "item_name"=>"newItem",
            "item_content"=>str_repeat("a",1200),
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $response = $this->client->post("/api/add-todo-item", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result" => "Max characters for the content is 1000"));
        $this->assertEquals($expected,$body);
    }

    /*public function testAddItemTodoListTooEarly()
    {
        $data = array(
            "todolist_id"=>"1",
            "item_name"=>"newItem",
            "item_content"=>"newItemContent",
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $response = $this->client->post("/api/add-todo-item", array(
            'form_params' => $data));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result" => "You have to wait 30 minutes every time you create an item"));
        $this->assertEquals($expected,$body);
    }*/
}