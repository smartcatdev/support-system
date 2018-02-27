<?php
/**
 * Plugin Name: Smartcat Plugin Marketing
 * Author: Smartcat
 * Version: 1.0.0
 *
 * @since 1.0.0
 * @package global
 */
if ( !class_exists( 'SC_PluginMarketing' ) ) :

    /**
     * Plugin for adding remotely adding notifications to the WordPress admin.
     *
     * @singleton
     * @since 1.0.0
     */
    class SC_PluginMarketing {

        /**
         * Plugin operation mode.
         *
         * @since 1.0.0
         */
        const MODE_PLUGIN = 'plugin';

        /**
         * Embedded library operation mode.
         *
         * @since 1.0.0
         */
        const MODE_EMBEDDED = 'embedded';

        /**
         * The operating mode of the plugin
         *
         * @since 1.0.0
         * @var string
         */
        protected static $mode = self::MODE_EMBEDDED;

        /**
         * Whether or not set_mode() has been called.
         *
         * @since 1.0.0
         * @var bool
         */
        protected static $mode_set = false;

        /**
         * The plugin instance.
         *
         * @since 1.0.0
         * @var null|SC_PluginMarketing
         */
        protected static $instance = null;

        /**
         * The current delegate function of the plugin.
         *
         * @since 1.0.0
         * @var null
         */
        protected $function = null;

        /**
         * Prevent calling __construct()
         *
         * @since 1.0.0
         */
        private function __constructor() {}

        /**
         * Get the plugin instance.
         *
         * @param mixed $config
         *
         * @since 1.0.0
         * @return SC_PluginMarketing
         */
        public static function instance( $config = '' ) {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
                self::$instance->initialize( $config );
            }
            return self::$instance;
        }

        /**
         * Set the plugin operation mode.
         *
         * @param string $mode
         *
         * @internal
         * @since 1.0.0
         * @return void
         */
        public static function _set_mode( $mode ) {
            if ( !self::$mode_set && in_array( $mode, array( self::MODE_PLUGIN, self::MODE_EMBEDDED ) ) ) {
                self::$mode = $mode;
                self::$mode_set = true;
            }
        }

        /**
         * Initializes the plugin.
         *
         * @param string|array $config
         *
         * @since 1.0.0
         * @return void
         */
        public function initialize( $config = '' ) {
            if ( self::$mode === self::MODE_PLUGIN ) {
                $this->function = new SC_MarketingFunctionPlugin();
            } else {
                $this->function = new SC_MarketingFunctionEmbedded( $config );
            }
        }

    }

    /**
     * Get the plugin instance.
     *
     * @action plugins_loaded
     *
     * @param mixed $config
     *
     * @since 1.0.0
     * @return SC_PluginMarketing
     */
    function sc_plugin_marketing( $config = '' ) {
        return SC_PluginMarketing::instance( $config );
    }

    /**
     * Load the plugin.
     *
     * @since 1.0.0
     */
    function sc_plugin_marketing_init() {
        if ( !in_array( __FILE__, wp_get_active_and_valid_plugins() ) ) {
            return;
        }

        SC_PluginMarketing::_set_mode( SC_PluginMarketing::MODE_PLUGIN ); // Set the mode to plugin if active

        // Boot the plugin
        add_action( 'plugins_loaded', 'sc_plugin_marketing' );
    }

    if ( defined( 'ABSPATH' ) ) {
        sc_plugin_marketing_init(); // Check environment early
    }

endif;


if ( !class_exists( 'SC_MarketingFunctionPlugin' ) ) :
/**
 * Handles plugin functionality when running in plugin mode.
 *
 * @since 1.0.0
 */
class SC_MarketingFunctionPlugin {

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the plugin.
     *
     * @since 1.0.0
     * @return void
     */
    protected function init() {
        add_action( 'init', array( $this, 'register_post_type' ) );
    }

    /**
     * Register the message post type.
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Marketing Messages', 'Post Type General Name' ),
            'singular_name'         => _x( 'Marketing Message', 'Post Type Singular Name' ),
            'menu_name'             => __( 'Plugin Marketing' ),
            'name_admin_bar'        => __( 'Marketing Message' ),
            'archives'              => __( 'Item Archives' ),
            'parent_item_colon'     => __( 'Parent Item:' ),
            'all_items'             => __( 'All Messages' ),
            'add_new_item'          => __( 'Create Message' ),
            'add_new'               => __( 'Create Message' ),
            'new_item'              => __( 'Create Message' ),
            'edit_item'             => __( 'Edit Message' ),
            'update_item'           => __( 'Update Message' ),
            'view_item'             => __( 'View Message' ),
            'search_items'          => __( 'Search Message' ),
            'not_found'             => __( 'Message Not found' ),
            'not_found_in_trash'    => __( 'Message Not found in Trash' ),
            'featured_image'        => __( 'Featured Image' ),
            'set_featured_image'    => __( 'Set featured image' ),
            'remove_featured_image' => __( 'Remove featured image' ),
            'use_featured_image'    => __( 'Use as featured image' ),
            'insert_into_item'      => __( 'Insert into message' ),
            'uploaded_to_this_item' => __( 'Uploaded to this message' ),
            'items_list'            => __( 'Messages list' ),
            'items_list_navigation' => __( 'Messages list navigation' ),
            'filter_items_list'     => __( 'Filter messages list' )
        );
        $args = array(
            'labels'               => $labels,
            'description'          => __( 'Remotely managed marketing messages for plugins' ),
            'supports'             => array( 'editor', 'title' ),
            'hierarchical'         => false,
            'public'               => false,
            'show_ui'              => true,
            'show_in_menu'         => true,
            'menu_position'        => 10,
            'menu_icon'            => 'dashicons-megaphone',
            'show_in_admin_bar'    => false,
            'show_in_nav_menus'    => false,
            'can_export'           => true,
            'has_archive'          => false,
            'exclude_from_search'  => true,
            'publicly_queryable'   => false,
            'capability_type'      => 'post',
            'feeds'                => null,
            'show_in_rest'         => true,
            'rest_base'            => 'marketing-messages'
        );

        register_post_type( 'marketing_message', $args );
    }

}

endif;


if ( !class_exists( 'SC_MarketingFunctionEmbedded' ) ) :
/**
 * Handles plugin functionality when running in embedded mode.
 *
 * @since 1.0.0
 */
class SC_MarketingFunctionEmbedded {

