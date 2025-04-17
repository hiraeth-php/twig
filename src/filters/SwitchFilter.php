<?php

namespace Hiraeth\Twig;

/**
 * A switch filter for handling printable boolean-like types
 */
class SwitchFilter
{
	/**
	 * Convert data into an integer representation of a boolean-like type
	 *
	 * @param mixed $data The data/value to convert
	 */
	function __invoke(mixed $data): int
	{
		return intval(filter_var($data, FILTER_VALIDATE_BOOLEAN));
	}
}
