<?php
namespace App\Helpers;

class Helper
{
	public static function generateRandomString($length = 8)
	{
		$characters 		= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length 	= strlen($characters);
		$random_string		= '';

		for ($i=0; $i < $length; $i++) { 
			$random_string .= $characters[rand(0, $characters_length - 1)];
		}

		return $random_string;
	}
}