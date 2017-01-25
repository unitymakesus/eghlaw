<?php
/**
 * EGH Genesis.
 *
 * This file adds the theme header settings to the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

//* Add the entry header markup and entry title before the content on all pages except the front page
add_action( 'genesis_after_header', 'egh_genesis_add_entry_header' );
function egh_genesis_add_entry_header() {
	if (is_front_page()) {
		return;
	} elseif ( is_page() || is_single() ) {
		$backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
		?>
		<div class="egh-header" id="egh-header" style="background: url('<?php if ( has_post_thumbnail() ) { echo $backgroundImg[0]; } ?>') no-repeat center; background-size: cover;">
			<?php
				//* Remove the entry header markup (requires HTML5 theme support)
				remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
				remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

				//* Remove the entry title (requires HTML5 theme support)
				remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

				genesis_entry_header_markup_open();
				genesis_do_post_title();
				if ($subtitle = get_field('subtitle')) {
					echo '<h2>' . $subtitle . '</h2>';
				}
				genesis_entry_header_markup_close();
			?>
		</div>
	<?php } elseif ( is_home() ) { ?>
	<div class="egh-header" id="egh-header">
		<?php
			remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );

			genesis_entry_header_markup_open();
			genesis_do_posts_page_heading( '<h1>', '</h1>' );
			genesis_entry_header_markup_close();
		?>
	</div>
<?php } elseif ( is_date() ) { ?>
	<div class="egh-header" id="egh-header">
		<?php
			remove_action( 'genesis_before_loop', 'genesis_do_date_archive_title' );

			genesis_entry_header_markup_open();
			genesis_do_date_archive_title( '<h1>', '</h1>' );
			genesis_entry_header_markup_close();
		?>
	</div>
<?php } elseif ( is_archive() ) { ?>
	<div class="egh-header" id="egh-header">
		<?php
			remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

			genesis_entry_header_markup_open();
			genesis_do_taxonomy_title_description( '<h1>', '</h1>' );
			genesis_entry_header_markup_close();
		?>
	</div>
<?php }
}
