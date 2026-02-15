<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ArrayToJSONStringTransformer implements DataTransformerInterface
{
     
    /**
    * Transform an array to a JSON string
    */
    public function transform(mixed $value): mixed
    {
        return json_encode($value);
    }

    /**
    * Transform a JSON string to an array
    */
    public function reverseTransform(mixed $value): mixed
    {
        return json_decode((string) $value, true);
    }
}
