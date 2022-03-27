<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ArrayToJSONStringTransformer implements DataTransformerInterface
{
     
    /**
    * Transform an array to a JSON string
    */
    public function transform($array)
    {
        return json_encode($array);
    }

    /**
    * Transform a JSON string to an array
    */
    public function reverseTransform($string)
    {
        return json_decode($string, true);
    }
}