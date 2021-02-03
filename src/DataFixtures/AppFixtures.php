<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Article;
use App\Entity\Comments;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        
        $user = new User();
        $user -> setUsername($faker->name().'du68')
              -> setFirstname($faker->name())
              -> setLastname($faker->name())
              -> setPassword($faker->md5())
              -> setBornAt($faker->dateTime())
              -> setRoles(['user'])
              -> setEmail($faker->email());
        $manager-> persist($user);
        
        for($c=1;$c < 5;$c++)
        {
            $cat = new Category();
            $cat->setName($faker->realText(20))
                ->setDescription($faker->realText(200));
            $manager->persist($cat);

            for($p=1;$p<10;$p++)
            {
                $article = new Article();
                $article->setTitle($faker->realText(20))
                        ->setContent($faker->realText(200))
                        ->setCreatedat(new \DateTime())
                        ->setPublishedat(new \DateTime())
                        ->addCategoryId($cat)
                        ->setUserId($user)
                        ->setImage('https://picsum.photos/id/' . $faker->numberBetween(1, 1050) . '/1920/1080');

                $manager->persist($article);
            }
        }
        $manager->flush();
    }
}