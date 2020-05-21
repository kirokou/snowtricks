<?php

namespace App\Tests\Form\Type;

use App\Entity\Movie;
use App\Form\MovieType;
use Symfony\Component\Form\Test\TypeTestCase;

class MovieTypeTest extends TypeTestCase
{
    public function testSubmitMovieTypeValidData()
    {
        $formData = [
            'title' => 'Titre de la vidéo',
            'src' => 'Lien de la vidéo'
        ];

        $movie = new Movie();
        $movie->setTitle('Titre de la vidéo')
            ->setSrc('Lien de la vidéo');

        $objectToCompare = new Movie();

        $form = $this->factory->create(MovieType::class, $objectToCompare); 
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());

        static::assertEquals($movie->getTitle(), $objectToCompare->getTitle());
        static::assertEquals($movie->getSrc(), $objectToCompare->getSrc());
        
        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $children = $form->createView()->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}