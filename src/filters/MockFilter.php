<?php

namespace Hiraeth\Twig;

class MockFilter
{
	/**
	 * @param array<string, mixed> $context
	 * @param ?callable $result
	 * @param array<string, mixed> $data
	 */
	public function __invoke(array &$context, ?callable $result = NULL, array $data = []): void
	{
		if (is_callable($result)) {
			if (func_num_args() < 3) {
				$data = $result();
			}

			$context = array_merge(
				$context,
				$data
			);
		}
	}
}
