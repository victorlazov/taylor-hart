<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\LoginService;
use App\Service\RegistrationService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthzController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, RegistrationService $registrationService)
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
            $user = $form->getData(); // Get the submitted data
            $entityManager = $this->getDoctrine()->getManager(); // Get the object manager

            $registrationService
                ->setEntityManager($entityManager)
                ->setUserData($user);

            // Persist the submitted data.
            $registrationService->persistData();

            return $this->redirectToRoute('login', [
                'reg_success' => true,
            ]);
        }

        return $this->render('authz/register.html.twig', [
            'form'      => $form->createView(),
            'page_name' => 'Registration',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, LoginService $loginService)
    {
        $form = $this->createFormBuilder()
                     ->add('email', EmailType::class)
                     ->add('password', PasswordType::class)
                     ->add('submit', SubmitType::class, ['label' => 'Login!'])
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $repository = $this->getDoctrine()->getRepository(User::class);

            $user = $loginService
                ->setRepository($repository)
                ->setUser($data['email'])
                ->checkAuth($data['password']);

            if ($user) {
                // TODO: log the user in!
            }

            // Redirect the user once logged in.
            return $this->redirectToRoute('login', [
                'login_success' => false,
            ]);
        }

        return $this->render('authz/login.html.twig', [
            'form'      => $form->createView(),
            'reg_success' => false
        ]);
    }
}
