<?php

namespace Hiraeth\Twig;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class RelayMiddleware
{
	/**
	 *
	 */
	protected $resolver = NULL;


	/**
	 *
	 */
	protected $stream = NULL;


	/**
	 *
	 */
	public function __construct(RequestResolver $resolver, Stream $stream)
	{
		$this->resolver = $resolver;
		$this->stream   = $stream;
	}

	/**
	 *
	 */
	public function __invoke(Request $request, Response $response, $next)
	{
		return $next($request, $this->resolver->__invoke($request, $response->withBody($this->stream)));
	}
}
