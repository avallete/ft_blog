<?php
/**
 * Created by PhpStorm.
 * User: avallete
 * Date: 28/08/17
 * Time: 20:19
 */
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Article;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword($encoder->encodePassword($userAdmin, 'admin'));
        $userAdmin->setRoles(['ROLE_ADMIN']);

        $user1 = new User();
        $user1->setUsername('user1');
        $user1->setPassword($encoder->encodePassword($user1, 'user1'));

        $manager->persist($userAdmin);
        $manager->persist($user1);
        $manager->flush();

        $this->addReference('admin-user', $userAdmin);
        $this->addReference('user1-user', $user1);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}