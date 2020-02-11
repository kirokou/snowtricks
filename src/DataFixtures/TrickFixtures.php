<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Img;
use App\Entity\Group;
use App\Entity\Movie;
use App\Entity\Trick;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getFigureData() as [$name, $description, $videoUrl]) {
            
            $group = new Group();
            $group->setTitle('break');
            
            $figure = new Trick();
            $figure->setTitle($name);
            $figure->setDescription($description);
            $figure->setCategory(null);
            $figure->setCreatedAt(new \DateTime());
            $figure->setUpdatedAt(new \DateTime());
            

            //$figure->setSlug((new FigureController())->slugify($figure->getName()));

            $video = new Movie();
            $video->setTitle($name);
            $video->setSrc($videoUrl);
            $figure->setMovie($video);

            $picture = new Img();
            $picture->setAlt($videoUrl);
            $picture->setExt('jpg');
            //$manager->persist($picture);
            $figure->addImg($picture);

            $comment = new Comment();
            $comment->setAuthor($name);
            $comment->setContent($description);
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());

            $figure->addComment($comment);

            $manager->persist($figure);
        }

        $manager->flush();
    }

    private function getFigureData(): array
    {
        return [
            ['Mute',
                'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.',
                'https://www.youtube.com/embed/M5NTCfdObfs',
                'Premier commentaire de la figure Mute',
                'mute.jpg'],
            ['Stalefish',
                'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.',
                'https://www.youtube.com/embed/8VsIZiM_Y6c',
                'Premier commentaire de la figure Stalefish',
                'stalefish.jpg'],
            ['Indy',
                'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.',
                'https://www.youtube.com/embed/yoAesRZcVTo',
                'Premier commentaire de la figure Indy',
                'indy.jpg'],
            ['Sade',
                'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.',
                'https://www.youtube.com/embed/KEdFwJ4SWq4',
                'Premier commentaire de la figure Sade',
                'sade.jpg'],
            ['Japan Air',
                'Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.',
                'https://www.youtube.com/embed/jH76540wSqU',
                'Premier commentaire de la figure Japan Air',
                'japanair.jpg'],
            ['Frontflip',
                'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.',
                'https://www.youtube.com/embed/yoAesRZcVTo',
                'Premier commentaire de la figure Frontflip',
                'frontflip.jpg'],
            ['Backflip',
                'Un flip est une rotation verticale. Les backflips sont des rotations en arrière.',
                'https://www.youtube.com/embed/Yz4brafqk5A',
                'Premier commentaire de la figure Backflip',
                'backflip.jpg'],
        ];
    }
}
