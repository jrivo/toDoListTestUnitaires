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
        $lists = json_decode($this->client->request("GET","/api/todo-lists")->getBody(),true)["todo_lists"];
        $listToDelete = $lists[count($lists)-1];
        $data = array(
            "id"=>$listToDelete["id"],
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
        $lists = json_decode($this->client->request("GET","/api/todo-lists")->getBody(),true)["todo_lists"];
        $targetList = $lists[rand(0,count($lists)-1)];
        $data = array(
            "todolist_id"=>$targetList["id"],
            "item_name"=>"newItem".rand(0,10000),
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
        $randSuffix = rand(0,100);
        $item1 = array(
            "todolist_id"=>"1",
            "item_name"=>"newItem".$randSuffix,
            "item_content"=>"newItemContent",
            "creation_date"=>"20/06/2021 20:50:00",
        );
        $item2 = array(
            "todolist_id"=>"1",
            "item_name"=>"newItem".$randSuffix,
            "item_content"=>"newItemContent",
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $this->client->post("/api/add-todo-item", array(
            'form_params' => $item1));
        sleep(1);
        $response = $this->client->post("/api/add-todo-item", array(
            'form_params' => $item2));
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

    public function testAddItemTodoListTooEarly()
    {
        $item1 = array(
            "todolist_id"=>"1",
            "item_name"=>"newItem",
            "item_content"=>"newItemContent",
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $item2 = array(
            "todolist_id"=>"1",
            "item_name"=>"newItem2",
            "item_content"=>"newItemContent",
            "creation_date"=>(new \DateTime)->format('d/m/Y H:i:s'),
        );
        $this->client->post("/api/add-todo-item", array(
            'form_params' => $item1));
        $response = $this->client->post("/api/add-todo-item", array(
            'form_params' => $item2));
        $this->assertEquals(200,$response->getStatusCode());
        $body = $response->getBody();
        $expected = json_encode(array("result" => "You have to wait 30 minutes every time you create an item"));
        $this->assertEquals($expected,$body);
    }
}