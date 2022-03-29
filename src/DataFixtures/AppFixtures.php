<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("FR-fr");

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Henry')
                    ->setLastName('Ruan')
                    ->setEmail('Henry-R@hotmail.fr')
                    ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                    ->setPicture('https://avatars.io/twitter/LiiorC')
                    ->setIntroduction($faker->sentence())
                    ->setText($faker->sentence())
                    ->addUserRole($adminRole);
                    
        $manager->persist($adminUser);

        $users = [];
        $genres = ['male', 'femelle'];

        // Nous gérons les users 
        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setText($faker->sentence())
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // Nous gérons les annonces
        for($i =1; $i <= 30; $i++) {

            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = $faker->paragraph(5);

            $user = $users[mt_rand(0, count($users) - 1 )];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);

            for($j = 1; $j <= mt_rand(2,5); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                ->setCaption($faker->sentence())
                ->setAd($ad);
                
                $manager->persist($image);
            }
            $manager->persist($ad);

        }
        

        $manager->flush();
    }
}
