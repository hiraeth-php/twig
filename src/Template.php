<?php

namespace Hiraeth\Twig;

use Twig;
use Hiraeth\Templates;

/**
 *
 */
class Template extends Templates\AbstractTemplate
{
	/**
	 *
	 */
	protected $template = NULL;


	/**
	 *
	 */
	public function __construct(Twig\TemplateWrapper $template)
	{
		$this->template = $template;
	}


	/**
	 *
	 */
	public function render(): string
	{
		return $this->template->render($this->data);
	}
}
