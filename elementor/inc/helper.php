<?php


function eletheme_get_post_data($args){
    $defaults = array(
        'posts_per_page'   => 5,
        'offset'           => 0,
        'category'         => '',
        'category_name'    => '',
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'author'	   => '',
        'author_name'	   => '',
        'post_status'      => 'publish',
        'suppress_filters' => true
    );

    $atts = wp_parse_args($args,$defaults);

    $posts = get_posts($atts);

    return $posts;
}

function eletheme_get_post_types(){
    $args = array(
        'public' => true
    );

    $skip_post_types = ['attachment'];

    $post_types = get_post_types($args);
    return $post_types;
}

function eletheme_get_post_settings($settings){
    $post_args['post_type'] = $settings['post_type'];

    if($settings['post_type'] == 'post'){
        $post_args['category'] = $settings['category'];
    }

    $post_args['posts_per_page'] = $settings['num_posts'];
    $post_args['offset'] = $settings['post_offset'];
    $post_args['orderby'] = $settings['orderby'];
    $post_args['order'] = $settings['order'];

    return $post_args;
}

function eletheme_get_excerpt_by_id($post_id,$excerpt_length){
    $the_post = get_post($post_id); //Gets post ID
    $the_excerpt = ($the_post ? $the_post->post_content : null); //Gets post_content to be used as a basis for the excerpt
    //echo $the_excerpt;
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '…');
        $the_excerpt = implode(' ', $words);
    endif;

    return $the_excerpt;
}

function eletheme_get_thumbnail_sizes(){
    $sizes = get_intermediate_image_sizes();
    foreach($sizes as $s){
        $ret[$s] = $s;
    }

    return $ret;
}

function eletheme_get_post_orderby_options(){
    $orderby = array(
        'ID' => 'Post Id',
        'author' => 'Post Author',
        'title' => 'Title',
        'date' => 'Date',
        'modified' => 'Last Modified Date',
        'parent' => 'Parent Id',
        'rand' => 'Random',
        'comment_count' => 'Comment Count',
        'menu_order' => 'Menu Order',
    );

    return $orderby;
}