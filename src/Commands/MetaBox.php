<?php

namespace WCM\AstroFields\MetaBox\Commands;

use WCM\AstroFields\Core\Commands;
use WCM\AstroFields\Core\Mediators;
use WCM\AstroFields\Core\Providers;
use WCM\AstroFields\Core\Templates;

class MetaBox extends Commands\AbstractCollectorCommand implements
	Commands\ContextAwareInterface,
	Commands\ViewAwareInterface
{
	/** @type string */
	private $context = 'add_meta_boxes_{type}';

	/** @type string */
	private $label = 'The Label';

	/** @type string */
	private $mb_context;

	/** @type string */
	private $priority;

	/** @type \WCM\AstroFields\MetaBox\Providers\MetaBoxDataProviderInterface|Providers\DataProviderInterface */
	private $provider;

	/** @type Templates\TemplateInterface */
	private $template;

	/**
	 * @param string $label
	 * @param string $context
	 * @param string $priority
	 */
	public function __construct(
		$label,
		$context = 'advanced',
		$priority = 'default'
		)
	{
		$this->label      = $label;
		$this->mb_context = $context;
		$this->priority   = $priority;
	}

	/**
	 * Attach the template and add the meta box
	 * The `$callback` is the `display()` method from
	 * a Template inheriting `Core\Templates\TemplateInterface`.
	 * @param \WP_Post         $post
	 * @param Mediators\Entity $entity
	 * @param Array            $data
	 * @return mixed | void
	 */
	public function update(
		\WP_Post $post = null,
		Mediators\Entity $entity = null,
		Array $data = array()
		)
	{
		$this->provider->setData(
			$data
			+ array( 'entities' => $this, )
		);
		$this->template->attach( $this->provider );

		foreach ( $data['types'] as $type )
		{
			add_meta_box(
				$data['key'],
				$this->label,
				array( $this->template, 'display' ),
				$type,
				$this->mb_context,
				$this->priority
			);
		}
	}

	/**
	 * Attach an Entity
	 * Hides the MetaBox from the "Custom Fields" MetaBox,
	 * but avoids to add a(nother) sanitize Callback
	 * @param Mediators\EntityInterface | Mediators\Entity $entity
	 * @param int                                          $priority
	 * @return $this|void
	 */
	public function attach( Mediators\EntityInterface $entity, $priority )
	{
		register_meta( 'post', $entity->getKey(), null, '__return_false' );

		$this->insert( $entity, $priority );

		return $this;
	}

	public function setContext( $context )
	{
		$this->context = $context;

		return $this;
	}

	public function getContext()
	{
		return $this->context;
	}

	public function setProvider( Providers\DataProviderInterface $provider )
	{
		if ( $this->template )
			$this->template->attach( $provider );
		$this->provider = $provider;

		return $this;
	}

	public function setTemplate( Templates\TemplateInterface $template )
	{
		if ( $this->provider )
			$template->attach( $this->provider );
		$this->template = $template;

		return $template;
	}
}