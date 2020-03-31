<?php
/**
 * EGH Genesis.
 *
 * This file adds the single professional page to the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

add_action( 'genesis_meta', 'egh_single_pro_page_setup' );
/**
 * Set up the single practice area layout by conditionally loading sections when widgets
 * are active.
 *
 * @since 1.0.0
 */
function egh_single_pro_page_setup() {

 // Force full width layout setting
 add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

 //* Add the entry header markup and entry title before the content on all pages except the front page
 remove_action( 'genesis_after_header', 'egh_genesis_add_entry_header' );
 add_action( 'genesis_after_header', 'egh_professionals_header' );

 //* Add the inner sub nav
 add_action( 'genesis_after_header', 'egh_inner_subnav' );

 //* Remove Genesis loop and replace with custom single pro loop
 remove_action ('genesis_loop', 'genesis_do_loop'); // Remove the standard loop
 add_action( 'genesis_loop', 'egh_pro_intro' ); // Add custom loop

 //* Remove below content widget above the footer
 remove_action( 'genesis_before_footer', 'below_content_widget_area' );

 //* Add professional practice areas
 add_action( 'genesis_before_footer', 'egh_pro_legal_services', 11 );

 //* Add below content posts
 add_action( 'genesis_before_footer', 'egh_below_content_posts', 12 );

 //* Add the professional profile
 add_action( 'genesis_before_footer', 'egh_pro_profile', 13 );

}

//* Add professionals custom header
function egh_professionals_header() {
  if ( have_posts() ) {
    while ( have_posts() ) {
  		the_post();

      $pro_header = get_field( 'pro_header' );

      ?>
      <div class="egh-header" style="background-image: url('<?php echo $pro_header; ?>');">
        <?php
          genesis_entry_header_markup_open();

          $pro_title = get_field( 'pro_title' );
          $pro_phone = get_field( 'pro_phone' );
          $pro_email = get_field( 'pro_email' );
          $pro_vcard = get_field( 'vcard' );
          $staff     = get_field( 'staff' );

          genesis_do_post_title();

          echo '<div class="title">' . $pro_title . '</div>';
          echo '<p><i class="fa fa-phone" aria-label="phone"></i>' . $pro_phone . '<br />';
          echo '<i class="fa fa-envelope" aria-label="email"></i><a href="mailto:' . eae_encode_str($pro_email) . '" target="_blank">' . eae_encode_str($pro_email) . '</a><br />';
          echo '<i class="fa fa-address-card-o" aria-hidden="true"></i><a href="' . $pro_vcard['url'] . '" target="_blank">Download vCard</a></p>';

          if( $staff ): ?>
          	<div class="staff">
          	<?php foreach( $staff as $staff ): ?>
                <?php
                  $staff_title = get_field( 'pro_title', $staff->ID );
                  $staff_phone = get_field( 'pro_phone', $staff->ID );
                  $staff_email = get_field( 'pro_email', $staff->ID );
                  $staff_vcard = get_field( 'vcard', $staff->ID );
                ?>
          	    <div class="staff-member">
          	    	<p><?php echo get_the_title( $staff->ID ); ?></p>
                  <p><?php echo $staff_title; ?></p>
                  <p><?php echo $staff_phone; ?></p>
                  <p><a href="mailto:<?php echo eae_encode_str( $staff_email ); ?>" target="_blank"><?php echo eae_encode_str( $staff_email ); ?></a></p>
                  <p><a href="<?php echo $staff_vcard['url']; ?>" target="_blank">Download vCard</a></p>
          	    </div>
          	<?php endforeach; ?>
            </div>
            <div class="clear"></div>
          <?php endif;

          genesis_entry_header_markup_close();
        ?>
      </div>
      <?php }
    } // end if
  // Restore original Post Data
  wp_reset_postdata();
}

//* Adds the inner sub navigation
function egh_inner_subnav() {
  echo '<div class="inner-scrollspy">';
  echo '<div id="navbar-affix" class="inner-sub-nav">';
  echo '<ul class="nav"><li><a href="#overview">Overview</a></li>';
  echo '</ul></div>';
}

//* Add the professional intro
function egh_pro_intro() {
  if ( have_posts() ) {
    echo '<div id="overview" class="pro-content intro-text">';
  	while ( have_posts() ) {
  		the_post();

      $pro_content = get_field( 'intro' );
      $pro_badges  = get_field( 'badges' );
  		//
  		// Post Content here
      echo $pro_content;
      echo '<p><a href="#profile" class="full-link">Read full profile &raquo;</a></p>';
      echo '<div class="pro-badges">';
      echo $pro_badges;
      echo '</div>';
  		//
  	} // end while
    echo '</div>';
  } // end if
  // Restore original Post Data
  wp_reset_postdata();
}

