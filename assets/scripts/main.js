/**
 * Created by petervandam on 26/10/2016.
 */
$( document ).ready(function() {

    $('.listItem').click(function() {

        $('.listItem').removeClass('selected');
        $(this).addClass('selected');
        $(this).addClass('used');

        var url = $(this).data('url');
        if (url == '') { url = 'nourl.php'; }

        $('iframe').attr('src', url);
        $('.pageLinkToUrl').text(url);
        $('.pageLinkToUrl').attr('href', url);
    });

    $('.title a').click(function() {
        $('iframe').attr('src', '/welcome/');
        $('.pageLinkToUrl').text('');
    });

    $('.pin').click(function(e) {
        e.stopImmediatePropagation();
        var pin = $(this);
        $.ajax("/pin/" +$(this).data('pin-id'))
            .done(function(response) {
                if (response == '1') {
                    $(pin).parent().toggleClass('pinned');
                }
            });
    });

    $('.createButton').click(function() {
        $('#createItem').modal({fadeDuration:100});
    });

    $('.submitFeedItem').click(function() {
        $.post( "/add-item/", $('#createItem').serialize())
            .done(function(data) {

                if (data == "Done") {
                    $.modal.close();
                }
                else {
                    alert(data);
                }
            })
            .fail(function(data) {
                alert(data);
            })
    });

});
