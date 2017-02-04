/**
 * Created by DarkAvenger on 04.02.2017.
 */

$( document ).ready(function() {



    $(".various").click( function(event)
    {
        $('.block_captch').show();
        $('.block_img').html("");
        $('#myForms input[type="submit"]').show();
    });


    $(".various").fancybox({
        maxWidth	: 800,
        maxHeight	: 600,
        fitToView	: false,
        width		: '70%',
        height		: '70%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });

    $("#myForms").submit( function(event)
    {
        var data = $(this).serialize();
        var action = $(this).attr("action");
        $.ajax(
            {
                url : action,
                type: "POST",
                data : data,
                success: function(data, textStatus, jqXHR) {


                    $('.block_captch').hide();
                    $('#myForms input[type="submit"]').hide();

                    $('.block_img').html("<img src="+data.gif+">");
                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        event.preventDefault();

    });




});