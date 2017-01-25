<?php
/**
 * EGH Genesis.
 *
 * This file is the page template for pages under the "Our Firm" section in the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

//* Template Name: Our Firm Section Template

//* Add landing page body class to the head
add_filter( 'body_class', 'egh_genesis_add_body_class' );
function egh_genesis_add_body_class( $classes ) {

	$classes[] = 'our-firm-template';

	return $classes;

}

//* Show about sidebar
remove_action('genesis_after_content', 'genesis_get_sidebar');
add_action( 'genesis_after_content', 'egh_get_about_sidebar' );

function egh_get_about_sidebar() {
  echo '<aside class="sidebar sidebar-primary widget-area" role="complementary" aria-label="Primary Sidebar" itemscope="" itemtype="http://schema.org/WPSideBar" id="sidebar-affix">';
	genesis_do_sidebar_alt();
	echo '</aside>';
}

//* Run the Genesis loop
genesis();
