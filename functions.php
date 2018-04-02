<?php

add_filter( 'emoji_svg_url', '__return_false' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 


// Add custom keywords to the eletheme
add_filter( 'eletheme_vars', 'new_keywords');
function new_keywords( $custom_vars ) {
    $custom_vars['current_year']=date('Y');
    return $custom_vars;
}

add_action( 'after_setup_theme', 'eletheme_setup' );
function eletheme_setup()
{
	load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 640;
	register_nav_menus(
		array( 'main-menu' => __( 'Main Menu', 'blankslate' ) )
		);
}

add_action( 'wp_enqueue_scripts', 'eletheme_load_scripts' );
function eletheme_load_scripts()
{
	wp_enqueue_script( 'jquery' );
}

add_action( 'comment_form_before', 'eletheme_enqueue_comment_reply_script' );
function eletheme_enqueue_comment_reply_script()
{
	if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}

add_filter( 'the_title', 'eletheme_title' );
function eletheme_title( $title ) {
	if ( $title == '' ) {
		return '&rarr;';
	} else {
		return $title;
	}
}

add_filter( 'wp_title', 'eletheme_filter_wp_title' );
function eletheme_filter_wp_title( $title )
{
	return $title . esc_attr( get_bloginfo( 'name' ) );
}


if ( ! function_exists( 'generate_widgets_init' ) ) :
/**
 * Register widgetized area and update sidebar with default widgets
 */

add_action( 'widgets_init', 'generate_widgets_init' );
function generate_widgets_init() 
{
	// Set up our array of widgets	
	$widgets = array(

/*		'header' => array(
			'name' => __( 'Header', 'eletheme' )
		),
		'footer' => array(
			'name' => __( 'Footer', 'eletheme' )
		),*/
		'sidebar' => array(
			'name' => __( 'Sidebar', 'eletheme' )
		),
	);

	// Loop through them to create our widget areas
	foreach ( $widgets as $widget => $id ) {

		register_sidebar( array(
			'name'          => $id[ 'name' ],
			'id'            => $widget,
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		) );
	}
}
endif;



function eletheme_custom_pings( $comment )
{
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
	<?php 
}

add_filter( 'get_comments_number', 'eletheme_comments_number' );
function eletheme_comments_number( $count )
{
	if ( !is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
		return count( $comments_by_type['comment'] );
	} else {
		return $count;
	}
}


/*-----------------------------------------------------------------------------------*/
/* Toplevel Page Menus not working at the moment.
/*-----------------------------------------------------------------------------------*/

function toplevel_admin_menu_pages(){
	//die( "sunt aici");
if ( current_user_can('administrator') ) {  // If the user is not the administrator remove and add new menus
    add_menu_page( 'Elements', 'Elements', 'edit_post', 'edit.php?post_type=elementor_library', '', 'dashicons-admin-home', 6 );
    }
  }
add_action( 'admin_menu', 'toplevel_admin_menu_pages' );

function sample_admin_notice__error() {
	$class = 'notice notice-error';
	$message = __( 'Install elementor and reactivate the theme for the default template to be imported !!!!', 'sample-text-domain' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}


include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('elementor/elementor.php')) {
	require_once get_template_directory() . '/elementor'. '/instalation.php';
} else add_action( 'admin_notices', 'sample_admin_notice__error' ); 

require_once get_template_directory() . '/menu'. '/sm_navwalker.php';
require_once get_template_directory() . '/admin'. '/admin.php';
require_once get_template_directory() . '/elementor'. '/container.php';
require_once get_template_directory() . '/elementor'. '/elements.php';


/*foogalerry to work with ligbox bank*/
function add_foogallery_link_rel($attr, $args, $attachment) {
	$attr['class'] = 'gallery-item';
	return $attr;
}

add_filter('foogallery_attachment_html_link_attributes', 'add_foogallery_link_rel', 10, 3);

function trysomething(){	
	print_r(get_template_name());
	echo "ceva";
}
//add_action( 'wp_head', 'trysomething', 23 );


	function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
/*		$post_id = get_the_ID();
		$is_not_builder_mode = ! is_singular() || ! User::is_current_user_can_edit( $post_id ) || 'builder' !== Plugin::$instance->db->get_edit_mode( $post_id );
*/		if ( !current_user_can('administrator') ) {
			return;
		}
		$wp_admin_bar->add_node( [
			'id' => 'elementor_library',
			'title' => __( 'Templates', 'eletheme' ),
			'href' => '/wp-admin/edit.php?post_type=elementor_library',
		] );
	}
	add_action( 'admin_bar_menu', 'add_menu_in_admin_bar', 300 );


// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)


		add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
		function woocommerce_header_add_to_cart_fragment( $fragments ) {
			//echo" am ajusn pe aici";
			ob_start();
			?><a class="cart-contents animated flash" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'woocommerce' ); ?>">
								<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'woocommerce' ), WC()->cart->get_cart_contents_count() ) );?></span>
								<i class="fa Example of shopping-basket fa-shopping-basket"></i>
							</a>
			<?php
			
			$fragments['a.cart-contents'] = ob_get_clean();
			
			return $fragments;
		}



/*******************************
* Add woocommerce theme support
********************************/

add_theme_support('woocommerce');

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);


/*****************************
* Add automatic update script
******************************/

require 'puc/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/dudaster/eletheme/',
	__FILE__,
	'eletheme'
);

$myUpdateChecker->setBranch('stable');