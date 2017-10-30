/*=====================
 Common js for get local
 ========================*/
function init_spinner() {

    $(".number-spinner input").focusout(function (e) {

        $('.number-spinner button').closest('.number-spinner').find('button').prop("disabled", false);

        if ($(this).attr('max') == undefined || parseInt($(this).val()) < parseInt($(this).attr('max')))
        {
            $('.data-up').prop("disabled", true);
        }
    });


    $('.number-spinner button').on('click', function () {

        btn = $(this);
        input = btn.closest('.number-spinner').find('input');
        btn.closest('.number-spinner').find('button').prop("disabled", false);


        if (btn.attr('data-dir') == 'up') {
            if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
                input.focus();
                input.val(parseInt(input.val()) + 1);
            } else {
                btn.prop("disabled", true);
            }
        } else {
            if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
                input.focus();
                input.val(parseInt(input.val()) - 1);
            } else {
                btn.prop("disabled", true);
            }
        }

        // Script for Spinner Label in page

        if (input.val() == 1) {
            $('.people-field span').text('Person');
        } else {
            $('.people-field span').text('People');
        }
        btn.focus();
        //input.trigger('change');
        //console.log('eerewr');

    });

}

function init_homesearch() {

    var start = moment();
    var end = moment();


    // On click of change value of search Dropdown menu
    $(".dropdown-menu li").click(function () {
        $($(this).parent().parent().parent().parent()).children().val($(this)[0].innerText);
    });


    // Script for Dropdown Selected
    $('.dropdown').on('click', '.dropdown-menu li', function () {
        var target = $(this).html();
        // Adds active class to selected item
        $(this).parents('.dropdown-menu').find('li').removeClass('selected');
        $(this).addClass('selected');
        $('.dropdown .date-clear').addClass('date-clear-show');
    });


    /* SCRIPT FOR AUTO RESET DROPDOWN FIELDS ON HOMEPAGE */
    $('.dropdown .date-clear').click(function () {
        $('.dropdown .date-clear').removeClass('date-clear-show')
        //debugger
        $($(this).parent()).find('input').val('Anything')
        $($(this).parent()).find('.dropdown-menu .searchlist ul li').removeClass('selected')
    });


    function cb(start, end) {
        var selectedclickOption = $('#selected-calender-option').val();
        if (selectedclickOption === "s")
        {
            $('#dates span').html(start.format('DD/MM/YYYY'));
        }
        else
        {
            $('#dates span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        }

        $("#dates .date-clear").addClass("date-clear-show");
    }


    $('#dates').daterangepicker({
        //startDate: '29/01/2017',
        minDate: moment(),
        startDate: start,
        endDate: end,
        ranges: {
            'Single day': [moment().startOf('month'), moment().endOf('month')],
        },
        autoApply: true
    }, cb);


    $('#dates').click(function () {
        var selectedclickOption = $('#selected-calender-option').val();
        if (selectedclickOption === 's')
        {
            $($('.ranges li')[0]).trigger('click.daterangepicker');
            $($('.ranges li')[0]).addClass('selected');
        }
        else
        {
            $($('.ranges li')[1]).trigger('click.daterangepicker');
            //$($('.ranges li')[1]).addClass('selected');
        }
    });


    $(".ranges  > ul > li").click(function () {
        //debugger
        if ($($('.ranges li')[0]).hasClass('selected'))
        {
            $($('.ranges li')[0]).removeClass('selected');
            $($('.ranges li')[1]).addClass('selected');
        }
        else
        {
            $($('.ranges li')[1]).removeClass('selected');
            $($('.ranges li')[0]).addClass('selected');
        }
    });


    $('#dates .date-clear').click(function () {
        $('#dates').data('daterangepicker').setStartDate(moment());
        $('#dates').data('daterangepicker').setEndDate(moment());
        $("#dates .date-clear").removeClass("date-clear-show");
        $('#dates span').text('Select date');
        $('#dates span').html('Select date');
    });

    //cb(start, end);
    $('#dates span').text('Select date');

    // Script for Number Spiner ( manual Edit & Disable issue resolve)



    /* SCRIPT FOR SLIDER FIRST SLIDE ON LEFT */




}


// redirect Search script 
jQuery(document).ready(function () {
    jQuery('#getlocal-search').click(function () {
        var url = jQuery('#redirect_url').val();
        var final_url = url + '?idx=GetLocal_Adv_Search';

        var affiliateid = jQuery('#affiliateid').val();
        var dropdowntoggle = jQuery('.dropdown-toggle').val();
        var type_date = jQuery('#selected-calender-option').val();

        var dates = $('#dates span').html().split(' - ');

        if (dates[0] != 'Start dates' || dates[1] != 'End dates') {

            if (dates.length > 1 && dates[0] != 'Start dates' && dates[0] != 'Anytime' && dates[1] != 'End dates' && dates[1] != undefined) {
                var from = dates[0].split("/");
                var startDate = moment([from[2], parseInt(from[1]) - 1, from[0]]);
                startDate = startDate.format('YYYYMMDD');
                var to = dates[1].split("/");
                var endDate = moment([to[2], parseInt(to[1]) - 1, to[0]]);
                endDate = endDate.format('YYYYMMDD');
            }
            else {
                if (dates[0] != 'Start dates' && dates[0] != 'Anytime') {

                    var from = dates[0].split("/");
                    var startDate = moment([from[2], parseInt(from[1]) - 1, from[0]]);
                    startDate = startDate.format('YYYYMMDD');
                }
                if (dates[1] != 'End dates' && dates[1] != undefined) {
                    var to = dates[1].split("/");
                    var endDate = moment([to[2], parseInt(to[1]) - 1, to[0]]);
                    endDate = endDate.format('YYYYMMDD');
                }
            }
        }

        if (dates != 'Select date') {
            if (type_date == 's')
            {
                final_url += '&nR[availabilityDate][=][0]=' + startDate;
            } else {
                final_url += '&nR[availabilityDate][>=][0]=' + startDate + '&nR[availabilityDate][<=][0]=' + endDate;
            }
        }
        var partAdult = jQuery('#partAdult').val();

        //alert(type_date);
        if (dropdowntoggle != 'Open for anything...' && dropdowntoggle != 'Anything')
        {
            dropdowntoggle = encodeURIComponent(dropdowntoggle);
            final_url += '&hFR[activities.activity][0]=' + dropdowntoggle;
        }



        final_url += '&nR[availabilityCount][>=][0]=' + partAdult +'&aid='+affiliateid;
        //alert(final_url);
        window.open(final_url, 'Getlcoal');
        //window.location.replace(final_url);
    });


});

init_homesearch();
init_spinner();