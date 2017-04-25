<?php

namespace smartcat\core;

interface Migration {
    public function version();
    public function migrate( $plugin );
}