<?php

namespace App\Form\DataTransformers;

use Symfony\Component\Form\DataTransformerInterface;

interface DataTransformableInterface
{
    public function addDataTransformer(string $field, DataTransformerInterface $dataTransformer);
}