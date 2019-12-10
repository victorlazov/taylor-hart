<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\LoginService;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     *
     * @param Request $request
     * @param LoginService $loginService
     * @param RegistrationService $registrationService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(
        Request $request,
        LoginService $loginService,
        RegistrationService $registrationService
    ) {
        // Bail early if the user is logged in.
        if ($loginService->checkAuth()) { // Authentication successful!
            return $this->redirectToRoute('video_index');
        }

        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('username', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Register!'])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($form->isValid());
            $user = $form->getData(); // Get the submitted data

            // Persist the submitted data.
            $registrationService->persistData($user);

            return $this->redirectToRoute('login', [
                'reg_success' => true,
            ]);
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
            'page_name' => 'Registration',
        ]);
    }
}