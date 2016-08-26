<?php

namespace Tribe\Project\Theme;

class Image_Wrap {

	public function hook() {

		add_filter( 'the_content', [ $this, 'customize_wp_image_output' ], 12, 1 );
	}

	/**
	 * Customize WP image output
	 *
	 * @param $html
	 *
	 * @return mixed
	 */
	public function customize_wp_image_output( $html ) {

		return preg_replace_callback( '/<p>((?:.(?!p>))*?)(<a[^>]*>)?\s*(<img[^>]+>)(<\/a>)?(.*?)<\/p>/is', function ( $matches ) {

			/*
			Groups 	Regex 			 Description
				    <p>			     starting <p> tag
			1	    ((?:.(?!p>))*?)	 match 0 or more of anything not followed by p>
				    .(?!p>) 		 anything that's not followed by p>
				    ?: 			     non-capturing group.
					*?		         match the ". modified by p> condition" expression non-greedily
			2	    (<a[^>]*>)?		 starting <a> tag (optional)
				    \s*			     white space (optional)
			3	    (<img[^>]+>)	 <img> tag
				    \s*			     white space (optional)
			4	    (<\/a>)? 		 ending </a> tag (optional)
			5	    (.*?)<\/p>		 everything up to the final </p>
				    i modifier 		 case insensitive
				    s modifier		 allows . to match multiple lines (important for 1st and 5th group)
			*/

			// image and (optional) link: <a ...><img ...></a>
			$image = $matches[2] . $matches[3] . $matches[4];
			// content before and after image. wrap in <p> unless it's empty
			$content = trim( $matches[1] . $matches[5] );
			if ( $content ) {
				$content = '<p>' . $content . '</p>';
			}

			return sprintf( '<figure class="wp-image-wrap">%s</figure>%s', $image, $content );
		}, $html );

	}

}
