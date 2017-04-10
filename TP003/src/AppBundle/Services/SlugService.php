<?php
	
namespace AppBundle\Services;

class SlugService{
	
	public function __construct(){
		
	}

	public function slugify($value){
        $string = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $value);
        $string = preg_replace('/[-\s]+/', '-', $string);
        return trim($string, '-');
    }

}