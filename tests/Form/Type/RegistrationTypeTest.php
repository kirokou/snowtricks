<?php

namespace App\Tests\Form\Type;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class RegistrationTypeTest extends TypeTestCase
{
    public function testSubmitUserTypeValidData()
    {
        $email = 'test-email@gmail.com';
        $firstname = 'John';
        $lastname = 'Woo';
        //$password = 'toto@';

        $formData = [
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
           // 'password' => $password
        ];

        $user = (new User())->setEmail($email)
            ->setFirstname($firstname)
            ->setLastname($lastname);
            //->setPassword($password);

        $objectToCompare = new User();

        $form = $this->factory->create(UserType::class, $objectToCompare); 
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());

        static::assertEquals($user->getEmail(), $objectToCompare->getEmail());
        //static::assertEquals($user->getSrc(), $objectToCompare->getSrc());
        
        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $children = $form->createView()->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
