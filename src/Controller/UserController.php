<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Entity\Information;
use App\Form\InformationType;
use App\Form\UserMetierFormType;
use App\Repository\UserRepository;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     * @Route("/user/{id}", name="user")
     */
    public function index(InformationRepository $informationRepo, UserRepository $userRepo, $id  = false)
    {
        if ($id === false) {
            // acces autorisé pour un user avec le ROLE_USER
            $this->denyAccessUnlessGranted('ROLE_USER');
            
            // Je récupère les informations de l'utilisateur connecté
            $user = $this->getUser();
        }
        else {
            $user = $userRepo->find($id);
        }

        $information = $informationRepo->findOneBy(['user' => $user->getId()]);

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user, 
            'information' => $information 
        ]);
    }

    /**
     * @Route("/user/edit/infos", name="user_edit_info")
     */
    public function edit_info(Request $request, InformationRepository $informationRepo)
    {
        $user = $this->getUser();

        $userId = $user->getId();

        // je récupere les informations du user
        $information = $informationRepo->findOneBy([ 'user' => $userId ]);
     dump($information);   
        // si le user n'a aucune information j'instancie ma classe Information 
        if ($information === null ) {
            $information = new Information();
        }

        $form = $this->createForm(InformationType::class, $information);

        $form->handleRequest($request);

        // enregistrement des données
        if ($form->isSubmitted() && $form->isValid()) {
            
            $information->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($information);
            $entityManager->flush();

        }

        return $this->render('user/edit_info.html.twig', [
            'infos_form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/user/edit/basic", name="user_edit_basic")
     */
    public function edit_basic(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        // enregistrement des données
        if ($form->isSubmitted() && $form->isValid()) {
            
            $user->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_AUTRE']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        }

        return $this->render('user/edit_basic.html.twig', [
            'basic_form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/user/edit/metier", name="user_edit_metier")
     */
    public function edit_metier(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserMetierFormType::class, $user);

        $form->handleRequest($request);

        // enregistrement des données
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        }

        return $this->render('user/edit_basic.html.twig', [
            'basic_form' => $form->createView(),
            'user' => $user
        ]);
    }


}
