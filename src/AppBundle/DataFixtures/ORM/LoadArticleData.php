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

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $article = new Article();
        $article->setAuthor($this->getReference('admin-user'));
        $article->setTitle("This is a test");
        $article->setDescription("And this is at the index");

        $manager->persist($article);
        for ($i = 0; $i < 10; $i++)
        {
            $article = new Article();
            $article->setAuthor($this->getReference('user1-user'));
            $article->setTitle("This is a user article nb ".$i."!");
            $article->setDescription("Lorem ipsum dolor sit amet consectetur adipiscing elit cursus curae, dictum facilisi porttitor justo penatibus aptent nascetur dis etiam blandit, laoreet pharetra egestas augue ante vulputate integer massa. Etiam dapibus accumsan non lobortis phasellus dignissim parturient posuere facilisi, ac nibh gravida orci a netus congue ridiculus vitae, torquent purus donec odio scelerisque primis habitasse mauris. Tempor pulvinar sapien tristique potenti odio phasellus suscipit non habitasse ultricies sociosqu aenean egestas, lectus mi cubilia sollicitudin euismod pellentesque id ullamcorper lacinia quis mauris accumsan.

Facilisis ultricies sodales nam eu potenti fermentum taciti aliquet, elementum penatibus mauris mi magna tortor augue, ligula eros facilisi arcu sagittis lacus hendrerit. Tempus tortor curabitur sed orci scelerisque dui curae tristique, eleifend mauris mattis sollicitudin eget penatibus suspendisse, pharetra quam suscipit mi aliquet integer donec. Fermentum habitasse congue eleifend vel massa lacus in nisi, felis hac nunc libero lobortis et luctus faucibus, tempor fusce mollis pharetra tristique nostra nullam.");
            $manager->persist($article);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}