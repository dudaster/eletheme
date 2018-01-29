<?php
function eletheme_register_dynamic_background( $widget, $args ){
	$widget->add_control(
		'eleplug_dynamic_value',
		[
			'label' => __( 'Dynamic Value?', 'elementor-pro' ),
			'type' =>  \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
			'label_on' => __( 'Yes', 'elementor' ),
			'label_off' => __( 'No', 'elementor' ),
			'condition' => [
//				'background' => [ 'classic' ],
//				'image[url]!' => '',
			],
		]
	);
	$widget->add_control(
		'eleplug_dynamic_value_info',
		[
			'label' => __( 'In posts this value would be changed with the dynamic value' ),
			'type' => \Elementor\Controls_Manager::RAW_HTML,
			'condition' => [
				'eleplug_dynamic_value!' => '',
			],
		]
	);

}

// add control of thumbnail image on certain elements.
add_action( 'elementor/element/section/section_background/before_section_end', 'eletheme_register_dynamic_background', 10, 2 );
add_action( 'elementor/element/column/section_style/before_section_end', 'eletheme_register_dynamic_background', 10, 2 );
add_action( 'elementor/element/image/section_image/before_section_end', 'eletheme_register_dynamic_background', 10, 2 );


function eletheme_dynamic_before_render( $widget ) {
	//global $supported_widgets;
	// check for background image support.
	if( $widget->get_name() === 'section' || $widget->get_name() === 'column' ) {
		$settings = $widget->get_active_settings();
		if ( ! empty( $settings['eleplug_dynamic_value'] ) && $settings['eleplug_dynamic_value'] === 'yes' ) {

				$widget->add_render_attribute( '_wrapper', 'style', 'background-image: url("{{featured_image}}") !important;' );
		}
	}

	// check for image widget.
	if( $widget->get_name() === 'image' ) {
		$settings = $widget->get_active_settings();
		if ( ! empty( $settings['eleplug_dynamic_value'] ) && $settings['eleplug_dynamic_value'] === 'yes' ) {

			$widget->set_settings('image', ['url'=>'{{featured_image}}']);
		}
	}

}

add_action( 'elementor/frontend/element/before_render', 'eletheme_dynamic_before_render' , 10 , 1);
add_action( 'elementor/frontend/widget/before_render', 'eletheme_dynamic_before_render' , 10 , 1);

function eletheme_register_font( $widget, $args ){


				$widget->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'eleplug_typography',
						'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
						'selector' => '{{WRAPPER}} ',
					]
				);

				$widget->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'eleplug_text_shadow',
						'selector' => '{{WRAPPER}}',
					]
				);

}



add_action( 'elementor/element/section/section_typo/after_section_start', 'eletheme_register_font', 112, 2 );
add_action( 'elementor/element/column/section_typo/after_section_start', 'eletheme_register_font', 112, 2 );
