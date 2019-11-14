<?php

namespace Hiraeth\Twig;

use Twig;
use Hiraeth\Templates;

/**
 * An abstracted Twig template which adheres to Hiraeth\Templates\Template
 */
class Template extends Templates\AbstractTemplate
{
	/**
	 * The twig template instance
	 *
	 * @var Twig\TemplateWrapper
	 */
	protected $template = NULL;


	/**
	 * Create a new instance
	 */
	public function __construct(Twig\TemplateWrapper $template, array $data = array())
	{
		$this->template = $template;
		$this->data     = $data;
	}


	/**
	 * {@inheritDoc}
	 */
	public function getExtension(): string
	{
		return explode('.', basename($this->template->getTemplateName()), 2)[1] ?? '';
	}


	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		return $this->template->render($this->data);
	}
}
