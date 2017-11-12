<?php

namespace smartcat\post;

if( !class_exists('smartcat\post\AbstractMetaBox') ) :

/**
 * Abstract base class for meta classes, automatically registers required actions
 * and callback functions for displaying and saving meta boxes.
 * 
 * @abstract
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
abstract class AbstractMetaBox {

    public $id;
    public $title;
    public $post_type;
    public $context;
    public $priority;
    
    /**
     * @param array $args
     *  $args = [
     *      'id'            => (string) The slug id of the metabox. Required.
     *      'title'         => (string) The title to display on the metabox. Required.
     *      'post_type'     => (string) The post type where the metabox should be displayed. Required.
     *      'context'       => (string) The type of settings page. Default: advanced.
     *      'priority'      => (int)    The priority in which the metabox should display. Default: default.
     *    ]
     *
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function __construct( array $args ) {
        $this->id = $args['id'];
        $this->title = $args['title'];
        $this->post_type = $args['post_type'];

        if( !empty( $args['context'] ) ) {
            $this->context = $args['context'];
        }

        if( !empty( $args['priority'] ) ) {
            $this->priority['priority'];
        }

        $this->init();
    }

    /**
     * Registers the metabox with WordPress.
     * 
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function add() {
        add_meta_box(
            $this->id,
            $this->title,
            array( $this, 'render' ),
            $this->post_type,
            $this->context,
            $this->priority
        );
    }
    
    /**
     * Stops the metabox from being displayed.
     * 
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
         */
        public function remove() {
        remove_meta_box( $this->id, $this->post_type, $this->context );
    }

    private function init() {
        add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'add' ) );
        add_action( "save_post_{$this->post_type}", array( $this, 'save' ), 10, 2 );
    }

    /**
     * Callback called by WordPress when the metabox is to be outputted.
     * 
     * @abstract
     * @param \WP_Post $post The post object that the metabox gets its data from.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public abstract function render( \WP_Post $post );
    
    /**
     * Callback called by WordPress when the metabox is to be saved.
     * 
     * @abstract
     * @param int $post_id The ID of the post to be saved.
     * @param \WP_Post $post The post object that contains the data to be saved.
     */
    public abstract function save( $post_id, \WP_Post $post );
}

endif;