<?php

namespace App\Controller;

use App\Entity\Information;
use App\Form\InformationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/edit/infos", name="user_edit_info")
     */
    public function edit_info()
    {
        $user = $this->getUser();

        $information = new Information();

        $form = $this->createForm(InformationType::class, $information);

        return $this->render('user/edit_info.html.twig', [
            'infos_form' => $form->createView(),
            'user' => $user
        ]);
    }

}
