<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\User;
use App\Utils\CustomTodoList;
use App\Utils\ItemList;
use App\Utils\UserAccount;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $item1 = new ItemList("item1", "this is the content16");
        $item2 = new ItemList("item2", "this is the content16");
        $item3 = new ItemList("item3", "this is the content16");
        $user = new UserAccount("jonathan", "rivo", "test2@email.fr", "mypassword123", "04/12/1998");
        $user->save($entityManager);
        $todoList = new CustomTodoList("todo list", "2", $entityManager);
        $item1->save($entityManager);
        $item2->save($entityManager);
        $item3->save($entityManager);

        $todoList->addItem($item1, $entityManager);
        $todoList->addItem($item2, $entityManager);
        $todoList->addItem($item3, $entityManager);
//        $itemList = $this->getDoctrine()->getRepository(Item::class)->findAll();
//        $usersList = $this->getDoctrine()->getRepository(User::class)->findAll();
//        foreach ($usersList as $item){
//            echo $item->getEmail()."<br>";
//        }
//        if($output)
//            echo "the user is valid";
//        else
//            echo "the user isn't valid";

        echo "<br>" . $todoList->getName() . "<br>";
        echo "<br>--------------<br>";
        $allItems = $todoList->getItems($entityManager);
        for ($i = 0; $i < count($allItems); $i++) {
            echo "<br>" . $allItems[$i]->getName() . "<br>";
        }

        return $this->render('main/index.html.twig');
    }
}
