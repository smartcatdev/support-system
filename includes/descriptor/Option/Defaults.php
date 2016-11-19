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

    /**
     * @since 1.0.0
     */
    const STATUSES = array(
        'new'           => 'New',
        'in_progress'   => 'In Progress',
        'resolved'      => 'Resolved',
        'follow_up'     => 'Follow Up',
        'closed'        => 'Closed'
    );

    /**
     * @since 1.0.0
     */
    const WOOCOMMERCE_ACTIVE = false;

    /**
     * @since 1.0.0
     */
    const EDD_ACTIVE = true;

    /**
     * @since 1.0.0
     */
    const TICKET_CREATED_MSG = 'We\'ve received your request for support and an agent will get back to you soon';

    /**
     * @since 1.0.0
     */
    const TICKET_UPDATED_MSG = 'This ticket has been updated';

    /**
     * @since 1.0.0
     */
    const EMPTY_TABLE_MSG = 'There are no tickets yet';

    /**
     * @since 1.0.0
     */
    const CREATE_BTN_TEXT = 'Create Ticket';

    /**
     * @since 1.0.0
     */
    const REPLY_BTN_TEXT = 'Reply';

    /**
     * @since 1.0.0
     */
    const CANCEL_BTN_TEXT = 'Cancel';

    /**
     * @since 1.0.0
     */
    const SAVE_BTN_TEXT = 'Save';
}
