<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Post_List;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\post_list\Post_List_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;

class Post_List_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Post_List_Controller::LAYOUT      => $this->get(
				Post_List::LAYOUT,
				Post_List::LAYOUT_STACKED
			),
			Post_List_Controller::TITLE       => $this->get( Post_List::TITLE, '' ),
			Post_List_Controller::LEADIN      => $this->get( Post_List::LEAD_IN, '' ),
			Post_List_Controller::DESCRIPTION => $this->get( Post_List::DESCRIPTION, '' ),
			Post_List_Controller::CTA         => $this->get_cta_args(),
			Post_List_Controller::POSTS       => $this->get_posts(),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Post_List::CTA, [] ), [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT => $cta[ 'title' ],
			Link_Controller::URL     => $cta[ 'url' ],
			Link_Controller::TARGET  => $cta[ 'target' ],
		];
	}

	/**
	 * @return array
	 */
	private function get_posts(): array {
		$type = $this->get( Post_List::QUERY_TYPE, Post_List::QUERY_TYPE_AUTO );

		if ( Post_List::QUERY_TYPE_AUTO === $type ) {
			return $this->get_auto_posts();
		}

		return $this->get_manual_posts();
	}

	/**
	 * @return Post_List_Object[]
	 */
	private function get_manual_posts(): array {
		$manual_posts = $this->get( Post_List::POSTS, [] );
		$return       = [];
		/** @var \WP_Post $post */
		foreach ( $manual_posts as $post ) {
			$return[] = $this->format_post( $post );
		}

		return $return;
	}

	/**
	 * @return Post_List_Object[]
	 */
	private function get_auto_posts(): array {
		$post_types = (array) $this->get( Post_List::POST_TYPES, [] );
		$tax_query  = $this->get_tax_query_args( $post_types );
		$args       = [
			'post_types'     => $post_types,
			'tax_query'      => [
				'relation' => 'AND',
			],
			'post_status'    => 'publish',
			'posts_per_page' => $this->get( Post_List::LIMIT, 10 ),
		];
		foreach ( $tax_query as $taxonomy => $ids ) {
			$args[ 'tax_query' ][] = [
				'taxonomy' => $taxonomy,
				'field'    => 'id',
				'terms'    => array_map( 'intval', $ids ),
				'operator' => 'IN',
			];
		}

		$_posts = get_posts( $args );
		$return = [];
		foreach ( $_posts as $p ) {
			$return[] = $this->format_post( $p );
		}

		return $return;
	}

	/**
	 * @param array $post_types
	 *
	 * @return array
	 */
	private function get_tax_query_args( array $post_types ): array {
		$tax_and_terms = [];
		foreach ( $post_types as $cpt ) {
			$terms = $this->get( Post_List::TAXONOMIES . '_' . $cpt );
			foreach ( $terms as $term ) {
				if ( ! $term instanceof \WP_Term ) {
					continue;
				}
				$tax_and_terms[ $term->taxonomy ][] = $term->term_id;
			}
		}

		return $tax_and_terms;
	}

	/**
	 * @param \WP_Post $_post
	 *
	 * @return Post_List_Object
	 */
	private function format_post( \WP_Post $_post ): Post_List_Object {
		global $post;
		$post = $_post;
		setup_postdata( $post );
		$post_obj = new Post_List_Object([
			'title'     => get_the_title(),
			'content'   => get_the_content(),
			'excerpt'   => get_the_excerpt(),
			'image'     => get_post_thumbnail_id(),
			'link'      => [
				'url'    => get_permalink(),
				'target' => '',
				'label'  => get_the_title(),
			],
			'post_type' => get_post_type(),
			'post_id'   => $_post->ID,
		]);
		wp_reset_postdata();

		return $post_obj;
	}
}
