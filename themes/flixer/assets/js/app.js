var player_skin = { name: 'seven', active: '#49d244', inactive: 'white', background: 'rgba(16, 16, 16, 1)' }

$(document).ready(function() {
    NProgress.done();
    $(".chosen").chosen({ disable_search_threshold: 30 });
    $(".chosen-results").mCustomScrollbar({ theme: 'dark' });
    $("#phone").intlTelInput({ utilsScript: base + '/assets/tel-input/js/utils.js' });
    $(".country-list").mCustomScrollbar({ theme: 'dark-3' });
    $(':checkbox').checkbox();
    $(".movie-slider-1").owlCarousel({
        loop : false,
        nav: true,
        navText: [
            "<i class='ti-angle-left icon-white'></i>",
            "<i class='ti-angle-right icon-white'></i>"
        ],
        autoplay: false,
        items: 4,
        lazyLoad: true,
        animateOut: 'slideOutDown',
        animateIn: 'flipInX',
        responsive:{
          0:{
              items:1
          },
          300:{
              items:1
          },
          400:{
              items:2
          },
          600:{
              items:3
          },
          1000:{
              items:4
          }
        }
    });
    if (user_id.length == 0) {
        var readonly = true;
    } else {
        var readonly = false;
    }
    $('.star-rating').rateYo({
            rating: getRating($('#movie_id').val()),
            halfStar: true,
            ratedFill: "#49d244",
            normalFill: "#262626",
            readOnly: readonly,
            "starSvg": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .288l2.833 8.718h9.167l-7.417 5.389 2.833 8.718-7.416-5.388-7.417 5.388 2.833-8.718-7.416-5.389h9.167z"/></svg>'
        })
        .on('rateyo.set', function(e, data) {
            var rating = data.rating;
            var movie_id = $('#movie_id').val();
            //updateRating(movie_id,rating);
        });


    $('#rateYo').rateYo({
        rating: parseFloat($('#ratings_id').val()),
        halfStar: true,
        ratedFill: "#49d244",
        normalFill: "#262626",
        readOnly: readonly,
        "starSvg": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .288l2.833 8.718h9.167l-7.417 5.389 2.833 8.718-7.416-5.388-7.417 5.388 2.833-8.718-7.416-5.389h9.167z"/></svg>'
    })


});

function loadSeason(season_id, season_number) {
    $.get(base + '/ajax/load_season.php?season_id=' + season_id + '&season_number=' + season_number,
        function(data) {
            $('.cast').hide();
            $('.suggestions').hide();
            $('.tabs li:nth-child(1)').addClass('active');
            $('.tabs li:nth-child(2)').removeClass('active');
            $('.tabs li:nth-child(3)').removeClass('active');
            $('.episodes').show();
            $('.season-picker').show();
            $('.episodes-ajax').html(data);
            $('.season-number').html('Season ' + season_number);
            $('#season-' + season_number).addClass('active');
        }
    );
}

function loadCast(movie_id) {
    $('.episodes').hide();
    $('.suggestions').hide();
    $('.season-picker').hide();
    if ($('.tabs li:nth-child(3)').length) {
        $('.tabs li:nth-child(1)').removeClass('active');
        $('.tabs li:nth-child(2)').addClass('active');
        $('.tabs li:nth-child(3)').removeClass('active');
    } else {
        $('.tabs li:nth-child(2)').removeClass('active');
        $('.tabs li:nth-child(1)').addClass('active');
    }
    $('.cast').show();
}


function loadSuggestions(movie_id) {
    $('.episodes').hide();
    $('.cast').hide();
    $('.season-picker').hide();
    if ($('.tabs li:nth-child(3)').length) {
        $('.tabs li:nth-child(2)').removeClass('active');
        $('.tabs li:nth-child(1)').removeClass('active');
        $('.tabs li:nth-child(3)').addClass('active');
    } else {
        $('.tabs li:nth-child(1)').removeClass('active');
        $('.tabs li:nth-child(2)').addClass('active');
    }
    $('.suggestions').show();
}

function loadEpisode(episode_id, is_embed) {
    $.get(base + '/ajax/load_episode.php?episode_id=' + episode_id + '&is_embed=' + is_embed,
        function(data) {
            if (is_embed == 0) {
                jwplayer().stop();
                var result = JSON.parse(data);
                var output = [];
                for (var i = 0; i < result.playlist.length; i++) {
                    var item = result.playlist[i];
                    output[i] = {};
                    output[i].file = uploads_path + '/' + item.episode_source;
                    output[i].image = uploads_path + '/poster_images/' + result.series_poster_image;
                    output[i].title = item.episode_name;
                    output[i].description = item.episode_description;
                }
                jwplayer().load(output);
                jwplayer().playlistItem(result.episode_index - 1);
            } else {
                $('.player-single-wrapper').html(data);
            }
        }
    );
}

function addToList(movie_id) {
    $.get(base + '/ajax/add_to_list.php?movie_id=' + movie_id,
        function(data) {
            $('.add-list').html(data);
        }
    );
}

function addToList1(movie_id) {
    $.get(base + '/ajax/add_to_list.php?movie_id=' + movie_id + '&episode=episode',
        function(data) {
            $('.add-list').html(data);
        }
    );
}



