<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setUpdatedate(new \DateTime());
            
            ($user->setCreationdate(new \DateTime()));
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('confirmation');
        }
        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/confirmationAjout", name="confirmation")
     */
    public function confirmation()
    {
        return $this->render('user/confirmation.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/afficherUtilisateur/{id}", name="show")
     */
    public function show($id, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['id'=>$id]);
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/modifierUtilisateur/{id}", name="edit")
     */
    public function edit(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user ->setUpdatedate(new \dateTime('',new \dateTimeZone('Europe/Paris')));
        

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('L\'utilisateur a bien été modifié.');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'controller_name' => 'UserController',
        ]);
    }

     /**
     * @Route("/supprimerUtilisateur/{$id}", name="remove")
     */
    public function remove($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $em->remove($user);
        $em->flush();

    }
}
