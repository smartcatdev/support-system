<?php

namespace smartcat\core;

if( !class_exists( '\smartcat\core\AbstractComponent' ) ) :

/**
 * A single component of a plugin that is instantiated automatically on plugin init.
 *
 * @package smartcat\core
 * @author Eric Green <eric@smartcat.ca>
 */
abstract class AbstractComponent implements Component {
    protected $plugin;

    /**
     * Called after the Component is instantiated
     *
     * @param Plugin $plugin The main plugin instance.
     */
    public function init( Plugin $plugin ) {
        $this->plugin = $plugin;
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
}

endif;