//* Add the practice areas of the professional
function egh_pro_legal_services() {
  if ( have_posts() ) {
    echo '<div class="pro-services">';
    echo '<div class="wrap">';
  	while ( have_posts() ) {
  		the_post();

      $pro_services = get_field( 'pa_relationships' );

  		// Post Content here
      echo '<div class="one-third first">';

        if( $pro_services ): ?>
          <h3>Legal Services</h3>
        	<ul class="service-list">
        	<?php foreach( $pro_services as $id ): ?>
        	    <li class="service practice">
        	    	<a href="<?php echo get_permalink( $id ); ?>"><?php echo get_the_title( $id ); ?></a>
        	    </li>
        	<?php endforeach; ?>
          </ul>
        <?php endif;

        $pro_education = get_field( 'education' );
        if( $pro_education ):
          $pro_education = preg_replace("/(.*)/i", "<li>$1</li>", $pro_education);
          $pro_education = str_replace("<li></li>", "", $pro_education);

          echo '<h3>Education</h3>';
          echo '<ul>' . $pro_education . '</ul>';
        endif;

      echo '</div>';
      echo '<div class="one-third">';

      $pro_bar = get_field( 'bar_admissions' );
      $pro_court = get_field( 'court_admissions' );
      $pro_affiliations = get_field( 'professional_affiliations' );
      $appointments = get_field( 'appointments' );
      $pro_licenses = get_field( 'professional_licenses' );

      if( $pro_bar ):
        $pro_bar = preg_replace("/(.*)/i", "<li>$1</li>", $pro_bar);
        $pro_bar = str_replace("<li></li>", "", $pro_bar);

        echo '<h3>Bar Admissions</h3>';
        echo '<ul>' . $pro_bar . '</ul>';
      endif;

      if( $pro_court ):
        $pro_court = preg_replace("/(.*)/i", "<li>$1</li>", $pro_court);
        $pro_court = str_replace("<li></li>", "", $pro_court);

        echo '<h3>Court Admissions</h3>';
        echo '<ul>' . $pro_court . '</ul>';
      endif;

      echo '</div>';
      echo '<div class="one-third">';

      if( $pro_affiliations ):
        $pro_affiliations = preg_replace("/^--(.*)/m", "<li>$1</li>", $pro_affiliations);  // Make 3rd level bullets
        $pro_affiliations = preg_replace("/(<li>.*?<\/li>(?!\n<li>))/s", "<ul>$1</ul>", $pro_affiliations); // Wrap 3rd level bullets
        $pro_affiliations = preg_replace("/^-(.*)/m", "<li>$1</li>", $pro_affiliations);  // Make 2nd level bullets
        $pro_affiliations = preg_replace("/(<\/li>).?(<ul>.*?<\/ul>)/s", "\n$2</li>", $pro_affiliations);  // Nest 3rd level within 2nd level
        $pro_affiliations = preg_replace("/(<li>.*?<\/li>(?!\n<li>)(?!<\/ul>))/s", "<ul>$1</ul>", $pro_affiliations);  // Wrap 2nd level bullets
        $pro_affiliations = preg_replace("/(.+?)(?:$|\n)(?!<li>|<ul>)/s", "<li>$1</li>", $pro_affiliations);  // Make 1st level bullets

        echo '<h3>Professional Affiliations</h3>';
        echo '<ul>' . $pro_affiliations . '</ul>';
      endif;
      
      if( $appointments ):
        $appointments = preg_replace("/^--(.*)/m", "<li>$1</li>", $appointments);  // Make 3rd level bullets
        $appointments = preg_replace("/(<li>.*?<\/li>(?!\n<li>))/s", "<ul>$1</ul>", $appointments); // Wrap 3rd level bullets
        $appointments = preg_replace("/^-(.*)/m", "<li>$1</li>", $appointments);  // Make 2nd level bullets
        $appointments = preg_replace("/(<\/li>).?(<ul>.*?<\/ul>)/s", "\n$2</li>", $appointments);  // Nest 3rd level within 2nd level
        $appointments = preg_replace("/(<li>.*?<\/li>(?!\n<li>)(?!<\/ul>))/s", "<ul>$1</ul>", $appointments);  // Wrap 2nd level bullets
        $appointments = preg_replace("/(.+?)(?:$|\n)(?!<li>|<ul>)/s", "<li>$1</li>", $appointments);  // Make 1st level bullets

        echo '<h3>Appointments</h3>';
        echo '<ul>' . $appointments . '</ul>';
      endif;


      if( $pro_licenses ):
        $pro_licenses = preg_replace("/(.*)/i", "<li>$1</li>", $pro_licenses);
        $pro_licenses = str_replace("<li></li>", "", $pro_licenses);

        echo '<h3>Professional Licenses</h3>';
        echo '<ul>' . $pro_licenses . '</ul>';
      endif;

      echo '</div>';
  		//
  	} // end while
    echo '</div>';
    echo '</div>';
  } // end if
  // Restore original Post Data
  wp_reset_postdata();
}

//* Add the below content posts
function egh_below_content_posts() {
  while ( have_posts() ) : the_post();
		/*
		*  Query posts for a relationship value.
		*  This method uses the meta_query LIKE to match the string "123" to the database value a:1:{i:0;s:3:"123";} (serialized array)
		*/
    $na_relationships = get_field('na_relationships');

    if (!empty($na_relationships)) {
  		$related_posts = get_posts(array(
        'post__in'        => $na_relationships,
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
            <h4><a href="<?php echo get_permalink( $related_post->ID ); ?>"><?php echo get_the_title( $related_post->ID ); ?></a></h4>
            <p class="date"><?php echo get_the_time( 'F j, Y', $related_post->ID ); ?></p>
            <p><?php echo get_the_excerpt( $related_post->ID ); ?></p>
      		</div>
  			<?php endforeach;
        echo '</div>';
        echo '</div>';
        echo '</div>';
  		endif;
    }
	endwhile; // end of the loop.

  // Restore original Post Data
  wp_reset_postdata();
}

//* Add the professional profile
function egh_pro_profile() {
  if ( have_posts() ) {
    echo '<div id="profile" class="pro-profile wrap">';
    echo '<div class="col-md-8 col-centered">';
    echo '<h3 class="profile-heading">Profile</h3>';
  	while ( have_posts() ) {
  		the_post();

      $pro_profile = get_field( 'profile' );
  		//
  		// Post Content here
      echo $pro_profile;
  		//
  	} // end while
    echo '</div>';
    echo '</div>';
    echo '</div>';
  } // end if
  // Restore original Post Data
  wp_reset_postdata();
}

genesis();
