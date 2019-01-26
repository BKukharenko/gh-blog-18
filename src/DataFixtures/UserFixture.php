<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@test.com');
        $user->setFullName('John Doe');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, '128769'));
        $manager->persist($user);
        $this->setReference('user', $user);

        $admin = new User();
        $admin->setEmail('admin@test.com');
        $admin->setFullName('Admin');
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, '128769'));
        $manager->persist($admin);
        $this->setReference('admin', $admin);

        $manager->flush();
    }
}
