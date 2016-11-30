<?php

namespace SmartcatSupport\admin;

use SmartcatSupport\util\ActionListener;

/**
 * Abstract base class for meta classes, automatically registers required actions
 * and callback functions for displaying and saving meta boxes.
 * 
 * @abstract
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
abstract class MetaBox extends ActionListener {
    /**
     * @var string The metabox ID.
     * @access protected
     * @since 1.0.0
     */
    protected $id;
    
    /**
     * @var string The metabox title.
     * @access protected
     * @since 1.0.0
     */
    protected $title;
    
    /**
     * @var string The associated post type.
     * @access protected
     * @since 1.0.0 
     */
    protected $post_type;
    
    /**
     * @var string The context where the metabox should display.
     * @access protected
     * @since 1.0.0 
     */
    protected $context;
    
    /**
     * @var string The priority of the metabox.
     * @access protected
     * @since 1.0.0 
     */
    protected $priority;
    
    /**
     * @param string $id        The ID of the metabox.
     * @param string $title     The title to display above the metabox.
     * @param string $post_type The type of post to display the metabox on.
     * @param string $context   When to display the metabox.
     * @param string $priority  The order in which the meta box should appear.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function __construct( $id, $title, $post_type, $context = 'advanced', $priority = 'default' ) {
        $this->set_id( $id )
            ->set_title( $title )
            ->set_post_type( $post_type )
            ->set_context( $context );
     
        $this->add_action( 'add_meta_boxes_' . $post_type, 'install' );
        $this->add_action( 'save_post', 'save', 10, 2 );
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
        
    // <editor-fold defaultstate="collapsed" desc="Getters / Setters">
    /**
     * Gets the ID.
     * 
     * @return string The metabox ID.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Gets the title.
     * 
     * @return string The metabox title.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Gets the post type.
     * 
     * @return string The post associated post type.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function get_post_type() {
        return $this->post_type;
    }

    /**
     * Gets the context.
     * 
     * @return string The display context.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function get_context() {
        return $this->context;
    }

    /**
     * Gets the display priority.
     * 
     * @return string The metabox display priority.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function get_priority() {
        return $this->priority;
    }

    /**
     * Sets the ID.
     * 
     * @param string $id The ID of the metabox.
     * @return \SmartcatSupport\admin\MetaBox The metabox.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function set_id( $id ) {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the title.
     * 
     * @param string $title The metabox title.
     * @return \SmartcatSupport\admin\MetaBox The metabox.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function set_title( $title ) {
        $this->title = $title;
        return $this;
    }

    /**
     * Sets the post type.
     * 
     * @param type $post_type The associated post type.
     * @return \SmartcatSupport\admin\MetaBox The metabox.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function set_post_type( $post_type ) {
        $this->post_type = $post_type;
        return $this;
    }

    /**
     * Sets the context.
     * 
     * @param type $context The display context.
     * @return \SmartcatSupport\admin\MetaBox The metabox.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function set_context( $context ) {
        $this->context = $context;
        return $this;
    }

    /**
     * Sets the priority.
     * 
     * @param type $priority The display priority.
     * @return \SmartcatSupport\admin\MetaBox The metabox.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function set_priority( $priority ) {
        $this->priority = $priority;
        return $this;
    }
// </editor-fold>
}
