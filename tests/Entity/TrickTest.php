<?php

namespace App\Tests\Entity;

use App\Entity\Img;

use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrickTest extends KernelTestCase
{
    
    /**
     * @return Trick
     */
    private function getTrick(): Trick
    {
        $trick = new Trick();
        $trick->setTitle('titre');
        $trick->setDescription('Description de l\'du trick');

        return $trick;
    }
    
    /**
     * @param  mixed $trick
     * @param  mixed $number
     * 
     * @return void
     */
    private function assertHasErrors(Trick $trick, int $number=0): void
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($trick);
        $this->assertCount($number,$error);
    }

    public function testSimplesFieldsValid(): void
    {
        $trick = $this->getTrick();
        $this->assertHasErrors($trick);
    }

    public function testMovieFieldsValid()
    {
        $trick = $this->getTrick();

        $movie = new Movie();
        $movie->setTitle('titre')
            ->setSrc('https://www.google.fr/');
        $trick->setMovie($movie);

        $this->assertHasErrors($trick);
    }


    public function testCategoryFieldsValid()
    {
        $trick = $this->getTrick();

        $category = new Category();
        $category->setTitle('categoryTitle');
        $trick->setCategory($category);

        $this->assertHasErrors($trick);
    }


    public function testUserFieldsValid()
    {
        $trick = $this->getTrick();

        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setRoles(['ROLE_USER']);

        $this->assertHasErrors($trick);
    }

    public function testImgsFieldsValid()
    {
        $trick = $this->getTrick();
        
        $img = new Img();
        $img->setTrick($trick);
        $trick->addImg($img);

        $this->assertHasErrors($trick);
    }

    public function testCommentFieldsValid()
    {
        $user = new User();
        $user->setEmail('user@gmail.com')
            ->setRoles(['ROLE_USER']);

        $trick = $this->getTrick();

        $comment = new Comment();
        $comment->setAuthor($user)
                ->setContent('faker sentence faker sentence faker sentence')
                ->setTrick($trick);
        $trick->AddComment($comment);

        $this->assertHasErrors($trick);
    }

    public function testTitleFieldsLengthError()
    {
        $this->assertHasErrors($this->getTrick()->setTitle('toto'), 1);
        $this->assertHasErrors($this->getTrick()->setTitle('title'));
        //$this->assertHasErrors($this->getTrick()->setTitle(''), 1);
    }

}
