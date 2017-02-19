<?php

namespace SmartcatSupport\ajax;


use smartcat\core\AbstractComponent;

abstract class AjaxComponent extends AbstractComponent {


    protected function render( $template, array $data = array() ) {
        extract( $data );
        ob_start();

        include( $template );

        return ob_get_clean();

    }
}
