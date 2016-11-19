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

    const STATUSES = array(
        'new'           => 'New',
        'in_progress'   => 'In Progress',
        'resolved'      => 'Resolved',
        'follow_up'     => 'Follow Up',
        'closed'        => 'Closed'
    );

    const WOOCOMMERCE_ACTIVE = false;
    const EDD_ACTIVE = true;

    const TICKET_CREATED_MSG = 'We\'ve received your request for support and an agent will get back to you soon';
    const TICKET_UPDATED_MSG = 'This ticket has been updated';
    const EMPTY_TABLE_MSG = 'There are no tickets yet';

    const CREATE_BTN_TEXT = 'Create Ticket';
    const REPLY_BTN_TEXT = 'Reply';
    const CANCEL_BTN_TEXT = 'Cancel';
    const SAVE_BTN_TEXT = 'Save';
}
