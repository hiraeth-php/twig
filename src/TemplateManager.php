<?php

namespace Hiraeth\Twig;

use Twig;
use Hiraeth\Templates;

/**
 *
 */
class TemplateManager implements Templates\ManagerInterface
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
	public function has(string $path): bool
	{
		return $this->environment->getLoader()->exists($path);
	}


	/**
	 *
	 */
	public function load(string $path, array $data = []): Templates\TemplateInterface
	{
		return new Template($this->environment->load($path), $data);
	}
}
