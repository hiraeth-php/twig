<?php

namespace Hiraeth\Twig;

use Hiraeth\Application;

/**
 *
 */
class ActionFunction
{
	/**
	 * @var Application
	 */
	protected $app;


	/**
	 *
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}


	/**
	 *
	 */
	public function __invoke(array &$context, string $class): void
	{
		$action  = $this->app->get(str_replace(':', '\\', $class));
		$result  = $action->call($context['request'], $context['route']->getParameters());
		$context = array_merge($context, $result);
	}
}
