<?php
/**
 * EGH Genesis.
 *
 * This file adds custom post types to the EGH Genesis Theme.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

add_action( 'init', function() {
	register_post_type( 'professional',
		array('labels' => array(
				'name' => 'Professionals',
				'singular_name' => 'Professional',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Professional',
				'edit' => 'Edit',
				'edit_item' => 'Edit Professional',
				'new_item' => 'New Professional',
				'view_item' => 'View Professional',
				'search_items' => 'Search Professionals',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions', 'page-attributes', 'thumbnail' ),
			'public' => true,
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true
		)
	);

	register_post_type( 'practice-area',
		array('labels' => array(
				'name' => 'Practice Areas',
				'singular_name' => 'Practice Area',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Practice Area',
				'edit' => 'Edit',
				'edit_item' => 'Edit Practice Area',
				'new_item' => 'New Practice Area',
				'view_item' => 'View Practice Area',
				'search_items' => 'Search Practice Areas',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => true,
			'supports' => array( 'title', 'editor', 'revisions', 'thumbnail' ),
			'public' => true,
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true
		)
	);
});

register_taxonomy( 'type',
	array('professional'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Types' ),
			'singular_name' => __( 'Type' ),
			'search_items' =>  __( 'Search Types' ),
			'all_items' => __( 'All Types' ),
			'parent_item' => __( 'Parent Type' ),
			'parent_item_colon' => __( 'Parent Type:' ),
			'edit_item' => __( 'Edit Type' ),
			'update_item' => __( 'Update Type' ),
			'add_new_item' => __( 'Add New Type' ),
			'new_item_name' => __( 'New Type Name' )
		),
		'public' => true,
		'show_ui' => true,
		'rewrite' => array(
			'slug' => 'professionals'
		)
	)
);


/*-------------------------------------------------------------------------------
	Custom Columns
-------------------------------------------------------------------------------*/

function egh_posts_columns($columns) {
	$columns = array(
		'cb'	 	=> '<input type="checkbox" />',
		'title' 	=> 'Title',
    'na_relationships' => 'Attorneys',
    'np_relationships' => 'Practice Areas',
		'date'		=>	'Date',
	);
	return $columns;
}

function egh_profs_columns($columns) {
	$columns = array(
		'cb'	 	=> '<input type="checkbox" />',
		'title' 	=> 'Title',
    'pa_relationships' => 'Practice Areas',
		'date'		=>	'Date',
	);
	return $columns;
}

function egh_pas_columns($columns) {
	$columns = array(
		'cb'	 	=> '<input type="checkbox" />',
		'title' 	=> 'Title',
    'pa_relationships' => 'Attorneys',
		'date'		=>	'Date',
	);
	return $columns;
}

function egh_custom_columns($column) {
	global $post;
	if($column == 'na_relationships') {
    if ($attorneys = get_field('na_relationships', $post->ID)) {
      foreach ($attorneys as &$a) {
        $a = get_the_title($a);
      }
  		echo implode(', ', $attorneys);
    }
	}
	elseif($column == 'pa_relationships') {
    if ($pas = get_field('pa_relationships', $post->ID)) {
      foreach ($pas as &$p) {
        $p = get_the_title($p);
      }
  		echo implode(', ', $pas);
    }
	}
	elseif($column == 'np_relationships') {
    if ($nps = get_field('np_relationships', $post->ID)) {
      foreach ($nps as &$p) {
        $p = get_the_title($p);
      }
  		echo implode(', ', $nps);
    }
	}
}

add_action("manage_posts_custom_column", "egh_custom_columns");
add_action("manage_pages_custom_column", "egh_custom_columns");
add_filter("manage_edit-post_columns", "egh_posts_columns");
add_filter("manage_edit-professional_columns", "egh_profs_columns");
add_filter("manage_edit-practice-area_columns", "egh_pas_columns");
