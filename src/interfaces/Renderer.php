<?php

namespace Hiraeth\Twig;


interface Renderer
{
	public function render(string $content, string $extension): string;

	public function setRenderManager(Manager $environment): self;
}
