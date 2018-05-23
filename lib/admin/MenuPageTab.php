<?php

namespace smartcat\admin;

if( !class_exists( 'smartcat/admin/MenuTabPage' ) ) :
    /**
     * Class MenuPageTab
     * @deprecated
     * @package smartcat\admin
     */
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