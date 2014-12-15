<?php

namespace Astro;

use WCM\AstroFields\Core;
use WCM\AstroFields\MetaBox\Commands;
use WCM\AstroFields\MetaBox\Providers;
use WCM\AstroFields\MetaBox\Templates;

class MetaBox extends AbstractFacade
{
	/** @var Core\Mediators\Entity */
	static private $entity;

	/** @var Commands\MetaBox */
	static private $box;

	public static function get( $name, Array $types = array() )
	{
		$box = Container::instance()->seek( $name );
		var_dump( $box );
	}

	public static function __callStatic( $method, $args )
	{
		$container = Container::instance();
		var_dump( $method, $args );
	}

	public static function build( $label, Array $types )
	{
		if ( is_null( self::$box ) )
		{
			$box = new Commands\MetaBox( $label );

			self::$entity = new Core\Mediators\Entity(
				strtolower( str_replace( ' ', '-', $label ) ),
				$types
			);
			self::$entity->attach( $box );
			$box->setProvider( new Providers\MetaBoxDataProvider )
				->setTemplate( new Templates\Table );

			self::$box = $box;
		}

		return self::$box;
	}

	public function attach( Core\Mediators\EntityInterface $entity, $priority )
	{
		self::$box->attach( $entity, $priority );
		return $this;
	}

	public function provider( Core\Providers\DataProviderInterface $provider )
	{
		self::$box->setProvider( $provider );
		return $this;
	}

	public function template( Core\Templates\TemplateInterface $template )
	{
		self::$box->setTemplate( $template );
		return $this;
	}
}
