<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Contracts;


use Tribe\Project\Faceted_Filter\Components\Component;

class Filter_Emitter {

	/**
	 * @var Component[]
	 */
	public $filters;

	public function __construct( Component ...$components ) {
		$this->filters = $components;
	}

	public function display_filters( $id ) {
		echo sprintf(
			'<script id="%s" type="application/json">%s</script>',
			$id,
			$this->get_filters()
		);
	}

	public function get_filters() {
		return json_encode( $this );
	}
}
