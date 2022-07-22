$(document).ready(function () {


    $('.nav-link.active .sub-menu').slideDown();
    $('.nav-link.active .arrow').toggleClass('fa-angle-right fa-angle-down');

    // $("p").slideUp();

    $('#sidebar-menu .arrow').click(function () {
        console.log(this);
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });


    //check all
    $("input[name='checkall']").click(function () {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });

});