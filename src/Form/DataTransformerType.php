<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class StringToFileTransformer implements DataTransformerInterface
{
    public function transform($file)
    {
        // transform the File object into a string
        if ($file instanceof File) {
            return $file->getPathname();
        }

        return null;
    }

    public function reverseTransform($string)
    {
        // transform the string back into a File object
        return new File($string);
    }
}
