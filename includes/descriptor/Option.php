<?php

namespace SmartcatSupport\descriptor;

/**
 * Constant keys for use with calls to get_option()
 * 
 * @since 1.0.0
 * @package desc
 * @author Eric Green <eric@smartcat.ca>
 */
final class Option {
    
    /**
     * @since 1.0.0
     */
    const PLUGIN_VERSION = 'ca.smartcat.support.version';
    
    /**
     * @since 1.0.0
     */
    const NUKE = 'ca.smartcat.support.erase';

    const STATUSES = 'ca.smartcat.support.statuses';

    const TICKET_CREATE_SUCCESS_MSG = 'ca.smartcat.support.message.ticket_create';

    const TICKETS_TAB_LABEL = 'ca.smartcat.support.ticket_tab.label';

    const CREATE_TICKET_BTN_TEXT = 'ca.smartcat.support.create_ticket_btn.text';
    const SAVE_TICKET_BTN_TEXT = 'ca.smartcat.support.create_ticket_btn.text';

    const EMPTY_TABLE_MSG = 'ca.smartcat.support.empty_table.msg';

    const SUBJECT_LABEL = 'ca.smartcat.support.subject.label';
    const SUBJECT_ERR = 'ca.smartcat.support.subject.err';

    const CONTENT_LABEL = 'ca.smartcat.support.contents.err';
    const CONTENT_ERR = 'ca.smartcat.support.contents.err';

    const FIRST_NAME_LABEL = 'ca.smartcat.support.first_name.label';
    const FIRST_NAME_ERR = 'ca.smartcat.support.first_name.err';

    const LAST_NAME_LABEL = 'ca.smartcat.support.last_name.label';
    const LAST_NAME_ERR = 'ca.smartcat.support.last_name.err';

    const EMAIL_LABEL = 'ca.smartcat.support.email.label';
    const EMAIL_ERR = 'ca.smartcat.support.email.err';

    const STATUS_LABEL = 'ca.smartcat.support.status.label';
    const STATUS_ERR = 'ca.smartcat.support.status.err';

    const ASSIGNED_LABEL = 'ca.smartcat.support.assigned.label';
    const ASSIGNED_ERR = 'ca.smartcat.support.assigned.err';

}
