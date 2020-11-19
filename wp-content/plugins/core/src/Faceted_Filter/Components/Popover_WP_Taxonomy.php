<?php
declare( strict_types=1 );

namespace Tribe\Project\Faceted_Filter\Components;


class Popover_WP_Taxonomy extends Popover {

	const NAME = 'popover';

	protected $taxonomy_slug;
	protected $field_type;
	protected $submittedBy;
	protected $clearedBy;

	public function __construct( $taxonomy_slug, $field_type = Checkbox::class, $submittedBy = [], $clearedBy = '' ) {
		parent::__construct();

		$this->taxonomy_slug = $taxonomy_slug;
		$this->field_type    = $field_type;
		$this->submittedBy   = $submittedBy;
		$this->clearedBy     = $clearedBy;

		$terms = get_terms( [
			'taxonomy' => $taxonomy_slug,
		] );

		$this->setup();

		// Add fields
		foreach ( $terms as $term ) {
			$this->add_term( $term );
		}
	}

	private function setup() {
		$taxonomy = get_taxonomy( $this->taxonomy_slug );
		$this->id = sprintf( 'popover-%s', $this->taxonomy_slug );
		$this->add_label( 'text', 'nothing', $taxonomy->label );
	}

	private function add_term( $term ) {
		$this->add_field(
			( new $this->field_type )
				->add_label( 'text', 'nothing', $term->name )
				->add( 'id', $term->slug )
				->add( 'submittedBy', $this->submittedBy )
				->add( 'clearedBy', $this->clearedBy )
		);
	}

}
