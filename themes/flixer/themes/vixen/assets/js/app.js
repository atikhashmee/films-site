var player_skin = { name: 'seven', active: '#98CB00', inactive: 'white', background: 'rgba(17, 26, 36, 1)' }

$(document).ready(function() {
  NProgress.done();
  $(".chosen").chosen({disable_search_threshold: 30});
  $(".chosen-results").mCustomScrollbar({theme:'dark'});
  $("#phone").intlTelInput({utilsScript: base+'/assets/tel-input/js/utils.js'});
  $(".country-list").mCustomScrollbar({theme:'light-3'});
  $(':checkbox').checkbox();
  $(".movie-slider-1").owlCarousel({
    navigation: true,
    navigationText: [
    "<i class='ti-angle-left icon-white'></i>",
    "<i class='ti-angle-right icon-white'></i>"
    ],
    autoplay: true,
    items: 4,
    lazyLoad: true,
    animateOut: 'slideOutDown',
    animateIn: 'flipInX'
  });
  if(user_id.length == 0) {
    var readonly = true;
  } else {
    var readonly = false;
  }
  $('.star-rating').rateYo({
    rating: getRating($('#movie_id').val()),
    halfStar: true,
    ratedFill: "#98CB00",
    normalFill: "#323F4E",
    readOnly: readonly,
    "starSvg": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .288l2.833 8.718h9.167l-7.417 5.389 2.833 8.718-7.416-5.388-7.417 5.388 2.833-8.718-7.416-5.389h9.167z"/></svg>'
  })
  .on('rateyo.set', function (e, data) {
    var rating = data.rating;
    var movie_id = $('#movie_id').val();
    updateRating(movie_id,rating);
  });
});

function loadSeason(season_id,season_number) {
  $.get(base+'/ajax/load_season.php?season_id='+season_id+'&season_number='+season_number,
    function(data) {
      $('.series-navigation .episodes').html(data);
      $('.series-navigation .back').show();
      $('.series-navigation .seasons').hide();
      $('.series-navigation .episodes').show();
    }
    );
}

function showSeasons() {
  $('.series-navigation .seasons').show();
  $('.series-navigation .episodes').hide();
  $('.series-navigation .back').hide();
}

function loadCast(movie_id) {
  $('.episodes').hide();
  $('.season-picker').hide();
  if($('.tabs li:nth-child(3)').length) {
    $('.tabs li:nth-child(1)').removeClass('active');
    $('.tabs li:nth-child(2)').addClass('active');
  } else {
    $('.tabs li:nth-child(2)').removeClass('active');
    $('.tabs li:nth-child(1)').addClass('active');
  }
  $('.cast').show();
}

function loadEpisode(episode_id,is_embed) {
  $.get(base+'/ajax/load_episode.php?episode_id='+episode_id+'&is_embed='+is_embed,
    function(data) {
      if(is_embed == 0) {
        jwplayer().stop();
        var result = JSON.parse(data);
        var output = [];
        for(var i = 0; i < result.playlist.length; i++) {
          var item = result.playlist[i];
          output[i] = {};
          output[i].file = uploads_path+'/'+item.episode_source;
          output[i].image = uploads_path+'/poster_images/'+result.series_poster_image;
          output[i].title = item.episode_name;
          output[i].description = item.episode_description;
        }
        jwplayer().load(output);
        jwplayer().playlistItem(result.episode_index-1);
      } else {
        $('.player-single-wrapper').html(data);
      }
    }
    );
}

function addToList(movie_id) {
  $.get(base+'/ajax/add_to_list.php?movie_id='+movie_id,
    function(data) {
      $('.add-list').html(data);
    }
    );
}

function initializePlayer(playlist) {
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
   skin: player_skin
 });
}

function isURL(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
  '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return pattern.test(str);
}

function cleanSource(source) {
  if(isURL(source) == true) {
    source = source;
  } else {
    source = uploads_path+'/'+source
  }
  return source;
}

function setPlayerSource(movie_id,is_series,is_embed) {
  $.get(base+'/ajax/set_player_source.php?id='+movie_id+'&is_series='+is_series+'&is_embed='+is_embed,
    function(data) {
     if(is_embed == 0) {
       if(is_series == 0) {
        loadCast(movie_id);
        var result = JSON.parse(data);
        var output = {};
        output.file = cleanSource(result[0].movie_source);
        output.image = uploads_path+'/poster_images/'+result[0].movie_poster_image;
        output.title = result[0].movie_name;
        output.description = result[0].movie_plot;
        initializePlayer(output);
      } else {
        var result = JSON.parse(data);
        var output = [];
        for(var i = 0; i < result.playlist.length; i++) {
          var item = result.playlist[i];
          output[i] = {};
          output[i].file = cleanSource(item.episode_source);
          output[i].image = uploads_path+'/poster_images/'+result.series_poster_image;
          output[i].title = item.episode_name;
          output[i].description = item.episode_description;
        }
        initializePlayer(output);
      }
    } else {
     $('.player-single-wrapper').html(data);
   }
 }
 );
}

function updateRating(movie_id,rating) {
  $.get(base+'/ajax/update_rating.php?movie_id='+movie_id+'&rating='+rating);
}

function getRating(movie_id) {
  $.get(base+'/ajax/get_rating.php?movie_id='+movie_id,
    function(data) {
      $(".star-rating").rateYo("option", "rating", data);
    }
    );
}

function changeFilter() {
  var filter = $('#filter').val();
  var no_filter_url = location.href.replace(/&?filter=([^&]$|[^&]*)/i, '');
  var no_filter_url = no_filter_url.replace('?', '');
  document.location = no_filter_url+'?filter='+filter;
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

$(document).on('hidden.bs.modal', function (e) {
  jwplayer().stop();
  $('html').css('overflow-y','scroll');
})