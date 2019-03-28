<?php

namespace Hiraeth\Twig;

use Twig;
use Hiraeth\Templates;

/**
 *
 */
class TemplateManager implements Templates\TemplateManagerInterface
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
	public function load(string $path, array $data = []): Templates\TemplateInterface
	{
		return new Template($this->environment->load($path, $data));
	}
}
