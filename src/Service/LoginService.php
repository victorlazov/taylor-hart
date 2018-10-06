<?php

namespace App\Service;

use App\Entity\User;

class LoginService
{
    private $repository;
    private $user;

    public function setRepository($repository): LoginService
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
     * User setter.
     *
     * Loads a user from the database by provided email address.
     *
     * @param $email
     *
     * @return $this
     */
    public function setUser($email): LoginService
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
    protected function checkPassword($plainPass)
    {
        if ($this->getUser()->getPassword() === $plainPass) {
            return true;
        }

        return false;
    }

    /**
     * Checks the provided password against the loaded user details.
     *
     * @param $password
     *
     * @return bool
     */
    public function checkAuth($password)
    {
        if ($this->checkPassword($password)) {
            return $this->user;
        }

        return false;
    }
}