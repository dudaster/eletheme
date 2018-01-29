<?php
namespace Elementor;

function eletheme_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'eletemplator',
        [
            'title'  => 'Eletemplator',
            'icon' => 'font'
        ],
        1
    );
}
add_action('elementor/init','Elementor\eletheme_elementor_init');