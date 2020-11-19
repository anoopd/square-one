<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Components;


trait With_Label {

	public $label = [];

	public function add_label( $type, $as, $value ) {
		$label         = new \stdClass();
		$label->type   = $type;
		$label->as     = $as;
		$label->value  = $value;
		$this->label[] = $label;

		return $this;
	}

}
