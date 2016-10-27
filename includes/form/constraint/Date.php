<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SmartcatSupport\form\constraint;

/**
 * Description of Date
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class Date extends Constraint {
    public function is_valid( $value ) {
        return date_create( $value ) !== false;
    }
}
