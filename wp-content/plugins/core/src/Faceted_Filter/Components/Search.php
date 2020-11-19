<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Components;


class Search extends Component {

	const NAME = 'search-box';

	const ID = 'id';
	const PLACEHOLDER ='placeholder';

	public function required_fields(): array {
		return [
			self::ID,
			self::PLACEHOLDER,
		];
	}
}
