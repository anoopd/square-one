<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter;


use Tribe\Libs\Container\Abstract_Subscriber;

class Faceted_Filter_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_action( 'wp_footer', function () {
			$this->container->get( Faceted_Filter_Definer::DEFAULT_FILTERS )->display_filters('filters');
		} );
	}
}
