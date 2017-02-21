(function ($) {

    $.fn.submit = function (options) {

        var _form = $(this);

        var _defaults = {
            method: "get",
            success: function (response) {},
            error: function (xhr, status, error) {}
        };

        var _settings = $.extend(_defaults, options);

        var _show_errors = function (errors) {
            _form.find(".form-control").each(function(index, element) {
                var field = $(element);
                var container = field.parent();

                if (errors[ field.attr("name") ] !== undefined) {
                    container.append( "<span class=\"help-block\">" + errors[ field.attr("name") ] + "</span>" );
                    container.addClass("has-error");
                }
            });
        };

        var _clear_errors = function () {
            _form.find(".form-control").each(function(index, element) {
                var field = $(element);
                var container = field.parents();

                container.find(".help-block").remove();
                container.removeClass("has-error");
            });
        };

        _clear_errors();

        return $.ajax({
            url: _settings.url + "?action=" + _settings.action,
            method: _settings.method,
            data: _settings.data,
            success: _settings.success,
            error: function (xhr, status, error) {
                _show_errors(xhr.responseJSON.data);
                _settings.error(xhr, status, error);
            }
        });
    };

})(jQuery);
