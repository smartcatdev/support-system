<?php

namespace SmartcatSupport\admin;

use smartcat\core\AbstractComponent;

class Reports extends AbstractComponent {

    public function start() {
        $this->plugin->add_api_subscriber( new ReportsMenuPage() );
    }

}