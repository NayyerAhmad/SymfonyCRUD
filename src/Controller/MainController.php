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
    #[Route("/", name:"main")]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controler_name'=> 'MainController',
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
        }
        return $this->render('main/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

}

