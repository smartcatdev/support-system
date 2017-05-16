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

        switch(value) {
            case 'last_week':
            default:
                start_month.val(moment().month() + 1);
                start_day.val(moment().date() - 7);
                start_year.val(moment().year());

                end_month.val(moment().month() + 1);
                end_day.val(moment().date());
                end_year.val(moment().year());
                break;

            case 'this_month':
                start_month.val(moment().month() + 1);
                start_day.val(moment().startOf('month').date());
                start_year.val(moment().year());

                end_month.val(moment().month() + 1);console.log(moment().endOf('month').date())
                end_day.val(moment().endOf('month').date());
                end_year.val(moment().year());
                break;

            case 'last_month':
                start_month.val(moment().month());
                start_day.val(moment().startOf('month').date());
                start_year.val(moment().year());

                end_month.val(moment().month());
                end_day.val(moment().endOf('month').date());
                end_year.val(moment().year());
                break;

            case 'this_year':
                start_month.val(1);
                start_day.val(1);
                start_year.val(moment().year());

                end_month.val(moment().month());
                end_day.val(moment().endOf('month').date());
                end_year.val(moment().year());
                break;
        }
    }

    range.change(function (e) {
        var range =  $(e.target).val();

        init_range(range);

        $('.date-range').toggleClass('hidden', range !== 'custom');
    });

});