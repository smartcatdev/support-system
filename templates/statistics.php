
<div class="stat-tab">
    <div><?php _e( 'Total Tickets', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <div class="grad-bubble">
        <h4>
            <?php echo \SmartcatSupport\statprocs\get_unclosed_tickets(); ?>
        </h4>
    </div>
</div>

<div class="stat-tab">
    <div><?php _e( 'Needs Attention', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'needs_attention'
    ) ) ?>
    </h4>
</div>

<div class="stat-tab">
    <div><?php _e( 'Awaiting Response', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'responded'
    ) ) ?>
    </h4>
</div>

<div class="stat-tab">
    <div><?php _e( 'Waiting', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'waiting'
    ) ); ?>
    </h4>
</div>

<div class="stat-tab">
    <div><?php _e( 'Assigned to Me', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
    <?php echo \SmartcatSupport\statprocs\get_user_assigned( array(
        'agent'    => get_current_user_id()
    ) ) ?>
    </h4>
</div>

