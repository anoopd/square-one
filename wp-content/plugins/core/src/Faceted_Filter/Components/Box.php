<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Components;


class Box extends Component {

	const NAME = 'box';

	const ID = 'id';

	public function required_fields(): array {
		return [
			self::ID,
		];
	}
}
