<?php

namespace WCM\AstroFields\MetaBox\Templates;

use WCM\AstroFields\Core;

class Table implements Core\Templates\TemplateInterface
{
	/** @type \SplPriorityQueue */
	private $entities;

	/**
	 * Attach the entities
	 * @param \WCM\AstroFields\MetaBox\Providers\MetaBoxDataProviderInterface $provider
	 * @return $this
	 */
	public function attach( $provider )
	{
		$this->entities = $provider->getEntities();

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->display();
	}

	/**
	 * Render the Entities
	 * @return string
	 */
	public function display()
	{
		$entities = $this->entities;
		?>
		<table class="wp-list-table  widefat">
			<tbody>
			<?php
			for ( $entities->rewind(); $entities->valid(); $entities->next() )
			{
				$class = 0 === $entities->key() %2 ? ' class="alternate"' : '';
				?>
				<tr<?php echo $class; ?>>
					<td>Foo</td>
					<td>
						<?php
						foreach ( $entities->current() as $en )
							var_dump( $entities->current()->current() );
						/*$entities
							->current()
							->notify( $entities->current(), array() );*/
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	}
}