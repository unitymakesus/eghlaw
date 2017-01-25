<?php
/**
 * EGH Genesis.
 *
 * This file adds functions to the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Custom Post Types
include_once( get_stylesheet_directory() . '/lib/custom-post-types.php' );

//* Theme headers
include_once( get_stylesheet_directory() . '/lib/theme-headers.php' );

//* Theme widgets
include_once( get_stylesheet_directory() . '/lib/theme-widgets.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'egh-genesis', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'egh-genesis' ) );

//* Add Image upload and Color select to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Include Customizer CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'EGH Genesis' );
define( 'CHILD_THEME_URL', 'http://www.unitymakes.us/' );
define( 'CHILD_THEME_VERSION', '1.0' );

//* Enqueue Scripts and Styles
add_filter( 'wp_enqueue_scripts', 'egh_enqueue_first', 0 );
function egh_enqueue_first() {
    wp_enqueue_script( 'egh-custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array( 'jquery' ), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'egh_genesis_enqueue_scripts_styles' );
function egh_genesis_enqueue_scripts_styles() {

	wp_enqueue_style( 'egh-genesis-fonts', '//fonts.googleapis.com/css?family=Cabin:400,400i,700|Cardo:400,400i,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

	wp_enqueue_style( 'egh-custom-css', get_stylesheet_directory_uri() . '/assets/css/custom.css' );

	wp_enqueue_script( 'egh-genesis-responsive-menu', get_stylesheet_directory_uri() . '/assets/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	$output = array(
		'mainMenu' => __( 'Menu', 'egh-genesis' ),
		'subMenu'  => __( 'Menu', 'egh-genesis' ),
	);
	wp_localize_script( 'egh-genesis-responsive-menu', 'genesisSampleL10n', $output );

	wp_enqueue_script( 'egh-bootstrap-js', get_stylesheet_directory_uri() . '/assets/js/bootstrap.js', array( 'jquery' ), '1.0.0', true );

}

//* Soil clean up
add_theme_support('soil-clean-up');

//* Cleaner walker for navigation menus
add_theme_support('soil-nav-walker');

//* Convert search results
//add_theme_support('soil-nice-search');

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

//* Add Accessibility support
// add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add Image Sizes
add_image_size( 'featured-image', 720, 400, TRUE );

//* Rename primary and secondary navigation menus
add_theme_support( 'genesis-menus' , array( 'primary' => __( 'After Header Menu', 'egh-genesis' ), 'secondary' => __( 'Footer Menu', 'egh-genesis' ) ) );

//* Custom logo support
add_theme_support( 'custom-logo' );

//* Remove Genesis in-post SEO Settings
remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

//* Remove Genesis SEO Settings menu link
remove_theme_support( 'genesis-seo-settings-menu' );

//* Remove the site title
remove_action( 'genesis_site_title', 'genesis_seo_site_title' );

//* Remove the site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//* Add custom logo in place of genesis site title and description
add_action( 'genesis_site_title', 'egh_genesis_custom_logo', 1 );
function egh_genesis_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'egh_genesis_secondary_menu_args' );
function egh_genesis_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

//* Modify size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'egh_genesis_author_box_gravatar' );
function egh_genesis_author_box_gravatar( $size ) {

	return 90;

}

//* Modify size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'egh_genesis_comments_gravatar' );
function egh_genesis_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}

//* Change heading defaults for Genesis widgets
add_filter( 'genesis_register_widget_area_defaults', 'egh_genesis_widget_defaults' );
function egh_genesis_widget_defaults($defaults) {

		$defaults = array(
			'before_widget' => genesis_markup( array(
				'open'    => '<section id="%%1$s" class="widget %%2$s"><div class="widget-wrap">',
				'context' => 'widget-wrap',
				'echo'    => false,
			) ),
			'after_widget'  => genesis_markup( array(
				'close'   => '</div></section>' . "\n",
				'context' => 'widget-wrap',
				'echo'    => false
			) ),
			'before_title'  => '<h3 class="widget-title widgettitle">',
			'after_title'   => "</h3>\n",
		);

		return $defaults;

}

//* Add read more link
add_filter( 'the_excerpt', 'egh_read_more_link' );
function egh_read_more_link($content) {
	return $content . '<a class="more-link" href="' . get_permalink() . '">Read more</a>';
}

//* Add allowed file types
add_filter('upload_mimes', 'my_mime_types');
function my_mime_types($mime_types) {
    $mime_types['vcf'] = 'text/x-vcard'; //VCard
    return $mime_types;
}

// Add To Top button
add_action( 'genesis_before', 'genesis_to_top');
function genesis_to_top() {
 echo '<a href="#" class="to-top" title="Back To Top" aria-hidden="true"><i class="fa fa-arrow-up"></i><span hidden>Back To Top</span></a>';
}

// Skip to content link
add_action( 'genesis_before', 'skip_links');
function skip_links() {
 echo '<a href="#egh-header" class="screen-reader-shortcut">Skip to content</a>';
}

// Email disclaimer modal
add_action('genesis_after_footer', 'email_disclaimer_modal');
function email_disclaimer_modal() {
  ob_start();
  ?>
  <div class="modal fade" id="email-disclaimer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Email Disclaimer</h4>
        </div>
        <div class="modal-body">
          Electronic mail to us in connection with a matter for which we do not already represent you may not be treated as privileged or confidential. Please do not send confidential information to us via e-mail without first communicating directly with us. The transmission of an e-mail request for information does not create an attorney-client relationship.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" id="email-agree" href="mailto:info@eghlaw.com" target="_blank">I agree</a>
        </div>
      </div>
    </div>
  </div>
  <?php
  return ob_end_flush();
}
