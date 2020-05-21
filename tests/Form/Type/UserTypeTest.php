<?php

namespace App\Tests\Form\Type;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testSubmitUserTypeValidData()
    {
        $formData = [
            'email' => 'test_user@gmail.com',
            'firstname' => 'John',
            'lastname' => 'Woo',
            'avatar' => 'https://www.google.com'
        ];

        $user = new User();
        $user->setEmail('test_user@gmail.com');
        $user->setFirstname('John');
        $user->setLastname('Woo');
        $user->setAvatar('https://www.google.com');

        $objectToCompare = new User();
        $form = $this->factory->create(UserType::class, $objectToCompare); 
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());

        static::assertEquals($user, $objectToCompare);
        //static::assertEquals($user->getEmail(), $objectToCompare->getEmail());

        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
        
    }
   
}