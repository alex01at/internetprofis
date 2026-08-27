<?php

function slug($string, $space="-") {

	$string = preg_replace("/[^a-zA-Z0-9\-]/", "", $string);
	$string = trim($string);
	$string = strtolower($string);
	$string = str_replace(" ", $space, $string);
  
	$special_chars = array("/Ğ/", "/Ü/", "/Ş/", "/İ/", "/Ö/", "/Ç/", "/ğ/", "/ü/", "/ş/", "/ı/", "/ö/", "/ç/");
	$string = preg_replace($special_chars, "", $string);
  
	return $string;
  
  }
  