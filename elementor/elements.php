<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'MY_ELEMENTS', get_template_directory()."/elementor/");


require_once MY_ELEMENTS.'inc/elementor-helper.php';

function add_eletheme_elements(){


	require_once MY_ELEMENTS.'inc/helper.php';

	// load elements
	require_once MY_ELEMENTS.'elements/logo.php';
	require_once MY_ELEMENTS.'elements/menu.php';

}
add_action('elementor/widgets/widgets_registered','add_eletheme_elements');


require_once MY_ELEMENTS.'inc/posts_featured_image.php';// set featured bg to section


function eletheme_scripts(){
 //  wp_enqueue_style('eletheme-css',MY_ELEMENTS.'assets/css/elements.css');
	   wp_enqueue_script('eletheme-js1',get_template_directory_uri().'/elementor/assets/js/mobile-menu.js');
}
add_action( 'wp_enqueue_scripts', 'eletheme_scripts' );