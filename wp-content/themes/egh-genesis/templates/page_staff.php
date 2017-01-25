<?php
/**
 * EGH Genesis.
 *
 * Template Name: Staff Listing
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

add_action( 'genesis_meta', 'egh_pros_page_setup' );
/**
 * Set up the professionals layout by conditionally loading sections when widgets
 * are active.
 *
 * @since 1.0.0
 */
function egh_pros_page_setup() {

  remove_action ('genesis_loop', 'genesis_do_loop'); // Remove the standard loop
  add_action( 'genesis_loop', 'egh_pro_custom_loop' ); // Add custom loop

}

//* Add professionals body class to the head
add_filter( 'body_class', 'egh_genesis_add_pro_class' );
function egh_genesis_add_pro_class( $classes ) {

	$classes[] = 'staff';

	return $classes;

}

//* EGH Professionals Custom Loop
function egh_pro_custom_loop() {
  // WP_Query arguments
  $args = array (
  	'post_type'      => 'professional',
    'type'           => 'staff',
    'posts_per_page' => '-1',
    'order'          => 'ASC',
    'orderby'        => 'menu_order',
  );

  // The Query
  $pros_loop = new WP_Query( $args );

  // The Loop
  if ( $pros_loop->have_posts() ) {
  	while ( $pros_loop->have_posts() ) {

      $proscount++;
      $pros_class = ( ($proscount % 2) == 1 ) ? "first one-half" : "one-half";

  		$pros_loop->the_post();

      $protitle = get_field('pro_title');
      $pro_phone = get_field( 'pro_phone' );
      $pro_email = get_field( 'pro_email' );
      $pro_vcard = get_field( 'vcard' );

      ?>

      <div <?php post_class([$pros_class, 'pro']); ?>>
        <h3><?php the_title(); ?></h3>
        <h4><?php echo $protitle; ?></h4>
        <p><i class="fa fa-phone" aria-hidden="true"></i><?php echo $pro_phone; ?><br />
        <i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:<?php echo eae_encode_str( $pro_email ); ?>" target="_blank"><?php echo eae_encode_str( $pro_email ); ?></a><br />
        <i class="fa fa-address-card-o" aria-hidden="true"></i><a href="<?php echo $pro_vcard['url']; ?>" target="_blank">Download vCard</a></p>
      </div>

  	<?php }
  } else {
  	// no posts found
  }

  // Restore original Post Data
  wp_reset_postdata();
}

//* Run the Genesis loop
genesis();
