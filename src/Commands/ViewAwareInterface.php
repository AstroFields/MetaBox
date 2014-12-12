<?php

namespace WCM\AstroFields\MetaBox\Commands;

use WCM\AstroFields\Core\Templates;

interface ViewAwareInterface
{
	public function setProvider( \SplPriorityQueue $receiver );

	public function setTemplate( Templates\TemplateInterface $template );
}