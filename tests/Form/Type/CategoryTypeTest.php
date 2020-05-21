<?php

namespace App\Tests\Form\Type;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

class CategoryTypeTest extends TypeTestCase
{
   
    public function testSubmitCategoryTypeValidData()
    {
        $formData = [
            'title' => 'Test title'
        ];

        // Manually populated object
        $category = new Category();
        $category->setTitle('Test title');

        // Object that should be the same than the manually populated object
        $objectToCompare = new Category();

        // Form creation that we link to our objectToCompare
        $form = $this->factory->create(CategoryType::class, $objectToCompare); 
        $form->submit($formData);

        // Verification that form was correctly sent (data transformers didn't failed)
        static::assertTrue($form->isSynchronized());

        // Check that $objectToCompare was modified as expected when the form was submitted
        static::assertEquals($category, $objectToCompare);
        static::assertEquals($category->getTitle(), $objectToCompare->getTitle());

        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $children = $form->createView()->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /*
    public function testCustomFormView()
    {
        $formData = new Category();

        // The initial data may be used to compute custom view variables
        $view = $this->factory->create(CategoryType::class, $formData)
            ->createView();

        $this->assertArrayHasKey('title', $view->vars);
        $this->assertSame('Title_value', $view->vars['title']);
    }
    */
   
}