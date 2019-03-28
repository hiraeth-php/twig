<?php

namespace Hiraeth\Twig;

use Twig;
use Hiraeth\Templates;

/**
 *
 */
class Manager implements Templates\ManagerInterface
{
	/**
	 *
	 */
	protected $environment = NULL;


	/**
	 *
	 */
	public function __construct(Twig\Environment $environment)
	{
		$this->environment = $environment;
	}


	/**
	 *
	 */
	public function load(string $path, $data = array()): Templates\TemplateInterface
	{
		return (new Template($this->environment->load($path)))->setAll($data);
	}
}
