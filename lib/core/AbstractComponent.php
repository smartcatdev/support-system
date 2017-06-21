<?php

namespace smartcat\core;

if( !class_exists( '\smartcat\core\AbstractComponent' ) ) :

/**
 * A single component of a plugin that is instantiated automatically on plugin init.
 *
 * @deprecated
 * @package smartcat\core
 * @author Eric Green <eric@smartcat.ca>
 */
abstract class AbstractComponent implements Component, HookSubscriber {
    protected $plugin;
    protected $db;

    /**
     * Called after the Component is instantiated
     *
     * @param AbstractPlugin $plugin The main plugin instance.
     */
    public function init( AbstractPlugin $plugin ) {
        $this->plugin = $plugin;
        $this->plugin->add_api_subscriber( $this );
        $this->db = $plugin->db();
    }

    /**
     * Convenience method called after all components have loaded.
     */
    public function start() {}


    /**
     * Get the Plugin that instantiated the Component.
     *
     * @return Plugin The main plugin instance.
     */
    public function plugin() {
        return $this->plugin;
    }

    public function subscribed_hooks() {
        return array();
    }
}

endif;