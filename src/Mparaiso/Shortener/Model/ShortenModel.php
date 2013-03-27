<?php

namespace Mparaiso\Shortener\Model;

class ShortenModel{
	protected $original;
	protected $custom;
	function getOriginal(){
		return $this->original;
	}
	function setOriginal($original){
		$this->original=$original;
		return $this;
	}
	function getCustom(){
		return $this->custom;
	}
	function setCustom($custom){
		$this->custom=$custom;
		return $this;
	}
}