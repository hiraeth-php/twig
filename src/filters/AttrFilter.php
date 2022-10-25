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
	 *
	 * @param ?string $data The data/value of the HTML attribute, if NULL, it won't be printed
	 * @param string $name
	 */
	function __invoke(?string $data, string $name): string
	{
		return $data !== NULL
			? sprintf('%s="%s"', $name, $data)
			: '';
	}
}
