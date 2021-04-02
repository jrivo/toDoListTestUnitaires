<?php

namespace App\Tests;

use App\Utils\CustomTodoList;
use App\Utils\ItemList;
use phpDocumentor\Reflection\Types\True_;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;


class CustomTodoListTest extends TestCase{
    private CustomTodoList $todoList;

    protected function setUp( ): void
    {
//        $this->todoList = new CustomTodoList("my list ", $entityManager);
        parent::setUp();
    }



    //should return an empty array
    public function testGetItemsWhenArrayEmpty(){
        $this->assertEquals([],$this->todoList->getItems());
    }

    // should return true
    public function testAddOneItem(){
        $item = new ItemList("todo item", "some text");
        $this->assertTrue($this->todoList->addItem($item));
    }

    //should return false
    public function testAddTwoItemsInLesThan30min(){
        $item1 = new ItemList("item1", "some text");
        $item2 = new ItemList("item2", "some text");
        $this->todoList->addItem($item1)
        $this->assertFalse($this->todoList->addItem($item2));
    }


}
