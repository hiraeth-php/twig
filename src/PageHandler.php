<?php

namespace Hiraeth\Twig;

use Twig;

/**
 *
 */
class PageHandler
{
	/**
	 *
	 */
	protected $redirect = NULL;


	/**
	 *
	 */
	protected $template = NULL;


	/**
	 *
	 */
	protected $twig = NULL;


	/**
	 *
	 */
	public function __construct(Twig\Environment $twig)
	{
		$this->twig = $twig;
	}


	/**
	 *
	 */
	public function isRedirect()
	{
		return $this->redirect;
	}


	/**
	 *
	 */
	public function load($path)
	{
		$templates = array();

		if (substr($path, -1) == '/') {
			$templates[0] = '@pages' . $path . 'index.html';
		} else {
			$templates[1] = '@pages' . $path . '/index.html';
			$templates[0] = '@pages' . $path . '.html';
		}

		foreach ($templates as $redirect => $template) {
			try {
				$this->redirect = $redirect;

				return $this->twig->loadTemplate($template);

			} catch (Twig\Error\LoaderError $e) {

			}
		}

		return NULL;
	}


	/**
	 *
	 */
	public function render(Twig\Template $template, $context = array())
	{
		return $template->render($context);
	}
}
