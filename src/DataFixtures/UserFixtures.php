<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('momo');
        $user->setPassword($this->encoder->encodePassword($user, 'momo'));
		$user->setRoles(array('ROLE_ADMIN'));
		//$user->setRoles('ROLE_ADMIN');
		//$user->addRole("ROLE_ADMIN");
        $manager->persist($user);
        $manager->flush();
    }
}
