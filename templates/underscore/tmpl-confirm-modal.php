<?php
/**
 * Underscore.js template for conformation modals.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

?>

<script type="text/template" class="confirm-modal">

    <div id="<%= id %>" class="modal close-ticket-modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"><%= title %></h4>

                </div>

                <div class="modal-body">

                    <p><%= content %></p>

                </div>

                <div class="modal-footer">

                    <button type="button" class="button confirm">

                        <span class="glyphicon glyphicon-ok button-icon"></span>

                        <span><%= okay_text %></span>

                    </button>

                    <button type="button" class="button button-submit cancel"
                            data-target="#<%= id %>"
                            data-toggle="modal">

                        <span class="glyphicon glyphicon-ban-circle button-icon"></span>

                        <span><%= cancel_text %></span>

                    </button>

                </div>

            </div>

        </div>

    </div>

</script>
