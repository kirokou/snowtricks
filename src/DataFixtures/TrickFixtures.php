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

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('FR-fr');

        //User: Admin and simple User
        for($j=0; $j<=1; $j++)
        {
            $user = new User();
            if($j==0){
                $user->setEmail('user@gmail.com');
                $user->setRoles(['ROLE_USER']);
            }
                $user->setEmail('admin@gmail.com');
                $user->setRoles(['ROLE_ADMIN']);
            
            
            $password=$this->passwordEncoder->encodePassword($user,'openclassrooms-P6');
            $user->setPassword($password);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            //I need to persist it because it indep
            $manager->persist($user);
        }

        

        foreach ($this->getFigureData() as [$title, $description, $movieUrl, $imgName]) 
        {
            $trick = new Trick();
                $trick->setTitle($title);
                $trick->setDescription($description);
                $trick->setUser($user);    
                $trick->setCreatedAt(new \DateTime());
                    //Movie
                    $movie= new movie();
                    $movie->setTitle($title)
                        ->setSrc($movieUrl);
                $trick->setMovie($movie);

                    //Comment
                    $comment=[];
                    for($i=0; $i<=3; $i++)
                    {
                        $comment[$i]= new comment();
                        $comment[$i]->setAuthor($faker->name)
                                    ->setContent($faker->sentence(5))
                                    ->setCreatedAt(new \DateTime())
                                    ->setTrick($trick);
                        $trick->AddComment($comment[$i]);
                    }

                    //Category
                    $arrayCategory=['Slide','Flip','One foot','Old school','Rotation','Grab'];
                    $category= new Category();
                    $category->setTitle($arrayCategory[mt_rand(0,5)]);
                    //I persist it because i need it beford create trick and category is INDEP
                    $manager->persist($category); 
                $trick->setCategory($category);

                    //Img
                    for($i=1; $i<=3; $i++)
                    {
                        $img = new Img();
                        // set newfileName for file
                        $oldFileName=$imgName;
                        $filename = md5(uniqid());
                        $img->setFileName($filename.'.jpg')
                            ->setAlt($faker->sentence); 
                        //try??  que les set se sont bien passer avant le move  
                        //Make new file and Move the file from my fixtures_img_repository with newfileName
                        $imgFile = new File(self::FIXTURES_IMG_DIR_FROM . $oldFileName.$i.'.jpg');
                        $imgFile->move(self::FIXTURES_IMG_DIR_DEST, $filename.'.jpg');
                        
                        $img->setTrick($trick);
                        $trick->addImg($img);
                    }           
            $manager->persist($trick);
        }

        $manager->flush();
    }

    private function getFigureData(): array
    {
        return [
            ['Mute',
                'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.',
                'M5NTCfdObfs',
                'mute'
            ],
            ['Stalefish',
                'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.',
                '8VsIZiM_Y6c',
                'stalefish'
            ],
            ['Indy',
                'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.',
                'yoAesRZcVTo',
                'indy'
            ],
            ['Sade',
                'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.',
                'KEdFwJ4SWq4',
                'sade'
            ],
            ['Japan Air',
                'Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.',
                'jH76540wSqU',
                'japanair'
            ],
            ['Frontflip',
                'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.',
                'yoAesRZcVTo',
                'frontflip'
            ],
            ['Backflip',
                'Un flip est une rotation verticale. Les backflips sont des rotations en arrière.',
                'Yz4brafqk5A',
                'backflip'
            ],
        ];
    }

}
