<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Utils\ItemList;

class ItemListTest extends TestCase
{
    private ItemList $item;

    protected function setUp(): void
    {
        $this->item = new ItemList('new', 'this is a test', "01/01/2021");
        parent::setUp();
    }

    public function testItemNominal()
    {
        $this->assertTrue($this->item->isValid());
    }

    public function testItemNameEmpty()
    {
        $this->item->setName("");
        $this->assertFalse($this->item->isValid());
    }

    public function testItemContentTooLong()
    {
        $longName = str_repeat("a",1200);
        $this->item->setContent($longName);
        $this->assertFalse($this->item->isValid());
    }

//    public function testItemSave()
//    {
//        $this->assertTrue($this->item->save());
//    }
}
