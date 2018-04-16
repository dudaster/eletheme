<?php
if (!is_plugin_active('elementor/elementor.php') && !is_admin()) {
	die("In order for the Eletheme to work you need Elementor plugin installed");
}
// gets the elementor content page from templates

function get_template_object($type,$after=""){
		$after = $after ? '_'.$after : "";
		$isnone = get_page_by_title('_'.$type.'_[none]', OBJECT, 'elementor_library');
		if ($isnone) return false;// igonore the post type
		$page = get_page_by_title('_'.$type.$after, OBJECT, 'elementor_library');
		if (!$page && $type!='page' && $type!='elementor_library'){ // if we don't find a template we'll try find a default one
			$page = get_page_by_title('_[default]'.$after, OBJECT, 'elementor_library');
		}
		return $page;
}

function get_current_template($final=""){
			$title = get_template_name($final);
		$page = get_page_by_title($title, OBJECT, 'elementor_library');
		return $page;
}

function is_in_templates($type,$term,$final=""){
	global $wpdb;
	$templates = $wpdb->get_col( "SELECT `post_title`  FROM $wpdb->posts where `post_type` =  'elementor_library'" );
	$final = $final ? "_".$final : "";
	$term = $term ? "_".$term : "";
	if (in_array("_".$type.$term.$final, $templates)) return true; 
	return false;
}


function get_template_name($final=""){ //this function gets the name of the template soon
		global $post;

		$finals = $final ? "_".$final : "";

		$type=get_post_type();

		if($type=="page" || $type=="elementor_library" || is_in_templates($type,"","[none]")) return false;

//check for term, this should be revised in the future because we check only with one taxonomy!!!!
	$term_id=get_queried_object()->term_id;
	$taxonomy=get_queried_object()->taxonomy;
	if (!$term_id && is_single()){// if post, check for term
			$taxonomy_names = get_post_taxonomies( );
			$terms = wp_get_post_terms( $post->ID, $taxonomy_names[0] );// we take the first taxonomy
			$term_id=$terms[0];//iau doar prima cateogrie
	}
	if($term_id){ // if term_id found
		$found=false;
		$term = get_term($term_id,$taxonomy); //iau datele

		$page = is_in_templates($type,$term->slug,$final);// daca este categoria curenta
		if($page) return "_".$type."_".$term->slug.$finals;
		if (!$page) while (!$found && $term->parent != 0) {//caut categoria parinte sa vad daca are template elementor

				$term = ($term->parent == 0) ? $term : get_term($term->parent, $taxonomy);
				$page = is_in_templates($type,$term->slug,$final);
				if ($page) $found=true;

		}
		if ($found) return "_".$type."_".$term->slug.$finals;

	}

		$page = is_in_templates($type,'',$final);

		if ($page) return "_".$type.$finals;

		$page = is_in_templates("[default]",'',$final);

		if ($page) return "_[default]".$finals;

	return false;

}

function clean_header($string){//clean some stuff to behave like a normal theme
    $find = 'elementor-widget';
    return preg_replace('/('.$find.'(?=.*'.$find.'.*))/', '', $string);
}

/*-----------------------------------------------------------------------------------*/
/* Let's get the container for the post_type content
/*-----------------------------------------------------------------------------------*/
function post_type_container(){
	global $wp_query;

	$archive_type="";
	$is_category=false;
	if (is_category() || $wp_query->queried_object->term_id) {
		$is_category=true;
	}

	if (is_search()) {
		$archive_type= $GLOBALS['wp_query']->found_posts ? "[search]" : "[no-results]";
		//define('ARCHIVE_LOOP',false);
		define('ARCHIVE_ELEMENT',true); // nu stiu la ce bun... va trebui sa investighez
	}

	if ($is_category) {
		$archive_type= "[category]";
	}

	$page = get_current_template($archive_type); // we get the archive type template

	if (!$page){
		$is_category=false;
	}

	list($before,$after)=explode("{{content}}",get_eletheme($page->ID));
	$before=clean_header($before);
	//if it is archive than include the [archive] template
	if ( !is_single() && !is_404() && !is_search() && !$is_category) { //if we don't have category template would fetch for archive
		$archive_type="[archive]";

		$page = get_current_template($archive_type);

		if($page){ 
			define('ARCHIVE_ID',$page->ID);
			//$data = apply_filters( 'elementor/frontend/builder_content_data', $data, $post_id );
			list($top_archive,$bottom_archive)=explode("{{content}}",get_eletheme($page->ID));
		 } 
		// let's add the archive to the container	
		$before.=$top_archive;
		$before=clean_header($before);
		$after=$bottom_archive.$after;
		if ($top_archive && $bottom_archive) {
			define('ARCHIVE_LOOP',true); //if we don't use posts widget for loop

		}
		else {
			define('ARCHIVE_ELEMENT',true); // we use _[loop]

		}

	}

	//print_r(get_queried_object());
	echo parse_content($before);
	add_action( 'eletheme_after_main_content', function() use ($after) { echo parse_content($after);}, 25 );

}

//add action give it the name of our function to run
add_action( 'eletheme_before_main_content', 'post_type_container', 25 );

/*-----------------------------------------------------------------------------------*/
/* Gets template for single or looped posts and then parse's it
/*-----------------------------------------------------------------------------------*/
function post_type_content($content){
	global $post,$wp_query; 

	if ($wp_query->queried_object_id  !=  $post->ID && !defined('ARCHIVE_LOOP')) return parse_content($content); // prevents infinite loop

	if (is_single()) {
		$after="[single]";
	} else {
		$after="[loop]";
	}
	$page = get_current_template($after); // get the template
	if($page) return  parse_content(get_eletheme($page->ID),$post,$content);

	return parse_content($content);


}

