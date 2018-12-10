<?php
/**
 * A template for inserting dynamic image post types with the shorcode.
 *
 * @package        WordPress
 * @subpackage     ASD_DynamicImages
 * Author:         Michael H Fahey
 * Author URI:     https://artisansitedesigns.com/staff/michael-h-fahey
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

global $post;

$default_image_url = '';
$image_url_xs      = '';
$image_url_sm      = '';
$image_url_md      = '';
$image_url_lg      = '';
$image_url_xl      = '';
$image_url_xxl     = '';

$image_id_xs  = get_post_meta( $post->ID, 'image_path_xs', 'true' );
$image_id_sm  = get_post_meta( $post->ID, 'image_path_sm', 'true' );
$image_id_md  = get_post_meta( $post->ID, 'image_path_md', 'true' );
$image_id_lg  = get_post_meta( $post->ID, 'image_path_lg', 'true' );
$image_id_xl  = get_post_meta( $post->ID, 'image_path_xl', 'true' );
$image_id_xxl = get_post_meta( $post->ID, 'image_path_xxl', 'true' );

$image_classes = get_post_meta( $post->ID, 'image_classes', 'true' );
$class_attr    = '';
if ( $image_classes ) {
	$class_attr = 'class="' . $image_classes . '"';
} else {
	$class_attr = 'class="dynamic-image"';
}

$image_class = get_post_meta( $post->ID, 'image_class', 'true' );
if ( $image_class ) {
	$image_class .= ' ';
}
$image_class .= 'dynamic-image dynamic-image-' . $post->ID;

$image_id = get_post_meta( $post->ID, 'image_id', 'true' );
if ( ! $image_id ) {
	$image_id = 'dynamic-image-' . $post->ID;
}

/*
 * get image size definitions in reverse order, biggest to smallest
 * so that the smallest available image becomes the default
 */
$image_url_xxl = '';
if ( $image_id_xxl ) {
	$image_url_xxl     = wp_get_attachment_url( $image_id_xxl );
	$default_image_url = $image_url_xxl;
}
$image_url_xl = '';
if ( $image_id_xl ) {
	$image_url_xl      = wp_get_attachment_url( $image_id_xl );
	$default_image_url = $image_url_xl;
}
$image_url_lg = '';
if ( $image_id_lg ) {
	$image_url_lg      = wp_get_attachment_url( $image_id_lg );
	$default_image_url = $image_url_lg;
}
$image_url_md = '';
if ( $image_id_md ) {
	$image_url_md      = wp_get_attachment_url( $image_id_md );
	$default_image_url = $image_url_md;
}
$image_url_sm = '';
if ( $image_id_sm ) {
	$image_url_sm      = wp_get_attachment_url( $image_id_sm );
	$default_image_url = $image_url_sm;
}
$image_url_xs = '';
if ( $image_id_xs ) {
	$image_url_xs      = wp_get_attachment_url( $image_id_xs );
	$default_image_url = $image_url_xs;
}
if ( $default_image_url ) {
	echo '<img id="' . esc_attr( $image_id ) . '" class="' . esc_attr( $image_class ) . '" src="' . esc_attr( rtrim( $default_image_url ) ) . '"/>' . "\r\n";
}

$random_numeric_suffix = wp_rand( 100000, 999999 );

echo '<script type="text/javascript">' . "\r\n";
echo '   // script for dynamic image ' . esc_attr( $post->ID ) . "\r\n";
echo '   jQuery(document).ready( function() {' . "\r\n";
echo '      set_dynamic_image_src( "#' . esc_attr( $image_id ) . '", "' .
										esc_url( $image_url_xs ) . '", "' .
										esc_url( $image_url_sm ) . '", "' .
										esc_url( $image_url_md ) . '", "' .
										esc_url( $image_url_lg ) . '", "' .
										esc_url( $image_url_xl ) . '", "' .
										esc_url( $image_url_xxl ) . '" );' . "\r\n";
echo '   });' . "\r\n";

echo '   jQuery(function() {' . "\r\n";
echo '      var $thewindow = jQuery( window );' . "\r\n";
echo '      var thewidth' . esc_attr( $random_numeric_suffix ) . ' = $thewindow.width();' . "\r\n";
echo '      setInterval( function() {' . "\r\n";
echo '         if(  thewidth' . esc_attr( $random_numeric_suffix ) . ' != $thewindow.width() ) {' . "\r\n";
echo '            thewidth' . esc_attr( $random_numeric_suffix ) . ' = $thewindow.width();' . "\r\n";
echo '            set_dynamic_image_src( "#' . esc_attr( $image_id ) . '", "' .
										esc_url( $image_url_xs ) . '", "' .
										esc_url( $image_url_sm ) . '", "' .
										esc_url( $image_url_md ) . '", "' .
										esc_url( $image_url_lg ) . '", "' .
										esc_url( $image_url_xl ) . '", "' .
										esc_url( $image_url_xxl ) . '" );' . "\r\n";
echo '         }' . "\r\n";
echo '      }, 1000);' . "\r\n";
echo '   });' . "\r\n";

echo '</script>' . "\r\n";
