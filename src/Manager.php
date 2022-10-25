<?php

namespace Hiraeth\Twig;

use Twig;
use Hiraeth\Templates;

/**
 * A proxy class which represents a standard twig environment as a Hirath template manager
 */
class Manager implements Templates\Manager
{
	/**
	 * @var Twig\Environment|null
	 */
	protected $environment = NULL;


	/**
	 * Create a new manager
	 *
	 * @param Twig\Environment $environment The twig environment to wrap
	 */
	public function __construct(Twig\Environment $environment)
	{
		$this->environment = $environment;
	}


	/**
	 * Determine if a template exists at a given path
	 *
	 * @param string $path The path to the template
	 * @return bool Whether or not the template exists
	 */
	public function has(string $path): bool
	{
		return $this->environment->getLoader()->exists($path);
	}


	/**
	 * Get a template object for a given path and data
	 *
	 * @param string $path The path to the template
	 * @param mixed[] $data That data with which to render the template
	 */
	public function load(string $path, array $data = []): Templates\Template
	{
		return new Template($this->environment->load($path), $data);
	}
}