/*-----------------------------------------------------------------------------------*/
/* Here we change the content of the post
/*-----------------------------------------------------------------------------------*/
add_action( 'pre_get_posts', 'eletheme_pre_get_posts_callback' );
function eletheme_pre_get_posts_callback( $query ) {
		if ( $query->is_main_query() ) {
				add_filter( 'the_content', 'post_type_content',999 ); 
		}
}

function featured_image_before_content() {
	global $post; 
	if ( has_post_thumbnail() ) {
		$feat_image_url = get_the_post_thumbnail_url($post,'full');
		} 
		return $feat_image_url;
}

function get_my_excerpt($text){
	global $post;

		$text = strip_shortcodes( $text );
		$text = str_replace(']]>', ']]&gt;', $text);

		/* Remove unwanted JS code */
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);

		/* Strip HTML tags, but allow certain tags */
		$text = strip_tags($text);

		$excerpt_length = 55;
		$excerpt_more = '[...]';
		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count($words) > $excerpt_length ) {
			array_pop($words);
			$text = implode(' ', $words);
			$text = $text . $excerpt_more;
		} else {
			$text = implode(' ', $words);
		}

	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

function get_eletheme($pageid){
	add_action( 'elementor/frontend/widget/before_render', 'passtheid' , 10 , 1); // pass the curent id to the widgets;
	return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $pageid );

}

function is_elementor($pageid){
	return Elementor\Plugin::$instance->db->is_built_with_elementor( $pageid );
}

function getProductAttributes($id,$attribute){
			if (function_exists('wc_get_product_terms')) return implode(', ', wc_get_product_terms( $id, $attribute ));
}

/*-----------------------------------------------------------------------------------*/
/* Replacing the curly brakets tags with the actual values
/*-----------------------------------------------------------------------------------*/
function parse_content($t,$post=NULL,$content=""){
	global $wp_query;
	if ($post!=NULL) global $post;
	if ($post->ID) $var=$post; 
		else $var=get_queried_object();

/**  Set custom vars **/
	$id=$post->ID;
	if($id) $permalink=get_permalink($id);
	$featured_image=featured_image_before_content();
	$title=$var->name . $var->post_title;
	$name=get_queried_object()->name;
	if ($var->term_id) $description=do_shortcode(wpautop($var->description)); else {
		if (isset($var->description)) $var->description=NULL; /// to work only with terms descriptions
	} 

	if(!is_single() && !$content) $content=$var->post_content; // if it is an elementor format it would not work... please research a little bit | nu merge sa se cheme elementor de id cand este in ea...
	$post_excerpt = $post_excerpt ? $post_excerpt : get_my_excerpt($content);

	// add your own custom vars

	$custom_vars=apply_filters( 'eletheme_vars', $custom_vars ); 

	foreach($custom_vars as $key=>$value){
		$$key=$value;
	}

/** end seting custom vars **/
// replacing the keystrings from the template with the actual values. (ie for $content you have {content})

	preg_match_all('~\{\{(.*?)\}\}~si',$t,$matches);//get all the placeholders to replace them with values from.

	if ( isset($matches[1])) {
		$value=="";
		foreach ($matches[1] as $key) {

			$value=isset($$key) ? $$key : $var->$key; //echo "<br/> ".$key." "; print_r($var->$key);
			if ($value=="") { //echo "<br/> ".$key." "; print_r($custom_field);
				//Daca nu a gasit nici o proprietate a obeictului cauta custom field
				if ($post->ID) {
					$custom_field=get_post_meta( $post->ID, $key, true); //echo "<br/>..".$key." :"; print_r($custom_field);
				}
				$value=$custom_field ? $custom_field : "";//pune custom field sau sa stearga keya daca nu are valoare 
				if ($value=="") $value = getProductAttributes($post->ID,$key); // iau custom product attribute
				if ($value=="" && function_exists('get_field') && $var->term_id) $value = get_field($key, $var->taxonomy.'_'.$var->term_id);// iau custom field de la taxonomie
				if ($value=="") $value=$wp_query->query_vars[$key]; //get query_vars
			}
			$t = str_replace('{{'.$key.'}}',$value,$t);

		}
	}
	return $t;
}
/*-----------------------------------------------------------------------------------*/
/* Some preps for eleplug
/*-----------------------------------------------------------------------------------*/			
function passtheid( $widget ) {
	global $wp_query;
	// check for image widget.
	if( $widget->get_name() === 'w2cart' ) {//echo $widget->get_name();

		$widget->set_settings('loopid', $wp_query->queried_object_id); //echo $this->get_post_id();

	}
	if( $widget->get_name() === 'wc-add-to-cart' ) {
		$settings = $widget->get_active_settings();
		if ( ! empty( $settings['eleplug_dynamic_value'] ) && $settings['eleplug_dynamic_value'] === 'yes' ) {

			$widget->set_settings('product_id',  $wp_query->queried_object_id);
		}
	}



}


/*-----------------------------------------------------------------------------------*/
/* Define header and footer constants
/*-----------------------------------------------------------------------------------*/

function set_eletheme(){
	global $post;
	$type=get_post_type();
		if (is_in_templates('!'.$type,"") && is_single()) 
				$page = get_page_by_title('_!'.$type, OBJECT, 'elementor_library'); 
			else
				$page = get_page_by_title('[body]', OBJECT, 'elementor_library');
		if($page) 
			list($myhead,$myfooter)=explode("{{content}}",get_eletheme($page->ID));
		define( 'ELE_HEADER',parse_content(clean_header($myhead)));
		define( 'ELE_FOOTER',parse_content($myfooter));
}
add_action( 'wp_head', 'set_eletheme', 23 );