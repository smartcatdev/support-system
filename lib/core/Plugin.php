<?php

namespace smartcat\core;

if( !interface_exists( '\smartcat\core\Plugin' ) ) :

/**
 * Represents the main instance of a Plugin.
 *
 * @package smartcat\core
 * @author Eric Green <eric@smartcat.ca>
 */
interface Plugin {

    /**
     * Called from plugin.php to initiate the boot process of the plugin.
     *
     * @param string $name
     * @param string $version
     * @param string $file
     */
    public static function boot( $name, $version, $file );

    /**
     * Callback for plugin activation.
     */
    public function activate();

    /**
     * Callback for plugin deactivation.
     */
    public function deactivate();

    /**
     * @return string The Url of the plugin directory.
     */
    public function url();

    /**
     * @return string The path to the plugin directory.
     */
    public function dir();

    /**
     * @return string The name of the plugin.
     */
    public function name();

    /**
     * @return string The version of the plugin.
     */
    public function version();
}

endif;
