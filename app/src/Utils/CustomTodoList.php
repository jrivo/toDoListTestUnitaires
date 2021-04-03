<?php

namespace App\Utils;

use App\Entity\Item;
use App\Entity\ToDoList;
use App\Entity\User;

class CustomTodoList
{

    public function __construct($name, $userID, $entityManager)
    {
        $creator = $entityManager->getRepository(User::class)->findOneBy(['id' => $userID]);
        $creatorList = $entityManager->getRepository(ToDoList::class)->findOneBy(['creator' => $userID]);
        if (empty($creatorList)) {
            $this->todoList = new ToDoList();
            $this->name = $name;
            $this->todoList->setName($this->name);
            $this->items = [];
            $this->todoList->setCreator($creator);
            $entityManager->persist($this->todoList);
            $entityManager->flush();
        } else {
            //cannot detect if the constructor didnt construct
            $this->todoList = new ToDoList();
            $this->name = $name;
            $this->todoList->setName($this->name);
            $this->items = [];
            $this->todoList->setCreator($creator);
            $entityManager->persist($this->todoList);
            $entityManager->flush();
            return false;
        }
    }

    public function addItem($item, $entityManager)
    {
        $items = $this->getItems($entityManager);
        if (count($this->getItems($entityManager)) >= 10) // returns false if the list has more than 10 items
            return false;
        for ($i = 0; $i < count($items); $i++) { // checking if the item already exists
            if ($items[$i]->getName() == $item->getName())
                return false;
        }
        array_push($this->items, $item);
        $this->todoList->addItem($item->getItem($entityManager));
        $entityManager->persist($this->todoList);
        $entityManager->flush(); // saving data in the database
        return true;
    }

    public function getItems($entityManager)
    {
        $todoList = $entityManager->getRepository(ToDoList::class)->findOneBy(['name' => $this->name]);
        if (empty($todoList))
            return [];
        return $todoList->getItems();
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
     * @return ToDoList
     */
    public function getTodoList(): ToDoList
    {
        return $this->todoList;
    }

    /**
     * @param ToDoList $todoList
     */
    public function setTodoList(ToDoList $todoList): void
    {
        $this->todoList = $todoList;
    }


}