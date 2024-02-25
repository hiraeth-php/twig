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
	 * @var Twig\Environment
	 */
	protected $env;

	/**
	 * The twig template instance
	 *
	 * @var Twig\TemplateWrapper|null
	 */
	protected $template = NULL;


	/**
	 * Create a new instance
	 *
	 * @param Twig\TemplateWrapper $template
	 * @param mixed[] $data
	 */
	public function __construct(Twig\Environment $env, Twig\TemplateWrapper $template, array $data = array())
	{
		$this->template = $template;
		$this->data     = $data;
		$this->env      = $env;
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
		$content = $this->template->render($this->data);

		foreach ($this->env->getExtensions() as $extension) {
			if ($extension instanceof Renderer) {
				$content = $extension->render($content, $this->getExtension());
			}
		}

		return $content;
	}
}
