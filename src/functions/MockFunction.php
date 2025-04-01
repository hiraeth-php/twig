<?php

namespace Hiraeth\Twig;

use ArrayObject;
use Twig\Environment;
use InvalidArgumentException;

class MockFunction
{
	/**
	 * @param array<string, ArrayObject>
	 */
	static $data = array();

	/**
	 * @param array<string>
	 */
	static $building = [];


	/**
	 * @param array<string, mixed> $context
	 * @param ?callable $result
	 */
	public function __invoke(Environment $twig, string|array $data): ArrayObject
	{
		if (is_string($data)) {
			$ref = $data;

			if (!isset(static::$data[$ref]) && !in_array($data, static::$building)) {
				static::$building[]  = $ref;
				static::$data[$data] = new ArrayObject();

				$twig->load($ref)->render();

				if (!count(static::$data[$ref])) {
					throw new InvalidArgumentException(sprintf(
						'Referenced mock file "%s" does did not define a mock',
						$ref
					));
				}
			}

		} else {
			$ref = array_pop(static::$building);

			static::$data[$ref]->exchangeArray($data);
		}

		return static::$data[$ref];
	}
}
