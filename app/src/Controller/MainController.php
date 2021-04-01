<?php

namespace App\Controller;

use App\Entity\User;
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
        $user = new UserAccount("amine", "ziani","test@email.fr","mypassword123", "23/07/1995");
        $item = new ItemList("testItem","testContent",date("d/m/Y"));
        $output = $user->isValid();
        $entityManager = $this->getDoctrine()->getManager();
        $user->save($entityManager);
        $item->save($entityManager);
        $usersList = $this->getDoctrine()->getRepository(User::class)->findAll();
        foreach ($usersList as $item){
            echo $item->getEmail()."<br>";
        }
        if($output)
            echo "the user is valid";
        else
            echo "the user isn't valid";
        return $this->render('main/index.html.twig');
    }
}
