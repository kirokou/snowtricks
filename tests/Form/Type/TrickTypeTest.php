<?php

namespace App\Tests\Form\Type;

use App\Entity\Img;
use App\Entity\Movie;
use App\Entity\Trick;
use App\Entity\Category;
use App\Form\TrickType;

use Symfony\Component\Form\Test\TypeTestCase;

class TrickTypeTest extends TypeTestCase
{
    public function testSubmitTrickTypeValidData()
    {
        $title = 'titre du trick';
        $description = 'Description du trick';
        

        $category = new Category();
        $category->setTitle('tite category');

        $img = new Img();
        $img->setFileName('nom du fichier')
            ->setAlt('description du fichier');

        $movie = new Movie();
        $movie->setTitle('Titre de la vidéo')
            ->setSrc('Lien de la vidéo');

        $trick = new Trick();
        $trick->setTitle('Titre de la vidéo')
            ->setDescription('Titre de la vidéo')
            ->setCategory($category)
            //->addImg($img)
            ->setMovie($movie);

            $formData = [
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'imgs' => $img,
                'movie' => $movie
            ];

        $objectToCompare = new Trick();
       
        $form = $this->factory->create(TrickType::class, $objectToCompare); 
       
        $form->submit($formData);
    
        static::assertTrue($form->isSynchronized());

        static::assertEquals($trick, $objectToCompare);
        //static::assertEquals($trick->getSrc(), $objectToCompare->getSrc());
        
        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $children = $form->createView()->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}