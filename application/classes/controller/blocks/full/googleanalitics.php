<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Full_Googleanalitics extends Blocks_Abstract
{
    public function render()
    {

        $url = explode('/',$_SERVER['REQUEST_URI']);
        $sec = (isset($url[1]) AND ! empty($url[1])) ? $url[1] : '/';

        $this->template->sec = $sec;

	if (strpos($_SERVER['SERVER_NAME'], 'www.') === false)
	{
	    $this->template->domain =  '.'.$_SERVER['SERVER_NAME'];
	} else 
	{
    	    $this->template->domain =  strstr($_SERVER['SERVER_NAME'], '.');
    	}
        
        switch ($sec) {
	    case 'cat-beauty' : 
		$code = 'UA-25600556-2';
	    break;
	    case 'cat-stars' :
		$code = 'UA-25600556-3';  
	    break;
	    case 'cat-fashion': 
		$code = 'UA-25600556-4';  
	    break;
	    case 'cat-health' : 
		$code = 'UA-25600556-5';
	    break;
	    case 'cat-sex' : 
		$code = 'UA-25600556-6';
	    break;
	    case 'cat-shoping' : 
		$code = 'UA-25600556-7';
	    break;
	    case 'cat-house' :
		$code = 'UA-25600556-8';
	    break;
	    case 'cat-child' :
		$code = 'UA-25600556-9';
	    break;
	    case 'cat-relax' :
		$code = 'UA-25600556-10';
	    break;
	    case 'horoscope' :
		$code = 'UA-25600556-11';
	    break;
	    case 'cat-wiki' :
		$code = 'UA-25600556-12';
	    break;
	    case 'cat-cosmetik-opinions' :
		$code = 'UA-25600556-13'; 
	    break;

	    default:
		$code = 'UA-25600556-1';
    	}
    	
	$this->template->code = $code;
    }
}
