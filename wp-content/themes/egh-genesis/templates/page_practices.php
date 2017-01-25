<?php
/**
 * EGH Genesis.
 *
 * Template Name: Practice Areas Template
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

add_action( 'genesis_meta', 'egh_practice_page_setup' );
/**
 * Set up the practice areas layout by conditionally loading sections when widgets
 * are active.
 *
 * @since 1.0.0
 */
function egh_practice_page_setup() {



}

//* Add professionals body class to the head
add_filter( 'body_class', 'egh_genesis_add_body_class' );
function egh_genesis_add_body_class( $classes ) {

	$classes[] = 'practice-areas';

	return $classes;

}

//* EGH Professionals Custom Loop
function egh_practice_custom_loop() {
 // WP_Query arguments
 $args = array (
 	'post_type'              => array( 'practice-area' ),
 );

 // The Query
 $practice_loop = new WP_Query( $args );

 // The Loop
 if ( $practice_loop->have_posts() ) {
 	while ( $practice_loop->have_posts() ) {
 		$practice_loop->the_post();
 		// do something
 	}
 } else {
 	// no posts found
 }

 // Restore original Post Data
 wp_reset_postdata();
}

//* Run the Genesis loop
genesis();
