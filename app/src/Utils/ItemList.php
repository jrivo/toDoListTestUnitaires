<?php

use \App\Entity\Item;

class ItemList
{
    private $name;
    private $content;
    private $creationDate;

    public function __construct($name, $content, $creationDate)
    {
        $this->name = $name;
        $this->content = $content;
        $this->creationDate = $creationDate;
    }

    public function isValid()
    {
        if (!empty($this->name) && strlen($this->content) < 1000) {
            echo "item valid";
            return true;
        } else {
            echo "item invalid";
            return false;
        }
    }

    public function save()
    {
        if ($this->isValid()) {
            $item = new Item();
            $item->setName($this->name);
            $item->setContent($this->content);
            $item->setCreationDate($this->creationDate);
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
        }
    }
}