<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\LoginService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @param LoginService $loginService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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

            $loginService->authenticate($data['email'], $data['password']);

            if ($loginService->checkAuth()) { // Authentication successful!
                return $this->redirectToRoute('video_index');
            }

            // Authentication failed.
            return $this->redirectToRoute('login', [
                'login_success' => false,
            ]);
        }

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     *
     * @param LoginService $loginService
     * @return RedirectResponse
     */
    public function logout(LoginService $loginService): RedirectResponse
    {
        $loginService->logout();

        return $this->redirectToRoute('login');
    }
}
