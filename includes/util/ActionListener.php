<?php

namespace SmartcatSupport\util;

/**
 * Provides inherritable implementations for classes to register themeselves as
 * listeners of WordPress events.
 * 
 * @abstract
 * @since 1.0.0
 * @package abstracts
 * @author Eric Green <eric@smartcat.ca>
 */
abstract class ActionListener {
    
    /**
     * @var array The object's currently subscribed events.
     * @access protected
     * @since 1.0.0
     */
    protected $events = [];

    /**
     * Register the object with a WordPress event.
     * 
     * @param string $event The name of the event to listen for.
     * @param string $callback The name of the function to call.
     * @param int    $priority When the function should be called.
     * @param int    $argsc The number of arguments the function accepts.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function add_action( $event, $callback, $priority = 10, $argsc = 1 ) {
        add_filter( $event, [ $this, $callback ], $priority, $argsc );
        
        $this->events[ $event ] = $callback;
    }
    
    /**
     * Unregister an object from a WordPress event.
     * 
     * @param string $event The name of the event to stop listening for.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function remove_action( $event ) {
        if( array_key_exists( $event , $this->subscribed_events ) ) {
            remove_filter( $event, [ $this, $this->events[ $event ] ] );
            
            unset( $events[ $event ] );
        }
    }
    
    /**
     * Register an object to listent to WordPress events called by AJAX requests.
     * 
     * @param string  $event The name of the event to listen for.
     * @param string  $callback The name of the function to call.
     * @param boolean $priv Whether or not this action can be called from the front-end.
     * @param int     $priority When the function should be called.
     * @param int     $argsc The number of arguments the function accepts.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function add_ajax_action( $event, $callback, $priv = true, $priority = 10, $argsc = 1 ) {
        $this->add_action( $this->prefix_ajax( $event, $priv ), $callback, $priority, $argsc );
    }
    
    /**
     * Unregister an object from an AJAX WordPress event.
     * 
     * @param string $event The name of the event to stop listening for.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function remove_ajax_action( $event, $priv = true ) {
        $this->remove_action( $this->prefix_ajax( $event, $priv ) );
    }
    
    /**
     * Prefix an AJAX event as either privileged or unprivileged.
     * 
     * @param string  $event The name of the event to stop listening for.
     * @param boolean $priv Whether or not the action can be called from the front-end.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    private function prefix_ajax( $event, $priv ) {
        return ( $priv ? 'wp_ajax_' : 'wp_ajax_no_priv_' ) . $event;
    }
}
