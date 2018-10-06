<?php

namespace App\Service;

use App\Entity\User;

use Symfony\Component\HttpFoundation\Session\Session;

class LoginService
{
    private $repository;
    private $user;
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * Sets object repository.
     *
     * @param $repository
     *
     * @return \App\Service\LoginService
     */
    public function setRepository($repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * User getter.
     *
     * @return \App\Entity\User
     */
    protected function getUser(): User
    {
        return $this->user;
    }

    /**
     * Session getter.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * User setter.
     *
     * Loads a user from the database by provided email address.
     *
     * @param $email
     *
     * @return $this
     */
    public function setUser($email): self
    {
        $this->user = $this->repository->findOneBy(['email' => $email]);

        return $this;
    }

    /**
     * Checks provided password against the loaded user.
     *
     * @param $plainPass
     *
     * @return bool
     */
    protected function checkPassword($plainPass): bool
    {
        if ($this->getUser()->getPassword() === $plainPass) {
            return true;
        }

        return false;
    }

    /**
     * Performs authentication logic.
     *
     * @param $password
     */
    public function authenticate($password): void
    {
        if ($this->checkPassword($password)) {
            $this->session->invalidate();
            $this->session->start();

            $this->session->set('uid', $this->getUser()->getId());
            $this->session->set('username', $this->getUser()->getUsername());
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