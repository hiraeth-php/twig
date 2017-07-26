<?php

namespace Hiraeth\Twig;

use Hiraeth\Application;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class RequestResolver
{
	/**
	 *
	 */
	protected $app = NULL;


	/**
	 *
	 */
	protected $handler = NULL;


	/**
	 *
	 */
	protected $request = NULL;


	/**
	 *
	 */
	protected $response = NULL;


	/**
	 *
	 */
	public function __construct(PageHandler $handler, Application $app)
	{
		$this->handler = $handler;
		$this->app     = $app;
	}


	/**
	 *
	 */
	public function __invoke(Request $request = NULL, Response $response = NULL)
	{
		$this->request  = $request;
		$this->response = $response;

		try {
			if (!$this->request) {
				$path = $_SERVER['REQUEST_URI'];

			} else {
				if (isset($this->request->getAttributes()['file'])) {
					$path = $this->request->getAttributes()['file'];
				} else {
					$path = $this->request->getUri()->getPath();
				}
			}

			if ($template = $this->handler->load($path)) {
				if ($this->handler->isRedirect()) {
					return $this->redirect($path);
				}

				if (!$this->response) {
					header('HTTP/1.1 200 Found');
					echo $this->handler->render($template, [
						'request'  => $this->request,
						'response' => $this->response
					]);

					return 0;

				} else {
					$this->response->getBody()->write($this->handler->render($template, [
						'request'  => $this->request,
						'response' => $this->response
					]));

					return $this->response->withStatus(200);
				}

			} else {
				return $this->notfound();
			}

		} catch (Exception $e) {
			if ($this->app->getEnvironment('DEBUG')) {
				throw $e;
			}

			return $this->error();
		}
	}


	/**
	 *
	 */
	protected function error()
	{
		$body = 'Request cannot be completed at this time, please try again later.';

		if (!$this->response) {
			header('HTTP/1.1 500 Internal Server Error');
			echo $body;

			return 5;

		} else {
			$this->response->getBody()->write($body);

			return $this->response->withStatus(500);
		}
	}


	/**
	 *
	 */
	protected function notfound()
	{
		$body = 'Requested page could not be found';

		if (!$this->response) {
			header('HTTP/1.1 404 Not Found');
			echo $body;
			return 4;

		} else {
			$this->response->getBody()->write($body);

			return $this->response->withStatus(500);
		}
	}


	/**
	 *
	 */
	protected function redirect($path)
	{
		if (!$this->response) {
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $path . '/');
			return 3;

		} else {
			return $this->response->withStatus(301)->withHeader('Location', $path . '/');
		}
	}
}
