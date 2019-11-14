<?php

namespace Hiraeth\Twig;

/**
 * A filter that creates an HTML attribute with a given name from non-null data
 *
 * Example usage: {{ (pageClass ?? null)|attr('class') }}
 */
class AttrFilter
{
	/**
	 * Convert data and name to attribute string
	 */
	function __invoke($data, $name): string
	{
		return $data !== NULL
			? sprintf('%s="%s"', $name, $data)
			: '';
	}
}
