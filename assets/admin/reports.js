jQuery(document).ready(function ($) {

    var start_date = $('.start_date');
    var end_date = $('.end_date');
    var range = $('.date-range-select');

    function init_range(value) {
        switch(value) {
            case 'last_week':
            default:
                start_date.find('[name="start_month"]').val(moment().month() + 1);
                start_date.find('[name="start_day"]').val(moment().date() - 7);
                start_date.find('[name="start_year"]').val(moment().year());

                end_date.find('[name="end_month"]').val(moment().month() + 1);
                end_date.find('[name="end_day"]').val(moment().endOf('week').date());
                end_date.find('[name="end_year"]').val(moment().year());
                break;

            case 'this_month':
                start_date.find('[name="start_month"]').val(moment().month() + 1);
                start_date.find('[name="start_day"]').val(moment().startOf('month').date());
                start_date.find('[name="start_year"]').val(moment().year());

                end_date.find('[name="end_month"]').val(moment().month() + 1);
                end_date.find('[name="end_day"]').val(moment().endOf('month').date());
                end_date.find('[name="end_year"]').val(moment().year());
                break;

            case 'last_month':
                start_date.find('[name="start_month"]').val(moment().month());
                start_date.find('[name="start_day"]').val(moment().startOf('month').date());
                start_date.find('[name="start_year"]').val(moment().year());

                end_date.find('[name="end_month"]').val(moment().month());
                end_date.find('[name="end_day"]').val(moment().endOf('month').date());
                end_date.find('[name="end_year"]').val(moment().year());
                break;

            case 'this_year':
                start_date.find('[name="start_month"]').val(1);
                start_date.find('[name="start_day"]').val(1);
                start_date.find('[name="start_year"]').val(moment().year());

                end_date.find('[name="end_month"]').val(moment().month());
                end_date.find('[name="end_day"]').val(moment().endOf('month').date());
                end_date.find('[name="end_year"]').val(moment().year());
                break;
        }
    }

    init_range();

    range.change(function (e) {
        var range =  $(e.target).val();

        init_range(range);

        $('.date-range').toggleClass('hidden', range !== 'custom');
    });

});