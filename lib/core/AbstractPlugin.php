<?php

namespace smartcat\core;



if( !class_exists( '\smartcat\core\AbstractPlugin' ) ) :

/**
 * Base class that initializes a plugin in its components.
 * @deprecated
 * @package \smartcat\core
 * @author Eric Green <eric@smartcat.ca>
 */
abstract class AbstractPlugin implements HookRegisterer, HookSubscriber, Plugin {
    protected $url;
    protected $dir;
    protected $file;
    protected $id;
    protected $version;
    protected $cache = array();
    protected $db;

    private static $plugins_loaded = array();

    protected function __construct( $id, $version, $fs_context ) {
        $this->dir = plugin_dir_path( $fs_context );
        $this->url = plugin_dir_url( $fs_context );
        $this->id = $id;
        $this->file = $fs_context;
        $this->version = $version;

        $this->add_api_subscriber( $this );
    }

    /**
     * Start the Plugin and initialize each of its Components.
     *
     * @param string $name
     * @param string $version
     * @param string $fs_context
     */
    public static final function boot( $name, $version, $fs_context ) {
        if( !array_key_exists( $name, self::$plugins_loaded ) ) {
            $instance = new static( $name, $version, $fs_context );
            $instance->db = $GLOBALS['wpdb'];

            self::$plugins_loaded[ $name ] = $instance;

            register_activation_hook( $fs_context, array( $instance, 'activate' ) );
            register_deactivation_hook( $fs_context, array( $instance, 'deactivate' ) );

            add_action( 'plugins_loaded', array( $instance, 'start' ) );

            /**
             * Encapsulate the component loader code.
             */
            add_action( 'plugins_loaded', function () use ( $instance ) {

                foreach( $instance->components() as $class ) {
                    if( is_a( $class, Component::class, true ) ) {
                        $component = new $class();
                        $component->init( $instance );

                        add_action( $instance->id . '_components_loaded', array( $component, 'start' ) );
                    } else {
                        throw new \Exception( $class .' Does not comply with interface ' . Component::class );
                    }
                }

                do_action( $instance->id . '_components_loaded' );
                do_action( $instance->id . '_loaded' );

            } );
        }
    }

    public static final function plugin_dir( $plugin ) {
        return array_key_exists( $plugin, self::$plugins_loaded ) ? self::$plugins_loaded[ $plugin ]->dir : null;
    }

    public static final function plugin_url( $plugin ) {
        return array_key_exists( $plugin, self::$plugins_loaded ) ? self::$plugins_loaded[ $plugin ]->url : null;
    }

    public static final function get_plugin( $plugin ) {
        return array_key_exists( $plugin, self::$plugins_loaded ) ? self::$plugins_loaded[ $plugin ] : null;
    }

    /**
     * Register the callbacks of an event listener with the Plugin API.
     *
     * @deprecated
     * @param HookSubscriber $listener
     */
    public function add_api_subscriber( HookSubscriber $listener ) {
        foreach( $listener->subscribed_hooks() as $hook => $params ) {
            if( is_string( $params ) ) {
                add_filter( $hook, array( $listener, $params ) );
            } elseif( is_array( $params ) ) {
                add_filter( $hook, array( $listener, $params[0] ), isset( $params[1] ) ? $params[1] : 10, isset( $params[2] ) ? $params[2] : 1 );
            }
        }
    }

    /**
     * Unregister the callbacks of an event listener from the Plugin API.
     *
     * @deprecated
     * @param HookSubscriber $listener
     */
    public function remove_api_subscriber( HookSubscriber $listener ) {
        foreach( $listener->subscribed_hooks() as $hook => $params ) {
            if( is_string( $params ) ) {
                remove_filter( $hook, array( $listener, $params ) );
            } elseif( is_array( $params ) ) {
                remove_filter( $hook, array( $listener, $params[0] ), isset( $params[1] ) ? $params[1] : 10 );
            }
        }
    }

    public function perform_migrations() {

        // If we're not already in an upgrade
        if( !get_transient( $this->id . '_doing_upgrade' ) ) {

            $current = get_option( $this->id . '_version', 0 );
            $result = true;

            // If the version in code > version in database
            if( $this->version > $current ) {

                // Prevent multiple migrations from running at the same time
                set_transient( $this->id . '_doing_upgrade', true, 60 * 2 /* Allow 2 minutes for migration */ );

                // Perform migrations with the current version
                foreach ( glob( $this->dir . 'migrations/migration-*.php' ) as $file ) {
                    $migration = include_once( $file );
                    $version = $migration->version();

                    if ( $version > $current && $version <= $this->version ) {
                        $result = $migration->migrate( $this );

                        if ( $result === false || is_wp_error( $result ) ) {
                            break;
                        }
                    }
                }

                if ( $result !== false || !is_wp_error( $result ) ) {

                    // Let everyone know we're done
                    do_action( $this->id . '_upgraded' );

                    // Increment the version number
                    update_option( $this->id . '_version', $this->version );
                }

                // Unblock migrations now that we're all done
                delete_transient( $this->id . '_doing_upgrade' );

            }
        }

    }

    /**
     * The list of Components to instantiate.
     *
     * @deprecated
     * @return array
     */
    protected function components() {
        return array();
    }

    /**
     * Get and attribute from the cache.
     *
     * @param $name
     * @return mixed|null
     */
    public function __get( $name ) {
        return array_key_exists( $name, $this->cache ) ? $this->cache[ $name ] : null;
    }

    /**
     * Set an attribute in the cache.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set( $name, $value ) {
        $this->cache[ $name ] = $value;
    }

    public function __isset( $name ) {
        return array_key_exists( $name, $this->cache );
    }

    public function __unset( $name ) {
        if( isset( $this->cache[ $name ] ) ) {
            unset( $this->cache[ $name ] );
        }
    }

    /**
     * Instances of AbstractPlugin are singleton and should not be cloned.
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, 'AbstractPlugin cannot be cloned', '4.7' );
    }

    public function dir() {
        return $this->dir;
    }

    public function url() {
        return $this->url;
    }

    public function id() {
        return $this->id;
    }

    public function file() {
        return $this->file;
    }

    public function version() {
        return $this->version;
    }

    public function db() {
        return $this->db;
    }

    public function subscribed_hooks( $hooks = array() )  {
        return array_merge( array( 'init' => array( 'perform_migrations' ) ), $hooks );
    }

    /**
     * Convenience method called after plugins_loaded.
     */
    public function start() {}

    /**
     * Plugin activation callback.
     */
    public function activate() {}

    /**
     * Plugin deactivation callback.
     */
    public function deactivate() {}
}

endif;