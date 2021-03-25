<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoListType;
use App\Repository\ToDoListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/to/do/list")
 */
class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list_index", methods={"GET"})
     */
    public function index(ToDoListRepository $toDoListRepository): Response
    {
        return $this->render('to_do_list/index.html.twig', [
            'to_do_lists' => $toDoListRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="to_do_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $toDoList = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $toDoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($toDoList);
            $entityManager->flush();

            return $this->redirectToRoute('to_do_list_index');
        }

        return $this->render('to_do_list/new.html.twig', [
            'to_do_list' => $toDoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="to_do_list_show", methods={"GET"})
     */
    public function show(ToDoList $toDoList): Response
    {
        return $this->render('to_do_list/show.html.twig', [
            'to_do_list' => $toDoList,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="to_do_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ToDoList $toDoList): Response
    {
        $form = $this->createForm(ToDoListType::class, $toDoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('to_do_list_index');
        }

        return $this->render('to_do_list/edit.html.twig', [
            'to_do_list' => $toDoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="to_do_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ToDoList $toDoList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$toDoList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($toDoList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('to_do_list_index');
    }
}
