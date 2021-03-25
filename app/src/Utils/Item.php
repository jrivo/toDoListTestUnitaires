<?php

class Item
{

    public function __construct($name, $content, $creationDate)
    {
        $this->name = $name;
        $this->content = $content;
        $this->creationDate = $creationDate;
    }

    public function isValid()
    {
        if (isset($this->name) && strlen($this->content) < 1000) {
            echo "item valid";
        } else {
            echo "item invalid";
        }
    }

    public function save()
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($this);
        $em->flush();
    }
}