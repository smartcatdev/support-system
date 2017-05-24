<?php

namespace smartcat\admin;

if( !class_exists( 'smartcat/admin/MenuTabPage' ) ) :

abstract class MenuPageTab {

    public $title;
    public $slug;
    public $page;

    public function __construct( array $args ) {
        $this->title = $args['title'];
        $this->slug = $args['slug'];
    }

    public abstract function render();

}

endif;