<?php

namespace SmartcatSupport\Ticket;

final class Meta {
    const DATE_OPENED = 'date_opened';
    const ASSIGNED_TO = 'assigned_to';
    const EMAIL_ADDRESS = 'email_address';
    
    const STATUS = 'status';
    const STATUS_VALUES = [ 
        'new'           => 'New', 
        'in_progress'   => 'In Progress', 
        'resolved'      => 'Resolved', 
        'follow_up'     => 'Follow Up', 
        'closed'        => 'Closed'
    ]; 
}
