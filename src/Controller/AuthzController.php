<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthzController extends AbstractController
{
    public function index()
    {

    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
                     ->add('email', EmailType::class)
                     ->add('password', PasswordType::class)
                     ->add('username', TextType::class)
                     ->add('save', SubmitType::class, ['label' => 'Register!'])
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update the user with the submitted data.
            $user = $form->getData();

            // Save the  @#$%^&.
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('registration_success');
        }

        return $this->render('authz/register.html.twig', [
            'form'      => $form->createView(),
            'page_name' => 'Registration',
        ]);
    }

    /**
     * @Route("/reg_ok", name="registration_success")
     */
    public function registrationSuccess()
    {
        return $this->render("authz/success.html.twig");
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('authz/login.html.twig', [
            'page_name' => 'Login',
        ]);
    }
}
