<?php

namespace Hiraeth\Twig;

use Twig;
use Hiraeth\Templates;

/**
 *
 */
class Manager implements Templates\Manager
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
	public function load(string $path, array $data = []): Templates\Template
	{
		return new Template($this->environment->load($path), $data);
	}
}
