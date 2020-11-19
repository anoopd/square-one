<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Components;


trait With_Fields {

	public $fields = [];

	public function add_field( $component ) {
		$this->fields[] = $component;

		return $this;
	}
}
