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
	 * @param array<string, mixed> $context
	 * @param class-string $class
	 */
	public function __invoke(array &$context, string $class): void
	{
		$action  = $this->app->get(str_replace(':', '\\', $class));

		if ($context['route']) {
			$result = $action->call($context['request'], $context['route']->getParameters());
		} else {
			$result = $action->call($context['request']);
		}

		$context = array_merge($context, $result);
	}
}
