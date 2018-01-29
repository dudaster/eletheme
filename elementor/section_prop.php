<?php
$existing_image_carousel = Elementor\Plugin::instance()->widgets_manager->get_widget('image-carousel');
$existing_image_carousel->add_control(
    'dtbwp_border_style',
    [
        'label' => __( 'Border Style', 'elementor' ),
        'type' => 'select', //Controls_Manager::SELECT,
        'default' => 'default',
        'section' => 'section_image_carousel',
        'options' => array(
            'default' => 'Default',
            'boxed' => 'Line Box',
        ),
    ]
);
$existing_image_carousel->add_control(
    'dtbwp_layout_type',
    [
        'label' => __( 'Layout Type', 'elementor' ),
        'type' => 'select',
        'default' => 'default',
        'section' => 'section_image_carousel',
        'options' => array(
            'default' => 'Default',
            'slider' => 'Slider with Text',
        ),
    ]
);
// modify output of existing widget.
add_action('elementor/widgets/render_content/before', function($widget, $instance = []){
    if(!empty($instance['dtbwp_border_style'])) {
        $widget->add_render_attribute( 'widget-container', 'class', [
            esc_attr('dtbwp-border-style-' . $instance['dtbwp_border_style'])
        ] );
    }
    if(!empty($instance['dtbwp_layout_type'])) {
        $widget->add_render_attribute( 'widget-container', 'class', [
            esc_attr('dtbwp-layout-type-' . $instance['dtbwp_layout_type'])
        ] );
    }
}, 13, 100);
add_filter('elementor/widgets/image_carousel/image_html', function($image_html, $instance = []){
    //print_r($instance);
    if(!empty($instance['dtbwp_layout_type']) && $instance['dtbwp_layout_type'] == 'slider') {
        $slider_text = '<h2>Post Title</h2>';
        $slider_text .= '<p>Post text post text post text post text post text post text post text post text post text </p>';
        $slider_text .= '<a href="adf">Test Button</a>';
        $image_html = '<div class="dtbaker-image-slider-text">' . $slider_text . '</div><div class="dtbaker-image-slider-image"><div class="dtbaker-photo-frame"><div>' . $image_html . '</div></div></div>';
    }
    return $image_html;
}, 13, 100);