<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Components;


class Checkbox extends Component {

	const NAME = 'checkbox';

	public function required_fields(): array {
		return [];
	}
}