function markaswatched(movie_id) {
    $.get(base + '/ajax/add_to_watch.php?movie_id=' + movie_id,
        function(data) {
            $('.add-list-watch').html(data);
        }
    );
}



function markaswatched111111(movie_id,ep_no) {
    $.get(base + '/ajax/add_to_watched.php?movie_id=' + movie_id + '&ep_no='+ep_no+'&episode=episode',
        function(data) {
            if (data != "no") {
                $('.add-list-watch').html(data);
            }
        }
    );
}



function markaswatched1(movie_id) {
    $.get(base + '/ajax/add_to_watched.php?movie_id=' + movie_id,
        function(data) {
            if (data != "no") {
                $('.add-list-watch').html(data);
            }
        }
    );
}

function markaswatched2(movie_id) {
    $.get(base + '/ajax/add_to_watched.php?movie_id=' + movie_id + '&episode=episode',
        function(data) {
            if (data != "no") {
                $('.add-list-watch').html(data);
            }
        }
    );
}



function initializePlayer(playlist, movieid) {
    var playerInstance = jwplayer('player');
    playerInstance.setup({
        "playlist": playlist,
        stretching: 'fill',
        height: '100%',
        width: '100%',
        abouttext: 'Flixer Player',
        repeat: false,
        autostart: false,
        displaytitle: false,
        displaydescription: false,
        skin: player_skin,
        events: {
            onPlay: function(callback) {
                markaswatched1(movieid);
            }
        }
    });

}

function initializePlayer1(playlist, movieid) {
    var playerInstance = jwplayer('player');
    playerInstance.setup({
        "playlist": playlist,
        stretching: 'fill',
        height: '100%',
        width: '100%',
        abouttext: 'Flixer Player',
        repeat: false,
        autostart: false,
        displaytitle: false,
        displaydescription: false,
        skin: player_skin,
        events: {
            onPlay: function(callback) {
                markaswatched2(movieid);
            }
        }
    });

}



function isURL(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
        '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
    return pattern.test(str);
}

function isURL2(str) {
    var pattern = /^((http|https|ftp):\/\/)/;
    return pattern.test(str);
}


function cleanSource(source) {
    if (isURL(source) == true) {
        source = source;
    } else if (isURL2(source) == true) {
        source = source;
    } else {
        source = uploads_path + '/' + source
    }
    return source;
}

function setPlayerSource(movie_id, is_series, is_embed) {
    $.get(base + '/ajax/set_player_source.php?id=' + movie_id + '&is_series=' + is_series + '&is_embed=' + is_embed,
        function(data) {
            if (is_embed == 0) {

                loadCast(movie_id);
                var result = JSON.parse(data);
                var output = {};
                output.file = cleanSource(result[0].movie_source);
                output.image = uploads_path + '/poster_images/' + result[0].movie_poster_image;
                output.title = result[0].movie_name;
                output.description = result[0].movie_plot;
                console.log(output);
                initializePlayer(output, result[0].id);
            } else {
                $('.player-single-wrapper').html(data);
            }
        }
    );
}


function setPlayerSource1(movie_id, is_embed) {

    $.get(base + '/ajax/set_player_source1.php?id=' + movie_id + '&is_embed=' + is_embed,
        function(data) {
            if (is_embed == 0) {
                loadCast(movie_id);
                var result = JSON.parse(data);
                var output = {};
                output.file = cleanSource(result[0].episode_source);
                output.image = uploads_path + '/episodes/' + result[0].episode_thumbnail;
                output.title = result[0].episode_name;
                output.description = result[0].episode_description;
                initializePlayer1(output, result[0].id);
            } else {
                $('.player-single-wrapper').html(data);
            }
        }
    );
}



function updateRating(movie_id, rating) {
    $.get(base + '/ajax/update_rating.php?movie_id=' + movie_id + '&rating=' + rating);
}

function updateRating1(movie_id, rating, episode) {
    // $.get(base+'/ajax/update_rating.php?movie_id='+movie_id+'&rating='+rating+'&episode=episode');
}

function getRating(movie_id) {
    $.get(base + '/ajax/get_rating.php?movie_id=' + movie_id,
        function(data) {
            if (data == '') {
                data = 5;
            }
            $(".star-rating").rateYo("option", "rating", data);
        }
    );
}

function changeFilter() {
    var filter = $('#filter').val();
    var no_filter_url = location.href.replace(/&?filter=([^&]$|[^&]*)/i, '');
    var no_filter_url = no_filter_url.replace('?', '');
    document.location = no_filter_url + '?filter=' + filter;
}

function showSearch() {
    var form = $('.navbar-form');
    var toggle = $('#search-toggle');
    form.fadeIn('fast');
    toggle.hide();
}

function hideSearch() {
    var form = $('.navbar-form');
    var toggle = $('#search-toggle');
    form.hide();
    toggle.show();
}

$('#phone').on('countrychange', function(e, countryData) {
    var phone = $('#phone').intlTelInput('getNumber');
    $('#phone_country_code').val(phone);
});

$(document).on('hidden.bs.modal', function(e) {
    jwplayer().stop();
    $('html').css('overflow-y', 'scroll');
})
