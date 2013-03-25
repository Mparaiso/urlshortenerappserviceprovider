<?php

namespace Mparaiso\UrlShortener\Form;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class ShortenModelType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder->add("original","text",array("required"=>true))
		->add("custom","text",array("required"=>false));
	}
	
	function getName(){
		return "ShortenModel";
	}
}
