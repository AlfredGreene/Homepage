/**
 * Created by petervandam on 26/10/2016.
 */

$(function() {

    $('.js-button-parse-url').on('click', function() {
        getUrlMetaData();
    });

    $('.js-form-feed [name="url"]').on('blur', function() {
        getUrlMetaData();
    });

    $('.widget-note textarea').on('blur', function() {
       saveNote($(this));
    });

    $('.widget-note input').on('blur', function() {
       $(this).parent().find('textarea').trigger('blur');
    });

    setInterval(function() {
        $('.js-update-weahter-icon').load('/weather/icon/');
        $('.js-weather-radar').attr('src', 'https://api.buienradar.nl/image/1.0/RadarMapNL?w=500&h=512&time=' + Math.random());
    }, 5 * 60 * 1000);

    $(document).on('click', '.js-open-note', function() {
        $('.widget-note--notes > div').hide();
        $('.note-data-' + $(this).data('note-id')).show();
    })
        .on('click', '.js-remove-note', function() {
            removeNote($(this).data('id'));
        })
        .on('click', '.js-update-weather-icon', function() {
            $('.content-overlay').fadeIn();
            $('.js-show-weather-radar').slideDown();
        })
        .on('click', '.content-overlay, .feed-list', function() {
            $('.content-overlay').fadeOut();
            $('.js-show-weather-radar').slideUp();
        });

    $('.specialTxt').each(function() {
        var p1 = 'peter';
        var p3 = 'vdam';
        var p2 = '.nl';
        var p4 = 'mail';
        var p5 = '@';
        var p6 = 'to';
        $(this).html('<a href="' + p4 + p6 + ':' + p1 + p5 + p1 + p3 + p2 + '">' + p1 + p5 + p1 + p3 + p2 + '</a>');
    });

    $('.page--homepage').on('click', function() {
       $('.page--homepage .header').animate({
           height: '400px'
       }, 500);
       $('.widget').fadeIn();
    });

    $('.js-toggle-fullscreen').on('dblclick', function() {
        var sidebarWidth = $('.container .feed-list').css('width');
        var headerColor = '#337dff';

        if ($('.content').css('left') !== '0px') {
            sidebarWidth = 0;
            headerColor = '#000';
        }

        $('.header--bar-actions').toggle('slow');
        $('.header--bar').css('backgroundColor', headerColor);
        $('.content').animate({
            left: sidebarWidth
        }, 1000);
    });
});

function getUrlMetaData() {
    var Url = $('.js-form-feed [name="url"]').val();

    if (Url.length > 0) {
        $.ajax({
            method: "POST",
            url: "/meta/",
            data: { url: Url}
        })
            .done(function( data ) {
                var json = $.parseJSON(data);
                $('.js-form-feed [name="title"]').val(json.title);
                $('.js-form-feed [name="description"]').val(json.description);
            });
    }
}

function saveNote($el) {
    var id = $el.attr('data-id');
    var name = $el.parent().find('input').val();
    var position = $el.attr('data-position');
    var note = $el.val();

    $.ajax({
        method: "POST",
        url: "/note/save/",
        data: {
            id: id,
            position: position,
            name: name,
            note: note
        },
        beforeSend: function() {
            $el.css('color', '#303030');
        }
    })
        .done(function(data) {
            $el.css('color', 'black');

            $('.note-selector-' + id).text(name);
            if (id.length === 0) {
                var info = $.parseJSON(data);
                $el.attr('data-id', info.id);
            }
        })
        .fail(function() {
            $el.css('color', 'red');
        });
}

function removeNote(id) {
    if (confirm("Are you sure you want to remove this note?")) {
        $.ajax({
            method: "POST",
            url: "/note/remove/",
            data: { id: id}
        })
            .done(function() {
                $('.note-selector-' + id).hide();
                $('.note-data-' + id).hide();
            });
    }
}

/** global: WOW */
new WOW({
    scrollContainer: '.scroll',
    mobile: false
}).init();
