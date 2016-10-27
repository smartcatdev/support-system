<?php

namespace SmartcatSupport\descriptor\Option;

/**
 * Default values for options. Correspond to their keys in desc\Option.
 * 
 * @since 1.0.0
 * @package desc
 * @author Eric Green <eric@smartcat.ca>
 */
final class Defaults {
    
    /**
     * @since 1.0.0
     */
    const NUKE = 0;

    const STATUSES = [
        'new'           => 'New',
        'in_progress'   => 'In Progress',
        'resolved'      => 'Resolved',
        'follow_up'     => 'Follow Up',
        'closed'        => 'Closed'
    ];
}
