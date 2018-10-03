<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthzController extends AbstractController
{
    public function index()
    {

    }

    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        return $this->render('authz/register.html.twig', [
            'page_name' => 'Registration',
        ]);
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
