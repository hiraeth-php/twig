<?php

namespace Hiraeth\Twig;

use Hiraeth\Twig\PageHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class RelayMiddleware
{
	/**
	 *
	 */
	protected $handler = NULL;


	/**
	 *
	 */
	protected $stream = NULL;


	/**
	 *
	 */
	public function __construct(PageHandler $handler, Stream $stream)
	{
		$this->handler = $handler;
		$this->stream  = $stream;
	}

	/**
	 *
	 */
	public function __invoke(Request $request, Response $response, $next)
	{
		$path    = $request->getUri()->getPath();
		$context = ['request' => $request];

		if ($template = $this->handler->load($path)) {
			if ($this->handler->isRedirect()) {
				return $response->withStatus(301)->withHeader('Location', $path . '/');
			}

			$this->stream->write($this->handler->render($template, $context));

			return $next($request, $response->withBody($this->stream));

		} else {
			return $response->withStatus(404);
		}
	}
}
