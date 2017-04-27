jQuery(document).ready(function ($) {

    var start_date = $('.start_date').datepicker({
        dateFormat : 'dd-mm-yy',
        minDate: moment().subtract(2, 'years').toDate()
    });

    var end_date = $('.end_date').datepicker({
        dateFormat : 'dd-mm-yy',
        maxDate: moment().toDate()
    });

    $('.date-range-select').change(function (e) {

        var selection = $(e.target).val();

        var start;
        var end;

        switch($(e.target).val()) {
            case 'last_week':
                end = moment();
                start = moment().subtract(7, 'days');
                break;

            case 'this_month':
                end = moment();
                start = moment().startOf('month');
                break;

            case 'last_month':
                var d = moment().subtract(1, 'month');

                start = d.clone().startOf('month');
                end = d.clone().endOf('month');
                break;

            case 'this_year':
                start = moment().startOf('year');
                end = moment();
                break;

            case 'custom':
            default:
                start = moment();
                end = moment();
        }

        start_date.datepicker('setDate', start.format('DD-MM-YYYY'));
        end_date.datepicker('setDate', end.format('DD-MM-YYYY'));

        $('.date-range').toggleClass('hidden', selection !== 'custom');

    });

});