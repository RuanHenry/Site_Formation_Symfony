<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class FrenchToDateTimeTransformer implements DataTransformerInterface {
    
    public function transform($date) {
        if($date === null) {
            return '';
        }

        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate) {
        //frenchDate 20/09/2018
        if($frenchDate === null) {
            //exception
            throw TransformationFailedException("Vous devez fournir une date !");
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if($date === false) {
            // exception
            throw TransformationFailedException("Le format de la date n'est pas le bon !");
        }

        return $date;
    }
}


?>