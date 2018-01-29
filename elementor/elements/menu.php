<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_Menu extends Widget_Base {

	public function get_name() {
		return 'elt-menu';
	}

	public function get_title() {
		return __( 'Menu', 'elementor' );
	}

	public function get_icon() {
		return 'fa fa-bars';
	}


    public function get_categories() {
		return [ 'eletemplator' ];
	}




	protected function _register_controls() {
		/* start part icon list*/
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Menu List', 'elementor' ),
			]
		);

		$this->add_control(
			'menu_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'text' => __( 'Home', 'elementor' ),
						'icon' => 'fa fa-home',
						'link' => array('url' => '/',),
					],
					[
						'text' => __( 'About Us', 'elementor' ),
						'icon' => 'fa fa-times',
						'link' => array('url' => '#',),
					],
					[
						'text' => __( 'Services', 'elementor' ),
						'icon' => 'fa fa-dot-circle-o',
						'link' => array('url' => '#',),
					],
					[
						'text' => __( 'Contact', 'elementor' ),
						'icon' => 'fa fa-envelope',
						'link' => array('url' => '#',),
					],
				],
				'fields' => [
					[
						'name' => 'text',
						'label' => __( 'Text', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Menu Item', 'elementor' ),
						'default' => __( 'Menu Item', 'elementor' ),
					],
					[
						'name' => 'icon',
						'label' => __( 'Icon', 'elementor' ),
						'type' => Controls_Manager::ICON,
						'label_block' => true,
						'default' => '',
					],
					[
						'name' => 'link',
						'label' => __( 'Link', 'elementor' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'default' => ['url' => '#',],
						'placeholder' => __( 'http://your-link.com', 'elementor' ),
					],
				],
				'title_field' => '<i class="{{ icon }}"></i> {{{ text }}}',
			]
		);


		$this->add_responsive_control(
			'view',
			[
				'label' => __( 'All Menu Items Per Line', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'inline-block',
				'options' => [
					'inline-block' => [
						'title' => __( 'In line', 'elementor' ),
						'icon' => 'fa fa-arrows-h',
					],
					'block' => [
						'title' => __( 'Per row', 'elementor' ),
						'icon' => 'fa fa-arrows-v',
					],
				],
				'description' => __( 'Check if you want all menu items per line', 'elementor' ),
				'selectors' => [
					'{{WRAPPER}} .eletheme-menu-list-item' => 'display: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eletheme-menu-list-items' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

/* start part button */

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Menu Link Style', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'background_menu_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.eletheme-menu-link',
			]
		);

		$this->start_controls_tabs( 'tabs_meniu_style' );

		$this->start_controls_tab(
			'tab_meniu_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'meniu_text_color',
			[
				'label' => __( 'Menu Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link' => 'background-color: {{VALUE}};',
				],
			]
		);



		$this->add_responsive_control(
			'side_margins',
			[
				'label' => __( 'Menu Item Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} a.eletheme-menu-link' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'elementor' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .eletheme-menu-link',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_padding',
			[
				'label' => __( 'Text Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_meniu_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Hover Menu Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meniu_background_hover_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meniu_hover_border_color',
			[
				'label' => __( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_side_margins',
			[
				'label' => __( 'Hover Menu Item Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link:hover' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} a.eletheme-menu-link:hover' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_hover',
				'label' => __( 'Hover Border', 'elementor' ),
				'placeholder' => '1px',

				'selector' => '{{WRAPPER}} .eletheme-menu-link:hover',
			]
		);

		$this->add_control(
			'hover_border_radius',
			[
				'label' => __( 'Hover Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_text_padding',
			[
				'label' => __( 'Hover Text Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.eletheme-menu-link:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();
		/* icon style*/
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eletheme-menu-list-icon i' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => __( 'Icon Color Hover', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a:hover .eletheme-menu-list-icon i' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eletheme-menu-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
/* end part icon style */

	}

	protected function render() {
		$settings = $this->get_settings();
		?>
		<ul class="eletheme-menu-list-items">
			<?php foreach ( $settings['menu_list'] as $item ) : ?>
				<li class="eletheme-menu-list-item" >
					<?php
					if ( ! empty( $item['link']['url'] ) ) {
						$target = $item['link']['is_external'] ? ' target="_blank"' : '';

						echo '<a class="eletheme-menu-link" href="' . $item['link']['url'] . '"' . $target . '>';
					}

					if ( $item['icon'] ) : ?>
						<span class="eletheme-menu-list-icon">
							<i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
						</span>
					<?php endif; ?>
					<span class="eletheme-menu-list-text"><?php echo $item['text']; ?></span>
					<?php
					if ( ! empty( $item['link']['url'] ) ) {
						echo '</a>';
					}
					?>
				</li>
				<?php
			endforeach; ?>
		</ul>
		<?php
	}

	protected function _content_template() {
		?>
		<ul class="eletheme-menu-list-items active">
			<#
			if ( settings.menu_list ) {
				_.each( settings.menu_list, function( item ) { #>
					<li class="eletheme-menu-list-item">
						<# if ( item.link && item.link.url ) { #>
							<a class="eletheme-menu-link"  href="{{ item.link.url }}">
						<# } #>
						<span class="eletheme-menu-list-icon">
							<i class="{{ item.icon }}"></i>
						</span>
						<span class="eletheme-menu-list-text">{{{ item.text }}}</span>
						<# if ( item.link && item.link.url ) { #>
							</a>
						<# } #>
					</li>
				<#
				} );
			} #>
		</ul>
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Menu() );
