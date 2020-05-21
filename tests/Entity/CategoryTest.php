<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    /**
     * @param  mixed $category
     * @param  mixed $number
     * 
     * @return void
     */
    private function assertHasErrors(Category $category, int $number=0): void
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($category);
        $this->assertCount($number,$error);
    }

    public function testTitleFieldsValid(): void
    {
        $category = new Category();
        $category->setTitle('titre de la catégory');

        $this->assertHasErrors($category);
    }

    public function testCategoryFieldsLengthError()
    {
        $category = new Category();

        $this->assertHasErrors($category->setTitle('t'), 1);
        $this->assertHasErrors($category->setTitle('title'));
        //$this->assertHasErrors($category->setTitle(''), 1);
    }

}
