<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@mail.com')
                ->setPassword($this->passwordEncoder->encodePassword($user, 'mdpmdp'))
                ->setName($faker->firstName)
                ->setIsVerified(true);
            $manager->persist($user);
        }

        $admin = new User();
        $admin->setEmail('admin@mail.com')
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'mdpmdp'))
            ->setRoles(["ROLE_ADMIN"])
            ->setName('admin')
            ->setIsVerified(true);
        $manager->persist($admin);

        $manager->flush();

    }
}
