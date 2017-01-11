<?php

namespace smartcat\post;

if( !class_exists( 'smartcat\post\MetaBox' ) ) :

/**
 * Abstract base class for meta classes, automatically registers required actions
 * and callback functions for displaying and saving meta boxes.
 * 
 * @abstract
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
abstract class MetaBox {
    /**
     * @var string The metabox ID.
     * @since 1.0.0
     */
    public $id;
    
    /**
     * @var string The metabox title.
     * @since 1.0.0
     */
    public $title;
    
    /**
     * @var string The associated post type.
     * @since 1.0.0 
     */
    public $post_type;
    
    /**
     * @var string The context where the metabox should display.
     * @since 1.0.0 
     */
    public $context;
    
    /**
     * @var string The priority of the metabox.
     * @since 1.0.0
     */
    public $priority;
    
    /**
     * @param string $id        The ID of the metabox.
     * @param string $title     The title to display above the metabox.
     * @param string $post_type The type of post to display the metabox on.
     * @param string $context   When to display the metabox.
     * @param string $priority  The order in which the meta box should appear.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function __construct( array $args ) {
        $this->id = $args['id'];
        $this->title = $args['title'];
        $this->post_type = $args['post_type'];
        $this->context = $args['context'];
     
        add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'install' ) );
        add_action( 'save_post', array( $this, 'save' ), 10, 2 );
    }

    /**
     * Registers the metabox with WordPress.
     * 
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function install() {
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
    public function uninstall() {
        remove_meta_box( $this->id, $this->post_type, $this->context );
    }
    
    /**
     * Callback called by WordPress when the metabox is to be outputted.
     * 
     * @abstract
     * @param WP_Post $post The post object that the metabox gets its data from.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public abstract function render( $post );
    
    /**
     * Callback called by WordPress when the metabox is to be saved.
     * 
     * @abstract
     * @param int $post_id The ID of the post to be saved.
     * @param WP_Post $post The post object that contains the data to be saved.
     */
    public abstract function save( $post_id, $post );
}

endif;