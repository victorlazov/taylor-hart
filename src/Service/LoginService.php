<?php

namespace App\Service;

use App\Entity\User;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginService
{
    private $session;
    private $repository;

    public function __construct(SessionInterface $session, UserRepository $repository)
    {
        $this->session    = $session;
        $this->repository = $repository;
    }

    /**
     * Session getter.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * Looks up if the user is in the database
     *
     * @param $email
     *
     * @return null|User
     */
    protected function getUserByEmail($email): ?User
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    /**
     * Checks provided password against the loaded user.
     *
     * @param $plainPass
     * @param $userPass
     *
     * @return bool
     */
    protected function checkPassword($plainPass, $userPass): bool
    {
        if ($userPass === $plainPass) {
            return true;
        }

        return false;
    }

    /**
     * Performs authentication logic.
     *
     * @param $email
     * @param $password
     */
    public function authenticate($email, $password): void
    {
        $user = $this->getUserByEmail($email);

        if ($user && $this->checkPassword($password, $user->getPassword())) {
            $this->session->invalidate();
            $this->session->start();

            $this->session->set('uid', $user->getId());
            $this->session->set('username', $user->getUsername());
        }
    }

    /**
     * Checks if the user is authenticated or not.
     *
     * @return bool
     */
    public function checkAuth(): bool
    {
        if ($this->session && ! empty($this->session->get('uid'))) {
            return true;
        }

        return false;
    }

    /**
     * Logs out the user.
     */
    public function logout(): void
    {
        $this->session->invalidate();
    }
}