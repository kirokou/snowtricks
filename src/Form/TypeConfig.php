<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class TypeConfig extends AbstractType
{
    protected function getConfiguration($label, $placeholder, $required = true)
    {
        return [
            'label'=> $label,
            'attr'=> [
                'placeholder'=>$placeholder
            ],
            'required'=>$required
        ];
    }
}
