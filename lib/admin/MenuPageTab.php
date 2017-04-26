<?php

namespace smartcat\admin;

if( !class_exists( 'smartcat/admin/MenuTabPage' ) ) :

abstract class MenuPageTab {

    public $title;

    public function __construct( $title ) {
        $this->title = $title;
    }

    public abstract function render();
}

endif;