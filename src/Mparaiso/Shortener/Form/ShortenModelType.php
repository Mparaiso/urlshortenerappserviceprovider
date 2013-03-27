<?php

namespace Mparaiso\Shortener\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;

class ShortenModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("original", "text",
            array("required"    => TRUE,
                  "constraints" => array(
                      new Assert\Length(array("min" => 5)), new Assert\Url())))
            ->add("custom", "text",
            array("required"    => FALSE,
                  "constraints" => array(
                      new Assert\Length(array('min' => 4)))));
    }

    function getName()
    {
        return "ShortenModel";
    }
}
