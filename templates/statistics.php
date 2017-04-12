
<div class="stat-tab">
    <div class="inner">
        
        <h4>
            <?php echo \SmartcatSupport\statprocs\get_unclosed_tickets(); ?>
        </h4>
        <h5 class="stat-label"><?php _e( 'Total Tickets', \SmartcatSupport\PLUGIN_ID ); ?></h5>
        
        <span class="glyphicon glyphicon-envelope"></span>
        
    </div>
</div>

<div class="stat-tab">
    
    <div class="inner">
        
        <h4>
        <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
            'status'    => 'needs_attention'
        ) ) ?>
        </h4>
        <h5 class="stat-label"><?php _e( 'Needs Attention', \SmartcatSupport\PLUGIN_ID ); ?></h5>
        
        <span class="glyphicon glyphicon-exclamation-sign"></span>
        
    </div>
</div>

<div class="stat-tab">
    <div class="inner">
        
        <h4>
        <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
            'status'    => 'responded'
        ) ) ?>
        </h4>
        <h5 class="stat-label"><?php _e( 'Awaiting Response', \SmartcatSupport\PLUGIN_ID ); ?></h5>
        
        <span class="glyphicon glyphicon-repeat"></span>
        
    </div>
</div>

<div class="stat-tab">
    <div class="inner">
        
        <h4>
        <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
            'status'    => 'waiting'
        ) ); ?>
        </h4>
        <h5 class="stat-label"><?php _e( 'Waiting', \SmartcatSupport\PLUGIN_ID ); ?></h5>
        
        <span class="glyphicon glyphicon-time"></span>
        
    </div>
</div>

<div class="stat-tab">
    <div class="inner">
        
        <h4>
        <?php echo \SmartcatSupport\statprocs\get_user_assigned( array(
            'agent'    => get_current_user_id()
        ) ) ?>
        </h4>
        <h5 class="stat-label"><?php _e( 'Assigned to Me', \SmartcatSupport\PLUGIN_ID ); ?></h5>
        
        <span class="glyphicon glyphicon-user"></span>
        
    </div>
</div>

