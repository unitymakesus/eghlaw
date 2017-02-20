<?php
/**
 * EGH Genesis.
 *
 * This file adds the single practice area page to the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

add_action( 'genesis_meta', 'egh_single_practice_page_setup' );
/**
 * Set up the single practice area layout by conditionally loading sections when widgets
 * are active.
 *
 * @since 1.0.0
 */
function egh_single_practice_page_setup() {

  // Force full width layout setting
  add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

  //* Add the inner sub nav
  add_action( 'genesis_after_header', 'egh_inner_subnav' );

  //* Remove Genesis loop and replace with custom single practice loop
  remove_action ('genesis_loop', 'genesis_do_loop'); // Remove the standard loop
  add_action( 'genesis_loop', 'egh_single_practice_custom_loop' ); // Add custom loop

  //* Add random custom professionals loop above footer
  add_action( 'genesis_before_footer', 'egh_practice_areas_pro_loop', 7 );

  //* Add below content posts
  add_action( 'genesis_before_footer', 'egh_below_content_posts', 8 );

}

//* Adds the inner sub navigation
function egh_inner_subnav() {
  echo '<div class="inner-scrollspy">';
  echo '<div id="navbar-affix" class="inner-sub-nav">';
  echo '<ul class="nav"><li><a href="#overview">Overview</a></li>';
  echo '</ul></div>';
}

//* Custom loop for the single practice area
function egh_single_practice_custom_loop() {
  if ( have_posts() ) { ?>
    <div id="overview" class="pro-content">
  	<?php while ( have_posts() ) {
  		the_post();
  		//
  		// Post Content here
      the_content(); //
  	} // end while ?>
    </div>
  <?php } // end if
}

function egh_practice_areas_pro_loop() {
  $pa_relationships = get_field('pa_relationships');

  if (!empty($pa_relationships)) { ?>
    <div id="attorneys" class="practice-professionals">
      <div class="wrap">
        <h3 class="section-heading">Attorneys</h3>
  			<?php

  			/*
  			*  Query posts for a relationship value.
  			*  This method uses the meta_query LIKE to match the string "123" to the database value a:1:{i:0;s:3:"123";} (serialized array)
  			*/

  			$attorneys = get_posts(array(
          'post__in'    => $pa_relationships,
  				'post_type'   => 'professional',
          'type'        => 'attorney',
          'posts_per_page' => '-1',
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
  while ( have_posts() ) : the_post();
		/*
		*  Query posts for a relationship value.
		*  This method uses the meta_query LIKE to match the string "123" to the database value a:1:{i:0;s:3:"123";} (serialized array)
		*/
    $np_relationships = get_field('np_relationships');

    if (!empty($np_relationships)) {
  		$related_posts = get_posts(array(
        'post__in'        => $np_relationships,
        'post_type'       => 'post',
      	'posts_per_page'	=> 2,
        'date_query'      => array(
           array(
             'after'      => '6 months ago'
           )
        )
  		));

  		if( $related_posts ):
        echo '<div id="recent-news" class="practice-posts">';
        echo '<div class="wrap">';
        echo '<h3 class="section-heading">Recent News</h3>';
  			foreach( $related_posts as $related_post ): ?>
          <div class="practice-post">
            <a href="<?php echo get_permalink( $related_post->ID ); ?>"><?php echo get_the_title( $related_post->ID ); ?></a>
            <p class="date"><?php echo get_the_time( 'F j, Y', $related_post->ID ); ?></p>
            <p><?php echo get_the_excerpt( $related_post->ID ); ?></p>
      		</div>
  			<?php endforeach;
        echo '</div>';
        echo '</div>';
  		endif;
    }
	endwhile; // end of the loop.

  // Restore original Post Data
  wp_reset_postdata();
}


genesis();
