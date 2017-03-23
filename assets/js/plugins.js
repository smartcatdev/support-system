(function ($) {

    $.fn.submit = function (options) {

        var _form = $(this);

        var _defaults = {
            method: "post",
            success: function (response) {},
            error: function (xhr, status, error) {},
            complete: function (xhr, status) {},
            extras: {}
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
            url: _settings.url + "?action=" + _settings.action + "&" + _form.serialize(),
            method: _settings.method,
            data: _settings.extras,
            success: _settings.success,
            complete: _settings.complete,
            error: function (xhr, status, error) {
                _show_errors(xhr.responseJSON.data);
                _settings.error(xhr, status, error);
            }
        });
    };

})(jQuery);
