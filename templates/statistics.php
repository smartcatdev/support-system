
<div class="col-sm-2 stat-tab">
    <div><?php _e( 'Total tickets', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
        <?php echo \SmartcatSupport\statprocs\get_unclosed_tickets(); ?>
    </h4>
</div>

<div class="col-sm-2 stat-tab">
    <div><?php _e( 'Needs attention', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'needs_attention'
    ) ) ?>
    </h4>
</div>

<div class="col-sm-2 stat-tab">
    <div><?php _e( 'Awaiting response', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'responded'
    ) ) ?>
    </h4>
</div>

<div class="col-sm-2 stat-tab">
    <div><?php _e( 'Waiting', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'waiting'
    ) ); ?>
    </h4>
</div>

<div class="col-sm-2 stat-tab">
    <div><?php _e( 'Assigned to me', \SmartcatSupport\PLUGIN_ID ); ?></div>
    <h4>
    <?php echo \SmartcatSupport\statprocs\get_user_assigned( array(
        'agent'    => get_current_user_id()
    ) ) ?>
    </h4>
</div>

