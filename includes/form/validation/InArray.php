<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SmartcatSupport\form\validation;

/**
 * Description of Seclection
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class InArray extends Constraint {
    protected $options = [];
    
    public function __construct( $message = '', array $options ) {
        parent::__construct( $message );
        $this->options = $options;
    }
    
    public function is_valid( $value ) {
        return array_key_exists( $value, $this->options );
    }
}
