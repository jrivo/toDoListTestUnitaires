<?php

namespace App\Utils;


use App\Entity\Item;

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

    /*public function addItem($list, $listManager)
    {
        if ($this->isValid()) {
            $targetList = $listManager->getRepository(ToDoList::class)->findOneBy(['name' => $list]);
            $em = $this->getDoctrine()->getManager();
            $item = new Item();
            $item->setName($this->name);
            $item->setContent($this->content);
            $item->setCreationDate(\DateTimeInterface::ATOM);
            $item->setList($targetList);
            $em->persist($item);
            $em->flush();
        }
    }*/

    public function save($entityManager)
    {
        if ($this->isValid()) {
            $item = new Item();
            $tz  = new \DateTimeZone('Europe/Paris');
            $item->setName($this->name);
            $item->setContent($this->content);
            $item->setCreationDate(\DateTime::createFromFormat('d/m/Y', $this->creationDate, $tz));
            $entityManager->persist($item);
            $entityManager->flush();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate): void
    {
        $this->creationDate = $creationDate;
    }
}

