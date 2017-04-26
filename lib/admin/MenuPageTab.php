<?php

namespace smartcat\admin;


abstract class MenuPageTab {

    public $title;

    public function __construct( $title ) {
        $this->title = $title;
    }

    public abstract function render();
}