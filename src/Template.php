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
	public function __construct(Twig\TemplateWrapper $template, array $data = array())
	{
		$this->template = $template;
		$this->data     = $data;
	}


	/**
	 *
	 */
	public function getExtension(): string
	{
		return explode('.', basename($this->template->getTemplateName()), 2)[1] ?? '';
	}


	/**
	 *
	 */
	public function render(): string
	{
		return $this->template->render($this->data);
	}
}