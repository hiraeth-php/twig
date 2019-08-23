<?php

namespace Hiraeth\Twig;

/**
 *
 */
class AttrFilter
{
	/**
	 *
	 */
	function __invoke($data, $name)
	{
		return $data !== NULL
			? sprintf('%s="%s"', $name, $data)
			: '';
	}
}
