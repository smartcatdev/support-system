<?php

namespace SmartcatSupport\util;

interface Migration {
    public function version();
    public function migrate();
}