<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class SimpleArrayTransformer
 *
 * @package AppBundle\Form\DataTransformer
 */
class SimpleArrayTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value) {
            // trim all the values
            $value = array_map('trim', $value);
            // join together
            $string = implode(',', $value);

            return $string;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($value) {
            // string to array
            $array = explode(',', $value);
            // trim all the values
            $array = array_map('trim', $array);

            return $array;
        }
    }
}
