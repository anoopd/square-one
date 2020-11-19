<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Components;


abstract class Component {
	use With_Label, With_Fields;

	const NAME = '';
	const MODIFIERS = 'modifiers';

	public function __construct() {
		$this->type = static::NAME;
	}

	abstract public function required_fields(): array;

	public function add( $param, $value ) {
		if ( self::MODIFIERS === $param && ! is_array( $value ) ) {
			$this->modifiers[] = $value;
		} else {
			$this->$param = $value;
		}

		return $this;
	}
}
