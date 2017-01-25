<?php
/**
 * EGH Genesis.
 *
 * This file adds the theme widgets to the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

//* Register below content widget area
genesis_register_sidebar( array(
	'id' => 'below-content',
	'name' => __( 'Below Content Widget', 'genesis' ),
	'description' => __( 'Below Content Widget Area', 'egh-genesis' ),
) );
//* Register additional homepage widget area
genesis_register_sidebar( array(
	'id' => 'home-additional',
	'name' => __( 'Additional Homepage Widget', 'genesis' ),
	'description' => __( 'Additional Homepage Widget Area', 'egh-genesis' ),
) );

//* Add below content widget above the footer
add_action( 'genesis_before_footer', 'below_content_widget_area', 10 );
function below_content_widget_area() {

	genesis_widget_area( 'below-content', array(
		'before' => '<div class="below-content"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}
