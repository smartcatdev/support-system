var Sidebar = (function (module, $, window) {
    "use strict";

    var load_sidebar = function (id) {
        $.ajax({
            url: Globals.ajaxUrl,
            dataType: "json",
            data: {
                action: "support_ticket_sidebar",
                id: id
            },
            success: function (response) {
                $("#" + id).find(".sidebar").html(response.data);
            }
        });
    };

    var initialize = function () {
        window.setInterval(function () {
            $("div.pane").each(function (index, element) {

                var id = $(element).attr("id");
                if (!isNaN(id)) {
                    load_sidebar(id);
                }
            });
        }, 1000 * 60);
    };

    return {
        load_sidebar: load_sidebar,
        initialize: initialize
    };

})(Sidebar || {}, jQuery, window);

jQuery(document).ready(function ($) {

    Sidebar.initialize();

});