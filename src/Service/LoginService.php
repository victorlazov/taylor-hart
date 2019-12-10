<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginService
{
    private $session;
    private $userRepository;

    public function __construct(SessionInterface $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    /**
     * Checks provided password against the loaded user.
     *
     * @param $plainPass
     * @param $userPass
     *
     * @return bool
     */
    protected function checkPassword(string $plainPass, string $userPass): bool
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
    public function authenticate(string $email, string $password): void
    {
        $user = $this->userRepository->findUserByEmail($email);

        if ($user && $this->checkPassword($password, $user->getPassword())) {
            $this->session->invalidate();
            $this->session->start();

            $this->session->set('user', $user);
        }
    }

    /**
     * Checks if the user is authenticated or not.
     *
     * @return bool
     */
    public function checkAuth(): bool
    {
        return !empty($this->session->get('user'));
    }

    /**
     * Logs out the user.
     */
    public function logout(): void
    {
        $this->session->invalidate();
    }
}