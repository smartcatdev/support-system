jQuery(document).ready(function ($) {

    var start_date = $('.start_date');
    var end_date = $('.end_date');
    var range = $('.date-range-select');

    var start_day = start_date.find('[name="start_day"]');
    var start_month = start_date.find('[name="start_month"]');
    var start_year = start_date.find('[name="start_year"]');

    var end_day = end_date.find('[name="end_day"]');
    var end_month = end_date.find('[name="end_month"]');
    var end_year = end_date.find('[name="end_year"]');

    function init_range(value) {

        start_day.attr('selected', '');
        start_month.attr('selected', '');
        start_year.attr('selected', '');

        end_day.attr('selected', '');
        end_month.attr('selected', '');
        end_year.attr('selected', '');

        if(value != 'custom') {
            var start = moment(default_dates[value].start);
            var end = moment(default_dates[value].end);
        } else {
            var start = moment(default_dates['last_week'].start);
            var end = moment(default_dates['last_week'].end);
        }

        start_year.val(start.year());
        start_month.val(start.month() + 1);
        start_day.val(start.date());

        end_year.val(end.year());
        end_month.val(end.month() + 1);
        end_day.val(end.date());

    }

    range.change(function (e) {
        var range =  $(e.target).val();

        init_range(range);

        $('.date-range').toggleClass('hidden', range !== 'custom');
    });

});