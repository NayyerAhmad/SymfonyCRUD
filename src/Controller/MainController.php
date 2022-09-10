<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route("/main", name:"main")]
    public function index(ManagerRegistry $doctrine): Response
    {
        $data = $doctrine->getRepository(Crud::class)->findAll();
        return $this->render('main/index.html.twig', [
            'list' => $data
        ]);
    }
    
    #[Route("/create", name:"create")]
    public function create(Request $request, ManagerRegistry $doctrine){
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
                $entitymanager = $doctrine->getManager();
                $entitymanager->persist($crud);
                $entitymanager->flush();

                $this->addFlash('attention','Submission Done!');

                return $this->redirectToRoute('main');
        }
        return $this->render('main/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route("/update/{id}", name:"update")]

    public function update(Request $request, $id, ManagerRegistry $doctrine) {

        $crud = $doctrine->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
                $entitymanager = $doctrine->getManager();
                $entitymanager->persist($crud);
                $entitymanager->flush();

                $this->addFlash('attention','Update Done!');

                return $this->redirectToRoute('main');
        }
        return $this->render('main/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route("/delete/{id}", name:"delete")]

    public function delete(Request $request, $id, ManagerRegistry $doctrine) {
        $data = $doctrine->getRepository(Crud::class)->find($id);
        $entitymanager = $doctrine->getManager();
        $entitymanager->remove($data);
        $entitymanager->flush();

        $this->addFlash('attention','Deletion Done!');

        return $this->redirectToRoute('main');
    }
}

