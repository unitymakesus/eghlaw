<?php
/**
 * EGH Genesis.
 *
 * This file adds the Customizer additions to the EGH Genesis.
 *
 * @package EGH Genesis
 * @author  Unity
 * @license GPL-2.0+
 * @link    http://www.unitymakes.us/
 */

/**
 * Get default link color for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 1.0
 *
 * @return string Hex color code for link color.
 */
function egh_genesis_customizer_get_default_link_color() {
	return '#c3251d';
}

/**
 * Get default accent color for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 1.0
 *
 * @return string Hex color code for accent color.
 */
function egh_genesis_customizer_get_default_accent_color() {
	return '#c3251d';
}

add_action( 'customize_register', 'egh_genesis_customizer_register' );
/**
 * Register settings and controls with the Customizer.
 *
 * @since 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function egh_genesis_customizer_register() {

	global $wp_customize;

	$wp_customize->add_setting(
		'egh_genesis_link_color',
		array(
			'default'           => egh_genesis_customizer_get_default_link_color(),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'egh_genesis_link_color',
			array(
				'description' => __( 'Change the default color for linked titles, menu links, post info links and more.', 'genesis-sample' ),
			    'label'       => __( 'Link Color', 'genesis-sample' ),
			    'section'     => 'colors',
			    'settings'    => 'egh_genesis_link_color',
			)
		)
	);

	$wp_customize->add_setting(
		'egh_genesis_accent_color',
		array(
			'default'           => egh_genesis_customizer_get_default_accent_color(),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'egh_genesis_accent_color',
			array(
				'description' => __( 'Change the default color for button hovers.', 'genesis-sample' ),
			    'label'       => __( 'Accent Color', 'genesis-sample' ),
			    'section'     => 'colors',
			    'settings'    => 'egh_genesis_accent_color',
			)
		)
	);

}
