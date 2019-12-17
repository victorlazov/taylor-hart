<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\LoginService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class LoginServiceTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserRepository
     */
    private $userRepository;

    private $email = 'loginservicetest@localhost';
    private $password = '1234';
    private $username = 'loginservicetest';

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

    }

    protected function setUp()
    {
        self::bootKernel();
        $this->entityManager = self::$container->get('doctrine')->getManager();
        $this->userRepository = self::$container->get('doctrine')->getRepository(User::class);
        $this->session = new Session(new MockArraySessionStorage());
    }

    public function testSuccessfulAuthentication()
    {
        $user = $this->createUser(); // Create new user

        $loginService = new LoginService($this->session, $this->userRepository);
        $loginService->authenticate($this->email, $this->password);
        $this->assertEquals(true, $loginService->checkAuth(), "User authentication unsuccessful!\n");
        $loginService->logout(); // Log out the user for future tests.

        $this->cleanup($user);
    }

    public function testUnsuccessfulAuthentication()
    {
        $user = $this->createUser(); // Create new user

        $loginService = new LoginService($this->session, $this->userRepository);
        $loginService->authenticate('wrong_email@localhost', $this->password);
        $this->assertEquals(false, $loginService->checkAuth(), "Wrong email auth failure unsuccessful!\n");
        $loginService->logout(); // Log out the user for future tests.

        $loginService->authenticate($this->email, 'wrong_pass');
        $this->assertEquals(false, $loginService->checkAuth(), "Wrong password auth failure unsuccessful!\n");
        $loginService->logout(); // Log out the user for future tests.

        $this->cleanup($user);
    }

    private function createUser(): User
    {
        $user = (new User())
            ->setEmail($this->email)
            ->setPassword($this->password)
            ->setUsername($this->username)
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function cleanup(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}