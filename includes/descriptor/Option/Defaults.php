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

    const TICKET_CREATE_SUCCESS_MSG = 'We\'ve received your request for support and an agent will get back to you soon';

    const TICKETS_TAB_LABEL = 'Tickets';

    const CREATE_TICKET_BTN_TEXT = 'Create Ticket';

    const SAVE_TICKET_BTN_TEXT = 'Save Ticket';

    const EMPTY_TABLE_MSG = 'There are no tickets yet';

    const SUBJECT_LABEL = 'Subject';
    const SUBJECT_ERR = 'Subject cannot be blank';

    const CONTENT_LABEL = 'Details';
    const CONTENT_ERR = 'Description cannot be blank';

    const FIRST_NAME_LABEL = 'First Name';
    const FIRST_NAME_ERR = 'First name cannot be blank';

    const LAST_NAME_LABEL= 'Last Name';
    const LAST_NAME_ERR = 'Last name cannot be blank';

    const EMAIL_LABEL = 'Email Address';
    const EMAIL_ERR = 'Email cannot be blank';

    const STATUS_LABEL = 'Status';
    const STATUS_ERR = 'Invalid Status';

    const ASSIGNED_LABEL = 'Assigned To';
    const ASSIGNED_ERR = 'Invalid Agent';
}
