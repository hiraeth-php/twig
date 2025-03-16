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
	 * @var string
	 */
	protected $block;

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
	public function __construct(Twig\Environment $env, Twig\TemplateWrapper $template, array $data = [])
	{
		$this->template = $template;
		$this->data     = $data;
		$this->env      = $env;
	}


	/**
	 * Render a block
	 */
	public function block(string $name, array $data = []): static
	{
		$this->block = $name;
		$this->data  = array_merge_recursive($this->data, $data);

		return $this;
	}


	/**
	 * {@inheritDoc}
	 */
	public function getExtension(): string
	{
		return explode('.', basename((string) $this->template->getTemplateName()), 2)[1] ?? '';
	}


	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		if ($this->block) {
			$content = $this->template->renderBlock($this->block, $this->data);
		} else {
			$content = $this->template->render($this->data);
		}

		foreach ($this->env->getExtensions() as $extension) {
			if ($extension instanceof Renderer) {
				$content = $extension->render($content, $this->getExtension());
			}
		}

		return $content;
	}
}
