<?php

namespace smartcat\core;

if( !interface_exists( '\smartcat\core\Component' ) ) :

/**
 * Represents a single Component of a plugin. Components are auto-instantiated on plugin init
 * and given a reference to the Plugin class. All Components are decoupled from each other and
 * communicate with each other through the WordPress Plugin API.
 *
 * @package smartcat\core
 * @author Eirc Green <eric@smartcat.ca>
 */
interface Component {

    /**
     * Called after the Component is instantiated
     *
     * @param Plugin $plugin The main plugin instance.
     */
    public function init( AbstractPlugin $plugin );

    /**
     * Convenience method called after all components have loaded.
     */
    public function start();

    /**
     * Get the Plugin that instantiated the Component.
     *
     * @return Plugin The main plugin instance.
     */
    public function plugin();
}

endif;