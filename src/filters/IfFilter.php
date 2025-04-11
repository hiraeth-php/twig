<?php

namespace Hiraeth\Twig;

/**
 * A filter that outputs a string only if a condition is met
 *
 * Example usage: class="{{ 'bg-amber-700'|if(true) }}"
 */
class IfFilter
{
	/**
	 *
	 *
	 * @param mixed $data The string to output
	 * @param bool $requirement The condition for returning the string
	 */
	function __invoke(mixed $data, bool $requirement): mixed
	{
		return $requirement
			? $data
			: null;
	}
}
