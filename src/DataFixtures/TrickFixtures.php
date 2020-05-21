<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Img;
use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TrickFixtures extends Fixture
{
    const FIXTURES_IMG_DIR_FROM = __DIR__ . '/../../src/DataFixtures/img/';
    const FIXTURES_IMG_DIR_DEST = __DIR__ . '/../../public/uploads/';
        
    /**
     * @param  mixed $passwordEncoder
     * 
     * @return void
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

        
    /**
     * @param  mixed $manager
     * 
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('FR-fr');

        /**** User: Admin and simple User ****/
        for ($j = 0; $j <=1; $j++) {
            $user = new User();
            if ($j == 0) {
                $user->setEmail('user@gmail.com');
                $user->setRoles(['ROLE_USER']);
            } else {
                $user->setEmail('admin@gmail.com');
                $user->setRoles(['ROLE_ADMIN']);
            }
            
            $password = $this->passwordEncoder->encodePassword($user, 'openclassrooms-P6');
            $user->setPassword($password);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $manager->persist($user);
        }

        /**** Category ****/
        $arrayCategoryTitle = ['Slide','Flip','One foot','Old school','Rotation','Grab'];
        foreach ($arrayCategoryTitle as $categoryTitle) {
            $category = new Category();
            $category->setTitle($categoryTitle);
            $manager->persist($category);
        }

        foreach ($this->getFigureData() as [$title, $description, $movieUrl, $imgName]) {
            $trick = new Trick();
            $trick->setTitle($title);
            $trick->setDescription($description);
            $trick->setCategory($category);
            $trick->setUser($user);
            $trick->setCreatedAt(new \DateTime());
            
                /*** Movie ***/
                $movie = new Movie();
                $movie->setTitle($title)
                    ->setSrc($movieUrl);

            $trick->setMovie($movie);

                /*** Comment ***/
                $comment = [];
                for ($i=0; $i<=3; $i++) {
                    $comment[$i] = new Comment();
                    $comment[$i]->setAuthor($user)
                                ->setContent($faker->sentence(5))
                                ->setCreatedAt(new \DateTime())
                                ->setTrick($trick);
                    $trick->AddComment($comment[$i]);
                }

                //Img
                for ($i = 1; $i <= 3; $i++) {
                    $img = new Img();
                    // set newfileName for file
                    $oldFileName = $imgName;
                    $filename = md5(uniqid());
                    $img->setFileName($filename.'.jpg')
                        ->setAlt($faker->sentence);

                    $imgFile = new File(self::FIXTURES_IMG_DIR_FROM . $oldFileName.$i.'.jpg');
                    $imgFile->move(self::FIXTURES_IMG_DIR_DEST, $filename.'.jpg');
                            
                    $img->setTrick($trick);
                    $trick->addImg($img);
                }

            $manager->persist($trick);
        }

        $manager->flush();
    }

        
    /**
     * getFigureData
     */
    private function getFigureData(): array
    {
        return [
            ['Mute',
                'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'M5NTCfdObfs',
                'mute'
            ],
            ['Stalefish',
                'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                '8VsIZiM_Y6c',
                'stalefish'
            ],
            ['Indy',
                'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'yoAesRZcVTo',
                'indy'
            ],
            ['Sade',
                'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'KEdFwJ4SWq4',
                'sade'
            ],
            ['Japan Air',
                'Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'jH76540wSqU',
                'japanair'
            ],
            ['Frontflip',
                'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'yoAesRZcVTo',
                'frontflip'
            ],
            ['Backflip',
                'Un flip est une rotation verticale. Les backflips sont des rotations en arrière. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'Yz4brafqk5A',
                'backflip'
            ],
            ['Seat belt',
                'Seat belt est une rotation verticale. Les backflips sont des rotations en arrière. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'Yz4brafqk5A',
                'seatbelt'
            ],
            ['Nose grab',
                'Saisie de la partie avant de la planche, avec la main avant. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'Yz4brafqk5A',
                'nosegrab'
            ],
            ['Truck driver',
                'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture). Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget volutpat ante. Proin porttitor, lacus sed bibendum blandit, turpis metus lacinia nunc, ac tincidunt mauris tellus ac lorem. Duis rutrum convallis magna, a tempus mi vulputate vel. Sed et ultrices enim. Sed consequat non nunc ac viverra. Mauris at leo et justo pellentesque iaculis. Integer mollis interdum diam a faucibus.
                Maecenas pulvinar suscipit luctus. Nam at condimentum massa, et tempor lectus. Mauris gravida arcu vel est facilisis semper. Ut ac varius velit. Aliquam cursus porta purus. Curabitur eu erat eu augue vehicula bibendum. Integer quis egestas diam, in pretium metus. In non velit dictum, placerat justo egestas, tristique lacus.
                Mauris imperdiet dapibus arcu, eget laoreet sapien ornare a. Quisque non dapibus turpis. Donec fermentum tempus metus, sed semper lectus gravida eget. Pellentesque semper dolor ut sem efficitur, id fermentum eros varius. Integer est justo, placerat et leo eu, efficitur posuere felis. Suspendisse at cursus sem. In sagittis eros non sem volutpat consequat. Suspendisse feugiat ut lectus eget imperdiet. Phasellus vitae pharetra velit. Suspendisse a lacinia elit, at elementum turpis. Vestibulum in turpis quis elit volutpat sagittis sit amet ac dolor. Quisque nibh tellus, euismod in nisl sed, laoreet finibus justo. Duis auctor arcu et elit efficitur, vel viverra risus rhoncus. Donec laoreet nunc a est porttitor, quis semper urna imperdiet. ',
                'Yz4brafqk5A',
                'truckdriver'
            ],
        ];
    }
}
