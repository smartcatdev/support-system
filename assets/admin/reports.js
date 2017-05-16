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

        var now = moment();

        switch(value) {
            case 'last_week':
            default:
                start_month.val(now.month() + 1);
                start_day.val(now.date() - 7);
                start_year.val(now.year());

                end_month.val(now.month() + 1);
                end_day.val(now.date());
                end_year.val(now.year());
                break;

            case 'this_month':
                start_month.val(now.month() + 1);
                start_day.val(now.startOf('month').date());
                start_year.val(now.year());

                end_month.val(now.month() + 1);
                end_day.val(now.endOf('month').date());
                end_year.val(now.year());
                break;

            case 'last_month':
                now.subtract(1, 'month');

                start_month.val(now.month() + 1);
                start_day.val(now.startOf('month').date());
                start_year.val(now.year());

                end_month.val(now.month() + 1);
                end_day.val(now.endOf('month').date());
                end_year.val(now.year());
                break;

            case 'this_year':
                start_month.val(1);
                start_day.val(1);
                start_year.val(now.year());

                end_month.val(now.endOf('year').month());
                end_day.val(now.endOf('year').date());
                end_year.val(now.year());
                break;
        }
    }

    range.change(function (e) {
        var range =  $(e.target).val();

        init_range(range);

        $('.date-range').toggleClass('hidden', range !== 'custom');
    });

});