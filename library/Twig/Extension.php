<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

namespace App\Twig;

class Extension extends \Twig_Extension
{
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('dmitry_ages', array($this, 'dmitryAges')),
		);
	}

	public function dmitryAges()
	{
		$birthday = new \DateTime('1994-11-26 13:25:00');
		return (new \DateTime())->diff($birthday)->y;
	}

	public function getName()
	{
		return 'app_extension';
	}
} 