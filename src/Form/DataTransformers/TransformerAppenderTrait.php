<?php

namespace App\Form\DataTransformers;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

trait TransformerAppenderTrait
{
    private $dataTransformers = [];

    public function addDataTransformer(string $field, DataTransformerInterface $dataTransformer)
    {
        $this->dataTransformers[$field] = $dataTransformer;
    }

    private function appendTransformersToFields(FormBuilderInterface $builder)
    {
        foreach ($this->dataTransformers as $field => $transformer) {
            if ($builder->has($field)) {
                $builder->get($field)->addModelTransformer($transformer);
            }
        }
    }
}