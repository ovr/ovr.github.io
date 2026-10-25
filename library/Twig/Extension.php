<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
	public function getFunctions()
	{
		return array(
			new TwigFunction('dmitry_ages', array($this, 'dmitryAges')),
		);
	}

	public function getFilters()
	{
		return array(
			new TwigFilter('trans', array($this, 'trans')),
		);
	}

	/**
	 * Replaces the {% trans %} tag of the abandoned twig/extensions package.
	 * The domain is the one Application::bootstrap() binds through gettext,
	 * so the msgids in app/locale stay valid.
	 */
	public function trans($message)
	{
		return gettext($message);
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
