<?php

namespace SmartcatSupport\form\constraint;

/**
 * Description of Constraint
 *
 * @author Eric Green <eric@smartcat.ca>
 */
interface Constraint {
    public function is_valid( $value );
}
