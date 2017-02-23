/**
 * Created by petervandam on 07/11/2016.
 */
$( document ).ready(function() {
    addMobileListeners();
});

function addMobileListeners() {
    $('#mobile .listItem').click(function() {

        if ($(this).hasClass('Dumpert')) {
            window.frames[0].stop();
            return;
        }

        $('.list').hide();
        $('.title').hide();
        $('.contentContainer').show();

        $('.backButton').css('display', 'inline-block');
        $('.pageLinkToUrl').show();
        $('.navbar').css('display', 'inline-block');
    });

    $('#mobile .backButton').click(function() {
        $('.list').show();
        $('.title').show();
        $('.contentContainer').hide();

        $('.backButton').hide();
        $('.pageLinkToUrl').hide();
        $('.navbar').hide();

        $('iframe').attr('src', 'about:blank');
    });

    $('#mobile .listItem').hammer().on("swiperight", function() {
        var pin = $(this).find('.pin');
        $.ajax("/pin/" +$(pin).data('pin-id'))
            .done(function(response) {
                if (response == '1') {
                    $(pin).parent().toggleClass('pinned');
                }
            });
    });

    $('#mobile .Dumpert').each(function() {
        var title = $(this).find('.listTitle').text();
        var url = $(this).data('url');
        $(this).find('.listTitle').html('<a class="listTitle" href="' + url + '" target="_blank">' + title + '</a>');
    });
}