    /**
     * The transient key for the messages cache.
     *
     * @since 1.0.0
     */
    const TRANSIENT = 'sc_marketing_messages_cache';

    /**
     * The timeout period for the messages cache.
     *
     * @since 1.0.0
     */
    const CACHE_TTL = 86400;

    /**
     * The url of the server to pull messages from.
     *
     * @since 1.0.0
     * @var string
     */
    public $url = '';

    /**
     * Cached messages from the API.
     *
     * @since 1.0.0
     * @var array
     */
    public $cache = array();

    /**
     * Constructor.
     *
     * @param string|array $args
     *
     * @since 1.0.0
     */
    public function __construct( $args = '' ) {
        $default = array(
            'url' => ''
        );

        foreach ( wp_parse_args( $args, $default ) as $key => $value ) {
            if ( in_array( $key, array( 'url' ) ) ) {
                $this->$key = $value;
            }
        }

        $this->cache = get_transient( self::TRANSIENT );
        $this->init();
    }

    /**
     * Initialize the embedded library.
     *
     * @since 1.0.0
     * @return void
     */
    protected function init() {
        add_action( 'admin_init', array( $this, 'update_cache' ) );
        add_action( 'sc_marketing_message', array( $this, 'print_message' ), 10, 3 );
    }

    /**
     * Update the messages cache if the transient has timed out.
     *
     * @since 1.0.0
     * @return void
     */
    public function update_cache() {
        if ( !empty( $this->cache ) ) {
            return;
        }

        $messages = $this->fetch_messages();

        if ( !empty( $messages ) ) {
            $this->cache = $messages;
            set_transient( self::TRANSIENT, $messages, self::CACHE_TTL );
        }
    }

    /**
     * Make a request to the API and fetch all messages.
     *
     * @return bool|string
     */
    protected function fetch_messages() {
        $url = trailingslashit( $this->url ) . '/wp-json/wp/v2/marketing-messages?per_page=100';
        $res = wp_remote_get( $url );

        if ( wp_remote_retrieve_response_code( $res ) !== 200 ) {
            return false;
        }

        return json_decode( wp_remote_retrieve_body( $res ), true );
    }

    /**
     * Output the field for a message
     *
     * @action sc_marketing_message
     *
     * @param mixed           $_       (Unused)
     * @param string          $slug    The slug of the message
     * @param bool            $echo    Whether to echo the return value
     *
     * @since 1.0.0
     * @return false|string
     */
    function print_message( $_ = '', $slug, $echo = true ) {
        if ( !is_array( $this->cache ) ) {
            return false;
        }

        $message = false;

        foreach ( $this->cache as $cached ) {
            if ( isset( $cached['slug'] ) && $cached['slug'] === $slug ) {
                $message = $cached;
                break; // Break early if we found it
            }
        }

        if ( !$message || empty( $message['content'] ) ) {
            return false;
        }

        if ( $echo ) {
            echo sc_escape_marketing_message( $message['content']['rendered'] );
        }

        return $message['content']['rendered'];
    }

}

/**
 * Helper to output message fields.
 *
 * @param string          $slug
 * @param bool            $echo
 *
 * @since 1.0.0
 * @return string
 */
function sc_marketing_message( $slug, $echo = true ) {
    /**
     *
     * @since 1.0.0
     */
    return apply_filters( 'sc_marketing_message', '', $slug, $echo );
}


/**
 * Safely limit tags and attributes to a specific few.
 *
 * @param string content
 *
 * @since 1.0.0
 * @return string
 */
function sc_escape_marketing_message( $content ) {
    $allowed_tags = array(
        'a' => array(
             'id'     => array()
            ,'href'   => array()
            ,'class'  => array()
            ,'style'  => array()
            ,'target' => array()
            ,'title'  => array()
        ),
        'p' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
        ),
        'span' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
             ,'title' => array()
        ),
        'div' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
        ),
        'button' => array(
             'id'     => array()
             ,'class' => array()
             ,'style' => array()
             ,'title' => array()
        ),
        'h1' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
        ),
        'h2' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
        ),
        'h3' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
        ),
        'h4' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
        ),
        'h5' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
        ),
        'h6' => array(
              'id'    => array()
             ,'class' => array()
             ,'style' => array()
        ),
    );

    return wp_kses( $content, $allowed_tags );
}

endif;