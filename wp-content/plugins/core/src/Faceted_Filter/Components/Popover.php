<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Components;


class Popover extends Component {

	const NAME = 'popover';

	const ID = 'id';
	const ARIA_LABEL = 'ariaLabel';
	const CLOSED_BY  = 'closedBy';

	public function required_fields(): array {
		return [
			self::ID,
			self::ARIA_LABEL,
		];
	}
}
