<?php

namespace App\Tests\Form\Type;

use App\Entity\Img;
use App\Form\ImgType;
use Symfony\Component\Form\Test\TypeTestCase;

class ImgTypeTest extends TypeTestCase
{
    public function testSubmitImgTypeValidData()
    {
        $fileName = 'Nom du formulaire.';
        $alt = 'Description du formulaire';

        $formData = [
            'fileName' => $fileName,
            'alt' => $alt,
        ];

        $img = new Img();
        $img->setFileName($fileName)
            ->setAlt($alt);

        $objectToCompare = new Img();

        $form = $this->factory->create(ImgType::class, $objectToCompare); 
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());

        static::assertEquals($img->getFileName(), $objectToCompare->getFileName());
        static::assertEquals($img->getAlt(), $objectToCompare->getAlt());

        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $children = $form->createView()->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}