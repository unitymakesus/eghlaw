<?php
/**
 * EGH Genesis.
 *
 * Template Name: Professionals Template
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

	$classes[] = 'professionals';

	return $classes;

}

//* EGH Professionals Custom Loop
function egh_pro_custom_loop() {
  // WP_Query arguments
  $args = array (
  	'post_type'      => array( 'professional' ),
    'posts_per_page' => '20',
    'order'          => 'ASC',
    'orderby'        => 'date',
  );

  // The Query
  $pros_loop = new WP_Query( $args );

  // The Loop
  if ( $pros_loop->have_posts() ) {
  	while ( $pros_loop->have_posts() ) {

      $proscount++;
      $pros_class = ( ($proscount % 3) == 1 ) ? "first one-third" : "one-third";

  		$pros_loop->the_post();

      $protitle = get_field('pro_title');

      ?>

      <div <?php post_class($pros_class); ?>>
        <a href="<?php the_permalink(); ?>" class="pro">
          <div class="pro-image">
            <?php
              if ( has_post_thumbnail() ) {
                the_post_thumbnail();
              } else { ?>
                <img src="http://www.eghlaw.dev/wp-content/uploads/2016/11/placeholder.jpg" alt="Professional" />
              <?php }
            ?>
          </div>
          <div class="pro-info">
            <h3><?php the_title(); ?></h3>
            <h4><?php echo $protitle; ?></h4>
          </a>
        </div>
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
