<?php

namespace App\Utils;


use App\Entity\Item;


class ItemList
{
    private $name;
    private $content;
    private $creationDate;

    public function __construct($name, $content)
    {
        $this->name = $name;
        $this->content = $content;
        $dt = new \DateTime();
        $this->creationDate = $dt->format('d/m/Y H:i:s'); // the creation date is generated automatically when the item is created
    }

    public function isValid()
    {
        if (!empty($this->name) && strlen($this->content) < 1000) {
            return true;
        } else {
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
            if($entityManager->getRepository(Item::class)->findOneBy(['name' => $this->name]))
                return false;
            $item->setName($this->name);
            $item->setContent($this->content);
            $item->setCreationDate(\DateTime::createFromFormat('d/m/Y H:i:s',$this->creationDate));
            $entityManager->persist($item);
            $entityManager->flush();
            return true;
        } else {
            return false;
        }
    }

    public function getItem($entityManager){
        return $entityManager->getRepository(Item::class)->findOneBy(['name' => $this->name]);
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

