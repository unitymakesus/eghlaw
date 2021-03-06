<?php
/**
 * EGH Genesis.
 *
 * This file adds the single post template to the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

add_action( 'genesis_meta', 'egh_single_post_setup' );
/**
 * Set up the single practice area layout by conditionally loading sections when widgets
 * are active.
 *
 * @since 1.0.0
 */
function egh_single_post_setup() {

  // Force full width layout setting
  // add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

  //* Add the inner sub nav
  // add_action( 'genesis_after_header', 'egh_inner_subnav' );

  //* Remove Genesis loop and replace with custom single practice loop
  // remove_action ('genesis_loop', 'genesis_do_loop'); // Remove the standard loop
  // add_action( 'genesis_loop', 'egh_single_practice_custom_loop' ); // Add custom loop

  //* Add professionals loop above footer
  add_action( 'genesis_before_footer', 'egh_news_pro_loop', 7 );

  //* Add below content posts
  add_action( 'genesis_entry_footer', 'egh_below_content_posts', 1 );
}

function egh_news_pro_loop() {
  $na_relationships = get_field('na_relationships');

  if (!empty($na_relationships)) { ?>
    <div id="attorneys" class="practice-professionals">
      <div class="wrap">
        <h3 class="section-heading">Attorneys</h3>
  			<?php

  			/*
  			*  Query posts for a relationship value.
  			*  This method uses the meta_query LIKE to match the string "123" to the database value a:1:{i:0;s:3:"123";} (serialized array)
  			*/
    			$attorneys = get_posts(array(
            'post__in'    => $na_relationships,
    				'post_type'   => 'professional',
            'type'        => 'attorney',
            'posts_per_page' => -1,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
    			));

    			?>
    			<?php if( $attorneys ): ?>
    				<ul class="attorney-list flexcontainer">
    				<?php foreach( $attorneys as $attorney ): ?>
    					<?php

    					$thumbnail = get_the_post_thumbnail( $attorney->ID );
              $attorney_title = get_field( 'pro_title', $attorney->ID );

    					?>
    					<li class="flex">
    						<a href="<?php echo get_permalink( $attorney->ID ); ?>">
                  <div class="pro-image">
    							  <?php echo $thumbnail; ?>
                  </div>
                  <div class="pro-info">
                    <h3><?php echo get_the_title( $attorney->ID ); ?></h3>
                    <h4><?php echo $attorney_title; ?></h4>
                  </div>
    						</a>
    					</li>
    				<?php endforeach; ?>
    				</ul>
    			<?php endif;
          wp_reset_postdata(); ?>
  		</div>
  	</div>
	<?php }
}

//* Add the below content posts
function egh_below_content_posts() {
  global $post;

  if ($practice_areas = get_field('np_relationships', $post->ID)) {
    echo '<div class="entry-footer">';
    foreach ($practice_areas as &$a) {
      $a = '<a href="' . get_permalink($a) .'">' . get_the_title($a) . '</a>';
    }
		echo implode(', ', $practice_areas);
    echo '</div>';
  }
}


genesis();
