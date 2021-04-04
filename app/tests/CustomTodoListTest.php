<?php

namespace App\Tests;

use App\Utils\CustomTodoList;
use App\Utils\ItemList;
use App\Utils\EmailSenderService;
use phpDocumentor\Reflection\Types\True_;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;


class CustomTodoListTest extends TestCase
{
    private CustomTodoList $todoList;
    private EmailSenderService $ess;

    protected function setUp(): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->todoList = new CustomTodoList("my list ", "1", $entityManager);
        $this->ess = $this->getMockBuilder(EmailSenderService::class)
            ->onlyMethods(['sendMail'])
            ->getMock();

        parent::setUp();
    }

    public function testCreateListIfUserHasNoList()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->assertTrue(new CustomTodoList("Test User No List", "2", $entityManager));
    }

    public function testCreateListIfUserHasList()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->assertFalse(new CustomTodoList("Test User Has List", "1", $entityManager));
    }

    //should return an empty array
    public function testGetItemsWhenArrayEmpty()
    {
        $this->assertEquals([], $this->todoList->getItems());
    }

    // should return true
    public function testAddOneItem()
    {
        $item = new ItemList("todo item", "some text");
        $this->assertTrue($this->todoList->addItem($item));
    }

    //should return false
    public function testAddTwoItemsInLesThan30min()
    {
        $item1 = new ItemList("item1", "some text");
        $item2 = new ItemList("item2", "some text");
        $this->todoList->addItem($item1);
        $this->assertFalse($this->todoList->addItem($item2));
    }

    public function testAddEighthItem()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $item = new ItemList("item1", "some text");
        $this->todoList->addItem($item, $entityManager);
        $allItems = $this->todoList->getItems($entityManager);
        if (count($allItems) == 8) {
            $this->ess->expects($this->once())
                ->method('sendMail')
                ->willReturn("Email sent");
        }
    }


}
