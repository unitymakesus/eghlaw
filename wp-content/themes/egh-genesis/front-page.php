<?php
/**
 * EGH Genesis.
 *
 * This file adds the front page template to the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

add_action( 'genesis_meta', 'egh_home_page_setup' );
/**
 * Set up the single practice area layout by conditionally loading sections when widgets
 * are active.
 *
 * @since 1.0.0
 */
function egh_home_page_setup() {

  // Force full width layout setting
  add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

  // Hide page title
  remove_action( 'genesis_entry_header', 'genesis_do_post_title');

  // Add the callout section markup
  add_action( 'genesis_before_footer', 'egh_callout_markup_start', 5 );
  // Show attorney spotlight
  add_action( 'genesis_before_footer', 'egh_attorney_spotlight', 6 );
  // Shot home additional widget
  add_action( 'genesis_before_footer', 'egh_home_additional_widget_area', 7 );
  // Add the callout section markup
  add_action( 'genesis_before_footer', 'egh_callout_markup_end', 8 );

}

//* EGH callout markup start
function egh_callout_markup_start() {
	echo '<div class="special-callout">';
}

//* Attorney spotlight loop for the front page
function egh_attorney_spotlight() {
  echo '<div id="attorneys" class="one-half first">';

  // WP_Query arguments
	$args = array(
    'post_type'              => array( 'professional' ),
  	'type'                   => 'attorney',
  	'posts_per_page'         => '1',
  	'orderby'                => 'rand',
    'meta_query'             => array(
                                  array(
                                    'key' => 'spotlight_text',
                                    'value' => '',
                                    'compare' => '!='
                                  )
                                )
	);

	// The Query
	$attorney_spotlight = new WP_Query( $args );

	// The Loop
	if ( $attorney_spotlight->have_posts() ) {
		while ( $attorney_spotlight->have_posts() ) {
			$attorney_spotlight->the_post();

      $spotlight_img  = get_field( 'spotlight_image' );
      $attorney_intro = get_field( 'spotlight_text' );

      ?>
			<div class="spotlight" style="background-image: url('<?php echo $spotlight_img;  ?>'); background-size: cover;">
        <div class="spotlight-content">
          <h4>Attorney Spotlight</h4>
          <p><?php echo $attorney_intro; ?></p>
        </div>
			</div>
		<?php }
	}

	// Restore original Post Data
	wp_reset_postdata();

  echo '</div>';
}

//* Add the additional home widget area
function egh_home_additional_widget_area() {

  echo '<div id="recent-news" class="one-half">';

  genesis_widget_area( 'home-additional', array(
		'before' => '<div class="home-additional widget-area">',
		'after'  => '</div>',
  ) );

  echo '</div>';
}

//* EGH callout markup end
function egh_callout_markup_end() {
  echo '</div>';
  echo '<div class="clear"></div>';
}

genesis();
