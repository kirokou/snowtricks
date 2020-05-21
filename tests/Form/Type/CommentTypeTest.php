<?php

namespace App\Tests\Form\Type;

use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function testSubmitCommentTypeValidData()
    {
        $content = 'Contenu du commentaire de l\'article X.';

        $formData = [
            'content' => $content
        ];

        $comment = new Comment();
        $comment->setContent($content);

        $objectToCompare = new Comment();

        $form = $this->factory->create(CommentType::class, $objectToCompare); 
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());

        static::assertEquals($comment->getContent(), $objectToCompare->getContent());

        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $children = $form->createView()->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}