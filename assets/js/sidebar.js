var Sidebar = (function (module, $, window, globals, comments) {
    "use strict";

    var load_sidebar = function (id) {
        $.ajax({
            url: globals.ajaxUrl,
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

    return {
      load_sidebar: load_sidebar
    };

})(Sidebar || {}, jQuery, window, Globals, Comments);