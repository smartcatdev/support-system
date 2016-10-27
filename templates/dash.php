<?php use const SmartcatSupport\TEXT_DOMAIN; ?>

<form method="POST" data-action="edit_support_ticket" id="new_ticket">
    <input type="submit" value="New Ticket" />
</form>

<form method="POST" data-action="edit_support_ticket" id="select_ticket">
    <input type="number" name="ticket_id" />
    <input type="submit" value="Get Ticket" />
</form>

<form method="POST" data-action="ticket_list" id="all_tickets">
    <input type="submit" value="View Tickets" />
</form>
