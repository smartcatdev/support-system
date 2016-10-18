<?php

namespace SmartcatSupport\admin;

use SmartcatSupport\ActionListener;

/**
 * Abstract base class for meta classes, automatically registers required actions
 * and callback functions for displaying and aving meta boxes.
 */
abstract class MetaBox extends ActionListener {
    protected $id;
    protected $title;
    protected $post_type;
    protected $context;
    protected $priority;
    
    public function __construct( $id, $title, $post_type, $context = 'advanced', $priority = 'default' ) {
        $this->set_id( $id )
            ->set_title( $title )
            ->set_post_type( $post_type )
            ->set_context( $context );
     
        $this->add_action( 'add_meta_boxes_' . $post_type, 'install' );
        $this->add_action( 'save_post', 'save', 10, 2 );
    }
    
    public function get_id() {
        return $this->id;
    }

    public function get_title() {
        return $this->title;
    }

    public function get_post_type() {
        return $this->post_type;
    }

    public function get_context() {
        return $this->context;
    }

    public function get_priority() {
        return $this->priority;
    }

    public function set_id( $id ) {
        $this->id = $id;
        return $this;
    }

    public function set_title( $title ) {
        $this->title = $title;
        return $this;
    }

    public function set_post_type( $post_type ) {
        $this->post_type = $post_type;
        return $this;
    }

    public function set_context( $context ) {
        $this->context = $context;
        return $this;
    }

    public function set_priority( $priority ) {
        $this->priority = $priority;
        return $this;
    }
    
    public function install() {
        add_meta_box(
            $this->id,
            $this->title,
            [ $this, 'render' ],
            $this->post_type,
            $this->context,
            $this->priority
        );
    }
    
    public function uninstall() {
        remove_meta_box( $this->id, $this->post_type, $this->context );
    }
    
    public abstract function render( $post );
    public abstract function save( $post_id, $post );   
}
