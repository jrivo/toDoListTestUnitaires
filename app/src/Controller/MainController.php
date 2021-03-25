<?php

namespace App\Controller;

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
        $user = new UserAccount("amine", "ziani","email@email.com", "23/07/1995");
        $resultat = $user->isValid();
        echo $resultat;
        return $this->render('main/index.html.twig');
    }
}
