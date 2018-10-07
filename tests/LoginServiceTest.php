<?php

namespace App\Tests;

use App\Entity\User;
use App\Service\LoginService;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class LoginServiceTest extends WebTestCase
{
    private $entityManager;

    private $email = 'loginservicetest@localhost';
    private $password = '1234';
    private $username = 'loginservicetest';

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();

        $this->entityManager = self::$container->get('doctrine')->getManager();
    }

    public function testAuthentication()
    {
        $user = $this->createUser(); // Create new user

        $userRepository = self::$container->get('doctrine')->getRepository(User::class);
        $session        = new Session(new MockArraySessionStorage());

        $loginService = new LoginService($session, $userRepository);
        $this->authSuccess($loginService);
        $this->authUnsuccessful($loginService);

        $this->cleanup($user);
    }

    private function authSuccess(LoginService $loginService) {
        echo "\nTesting successful authentication.\n";

        $loginService->authenticate($this->email, $this->password);
        $this->assertEquals(true, $loginService->checkAuth(), "User authentication unsuccessful!\n");
        $loginService->logout(); // Log out the user for future tests.
    }

    private function authUnsuccessful(LoginService $loginService) {
        echo "\nTesting unsuccessful authentication.\n";

        $loginService->authenticate('wrong_email@localhost', $this->password);
        $this->assertEquals(false, $loginService->checkAuth(), "Wrong email auth failure unsuccessful!\n");
        $loginService->logout(); // Log out the user for future tests.

        $loginService->authenticate($this->email, 'wrong_pass');
        $this->assertEquals(false, $loginService->checkAuth(), "Wrong password auth failure unsuccessful!\n");
        $loginService->logout(); // Log out the user for future tests.
    }

    private function createUser(): User
    {
        $user = new User();
        $user->setEmail($this->email);
        $user->setPassword($this->password);
        $user->setUsername($this->username);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        echo "\nUser successfully created.\n";

        return $user;
    }

    private function cleanup($user) {
        $user = $this->entityManager->merge($user);
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        echo "\nCleanup process ended.\n";
    }
}