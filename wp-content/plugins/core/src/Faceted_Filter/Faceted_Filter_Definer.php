<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter;


use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Faceted_Filter\Components\Box;
use Tribe\Project\Faceted_Filter\Components\Button;
use Tribe\Project\Faceted_Filter\Components\Checkbox;
use Tribe\Project\Faceted_Filter\Components\Popover;
use Tribe\Project\Faceted_Filter\Components\Popover_WP_Taxonomy;
use Tribe\Project\Faceted_Filter\Components\Search;
use Tribe\Project\Faceted_Filter\Contracts\Filter_Emitter;
use Tribe\Project\Faceted_Filter\Contracts\Results_Emitter;
use function Clue\StreamFilter\fun;

class Faceted_Filter_Definer implements Definer_Interface {

	const DEFAULT_FILTERS  = 'default_faceted_loop_filters';
	const DEFAULT_RESPONSE = 'default_faceted_loop_response';

	public function define(): array {
		return [
			self::DEFAULT_FILTERS  => function () {
				return new Filter_Emitter(
					( new Search() )->add( 'id', 'search-by-name' )->add( 'placeholder', 'Search here...' )->add( 'modifiers', 'search' ),
					( new Box() )->add( 'modifiers', 'vertical-separator' )->add( 'id', 'seperator-box' ),
					( new Popover_WP_Taxonomy( 'category', Checkbox::class, [
						'filter-button-checkboxes',
						'popover-checkboxes',
					], 'clear-button-checkboxes' ) )
						->add_field(
							( new Box() )->add( 'id', 'checkbox-buttons' )->add( 'modifiers', 'popover-filters' )
							             ->add_field(
								             ( new Button() )->add( 'value', 'Clear Filter ' )->add( 'modifiers', 'ghost' )->add( 'id', 'clear-button-checkboxes' )
							             )
							             ->add_field(
								             ( new Button() )->add( 'value', 'Filter ' )->add( 'id', 'filter-button-checkboxes' )
							             )
						)
				);
			},
			self::DEFAULT_RESPONSE => function () {
				return new Results_Emitter();
			},
		];
	}
}
