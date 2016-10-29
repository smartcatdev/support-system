<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SmartcatSupport\form\constraint;

/**
 * Description of Seclection
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class Choice implements Constraint {
    protected $options = [];
    
    public function __construct( array $options ) {
        $this->options = $options;
    }
    
    public function is_valid( $value ) {
        return in_array( $value, $this->options );
    }
}
