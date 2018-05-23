<?php

namespace smartcat\core;
/**
 * Interface Migration
 * @deprecated
 * @package smartcat\core
 */
interface Migration {
    public function version();
    public function migrate( $plugin );
}