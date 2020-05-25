<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentTest extends KernelTestCase
{

    /**
     * @return Comment
     */
    private function getComment(): Comment
    {
        $Comment = new Comment();
        $Comment->setContent('Description de l\'du Comment');

        return $Comment;
    }

    /**
     * @param  mixed $comment
     * @param  mixed $number error
     * 
     * @return void
     */
    private function assertHasErrors(Comment $comment, int $number=0): void
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($comment);
        $this->assertCount($number,$error);
    }

    public function testContentFieldsValid(): void
    {
        $comment = $this->getComment();
        $comment->setContent('Contenu du commentaire.');

        $this->assertHasErrors($comment);
    }

    public function testCommentFieldsFieldsLengthError(): void
    {
        $comment = $this->getComment();
        $this->assertHasErrors($comment->setContent('t'), 1);
        $this->assertHasErrors($comment->setContent('Content'));
       // $this->assertHasErrors($comment->setContent(''),1);
    }
}
