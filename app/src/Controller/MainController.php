<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\ToDoList;
use App\Entity\User;
use App\Utils\CustomTodoList;
use App\Utils\ItemList;
use App\Utils\UserAccount;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


class MainController extends AbstractController
{


    /**
     * @Route("/api/todo-lists", name="todo_lists")
     */
    public function todolists(SerializerInterface $serializer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $todoLists = $em->getRepository(ToDoList::class)->findAll();
        $arrayOutput = array();
        foreach ($todoLists as $key => $todoList) {
            $arrayOutput["todo_lists"][$key]["id"] = $todoList->getId();
            $arrayOutput["todo_lists"][$key]["name"] = $todoList->getName();
            $items = $todoList->getItems();
            if (!is_null($items)) {
                foreach ($items as $itemKey => $item) {
                    $arrayOutput["todo_lists"][$key]["items"][$itemKey]["name"] = $item->getName();
                    $arrayOutput["todo_lists"][$key]["items"][$itemKey]["content"] = $item->getContent();
                    $arrayOutput["todo_lists"][$key]["items"][$itemKey]["creationDate"] = $item->getCreationDate();
                }
            }
        }
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $sales = array("toto" => "test", "number" => "2", "myarray", array("banana", "potato"));
        $sales = json_encode($arrayOutput);
        $jsonContent = $serializer->serialize($sales, 'json');
        return new JsonResponse($sales, Response::HTTP_OK, [], true);
    }


    /**
     * @Route("/api/delete-todo-list", name="delete_todo_list", methods={"POST"})
     */
    public function delete_todo_list(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $exists = $em->find(ToDoList::class, $request->get("id"));
        if ($exists) {
            $todoItem = $em->getReference(ToDoList::class, $request->get("id"));
            $em->remove($todoItem);
            $em->flush();
        }
        if ($exists)
            $result = array("result" => "todo list removed");
        else
            $result = array("result" => "item doesn't exist");

        return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true);
    }


    /**
     * @Route("/api/add-todo-list", name="add_todo_list", methods={"POST"})
     */
    public function add_todo_list(Request $request): Response
    {
//        $em = $this->getDoctrine()->getManager();
//        $todoItem = $em->getReference(ToDoList::class, $request->get("id"));
//        $em->remove($todoItem);
//        $em->flush();
//        $result = array("result"=> "todo list removed");
        $entityManager = $this->getDoctrine()->getManager();
//        $item1 = new ItemList($request->get("name"), $request->get("content"));
//        $todoItems = $request->get("items");
        $todoList = new CustomTodoList($request->get("name"), "2", $entityManager);
        $result = array("result" => "todo list created");
        return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true);
    }


    /**
     * @Route("/api/add-todo-item", name="add_todo_item", methods={"POST"})
     */
    public function add_todo_item(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (!$request->get("todolist_id")) {
            $result = array("result" => "todo list id not specified");
            return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true);

        }

        if (!$request->get("item_name")) {
            $result = array("result" => "todo list item name not specified");
            return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true);

        }
        if ($request->get("item_content") && strlen($request->get("item_content")) > 1000) {
            $result = array("result" => "Max characters for the content is 1000");
            return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true);
        }

        if (!$request->get("todolist_id")) {
            $result = array("result" => "todo list id not specified");
            return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true);

        }
        $todoList = $entityManager->find(ToDoList::class, $request->get("todolist_id"));
        $result = array("result" => "");
        if ($todoList) {
            // if the todo list exists, a new item is added to it
            $valid = True;
            $dt = new \DateTime();
            $creationDate = $dt->format('d/m/Y H:i:s');
            $items = $todoList->getItems();
            for ($i = 0; $i < count($items); $i++) { // checking if the item already exists
                if ($items[$i]->getName() == $request->get("item_name")) {
                    $result = array("result" => "item name already exists");
                    return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true); // stopping the program with an error message
                }

            }
            $item = new Item();
            $item->setName($request->get("item_name"));
            $item->setContent($request->get("item_content"));

            if ($items[0]) {
                $LastDate = $items[count($items) - 1]->getCreationDate();
                if ($request->get("creation_date"))
                    if (\DateTime::createFromFormat('d/m/Y H:i:s', $request->get("creation_date")))
                        $NewDate = \DateTime::createFromFormat('d/m/Y H:i:s', $request->get("creation_date"));
                    else {
                        $result = array("result" => "Date format is not valid");
                        return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true); // stopping the program with an error message

                    }
                else
                    $NewDate = \DateTime::createFromFormat('d/m/Y H:i:s', $creationDate);

                $interval = $LastDate->diff($NewDate);
                $timeDiffMin = ($interval->h * 60) + $interval->i;
            } else {
                $timeDiffMin = 1000;
            }

            if ($timeDiffMin >= 30) {
                if ($request->get("creation_date")) {
                    if (\DateTime::createFromFormat('d/m/Y H:i:s', $request->get("creation_date"))) {
                        $item->setCreationDate(\DateTime::createFromFormat('d/m/Y H:i:s', $request->get("creation_date")));
                    } else {
                        $result = array("result" => "Date format is not valid");
                        $valid = false;
                    }
                } else {
                    $item->setCreationDate(\DateTime::createFromFormat('d/m/Y H:i:s', $creationDate));
                }
                if ($valid) {
                    $entityManager->persist($item);
                    $entityManager->flush();
                    $todoList->addItem($item);
                    $entityManager->persist($todoList);
                    $entityManager->flush();
                    if (count($items) == 8) {
                        $result = array("result" => "item successfully added", "email" => "sent");

                    } else {
                        $result = array("result" => "item successfully added");

                    }
                }
            } else {
                $result = array("result" => "You have to wait 30 minutes every time you create an item");
            }

        } else {
            $result = array("result" => "todo list not found");
        }
        return new JsonResponse(json_encode($result), Response::HTTP_OK, [], true);
    }


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
