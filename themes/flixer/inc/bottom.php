<?php if(isset($page['footer']) && $page['footer'] == true) { ?>
<footer class="footer footer-black footer-big">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-offset-1 col-sm-9 col-xs-12">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="links">
                            <ul class="stacked-links">
                                <li><big><a href="<?=$muviko->getDomain()?>/categories.php">Categories List</a></big></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="links">
                            <ul class="stacked-links">
                                <li><big>Pages</big></li>
                                <?php
                                    $custom_pages = $muviko->getPages();
                                    while($custom_page = $custom_pages->fetch_object()) {
			                            ?>
                                    <li>
                                        <a href="<?=$muviko->getDomain()?>/page.php?id=<?=$custom_page->id?>">
                                            <?=$custom_page->page_name?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <li>
                                       <a href="login_.php" >Login</a>
                                    </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="links">
                            <ul class="stacked-links">
                                <li><big>Social</big></li>
                                <?php if(!empty($muviko->settings->facebook_url)) { ?>
                                <li>
                                    <a href="<?=$muviko->settings->facebook_url?>" target="_blank">
                            					Facebook
                            				</a>
                                </li>
                                <?php  } ?>
                                    <?php if(!empty($muviko->settings->twitter_url)) { ?>
                                    <li>
                                        <a href="<?=$muviko->settings->twitter_url?>" target="_blank">
                                					Twitter
                                				</a>
                                    </li>
                                    <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="links">
                            <ul class="stacked-links">
                                <?php $statistics = $muviko->getStatistics();

                                    $episode_count = $db->query("SELECT * FROM episodes");
                                ?>
                                <li>
                                    <h4>
                                        <?=$episode_count->num_rows?><br> <small>Episodes</small></h4>
                                </li>
                                <li>
                                    <h4>
                                        <?=$statistics->videos?><br> <small>videos</small></h4>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="copyright">
                    <div class="pull-left">
                        ©
                        2018
                            <?=$muviko->settings->website_name?>
                    </div>
                    <div class="links pull-right">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php } ?>
    <!--  Plugins -->
    <script src="<?=THEME_PATH?>/assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="<?=THEME_PATH?>/assets/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="<?=THEME_PATH?>/assets/bootstrap3/js/bootstrap.js" type="text/javascript"></script>
    <script src="<?=THEME_PATH?>/assets/js/ct-paper-checkbox.js"></script>
    <script src="<?=THEME_PATH?>/assets/js/ct-paper-radio.js"></script>
    <script src="<?=THEME_PATH?>/assets/js/owl.carousel.js"></script>
    <script src="<?=THEME_PATH?>/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?=THEME_PATH?>/assets/js/icomoon.js"></script>
    <script src="<?=THEME_PATH?>/assets/js/chosen.jquery.min.js"></script>
    <script src="<?=THEME_PATH?>/assets/tel-input/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.js"></script>
    <script type="text/javascript">
        window.cookieconsent_options = {
            "message": "This website uses cookies to ensure you get the best experience while using it",
            "dismiss": "Okay",
            "learnMore": "More info",
            "link": null,
            "theme": "light-top"
        };

    </script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            setTimeout(function() {
                $(".add-list-watch").trigger('click');
            }, 900000);
        });

    </script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.10/cookieconsent.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            var page = 1;
            var totalCount = 3;
            var currentSlide = 0;
            /*var optSlider =
                {
                mode : 'vertical',
                infiniteLoop : false,
                hideControlOnEnd : false,
                adaptiveHeight : false,
                pager : false,
                maxSlides : 3,
                minSlides : 2,
                touchEnabled:false,
                controls:true,
                startSlide : currentSlide,
                onSlideAfter: function($slideElement, oldIndex, newIndex) {
                    console.log(oldIndex,newIndex);
                    if(newIndex ==  totalCount-2){
                        console.log();

                        totalCount+=3;
                    }
                 }
            };*/
            //var homeSlider = $('.bx-slider').bxSlider(optSlider);
            var loadingMore = false;
            var homeSection = $('.loaded-content-slider-wrapper .home-section').length;
            var loadingCount = homeSection;
            var sliderCount = 1;
            var totalSlides = 3;
            var heightOfHomeSection = 359;//$('.loaded-content-slider-wrapper .home-section').eq(0).outerHeight();
            $('.load-content').css('min-height',heightOfHomeSection*totalSlides);
            $('.load-more').on('click',function(e){
                e.preventDefault();
                if(totalSlides == loadingCount){
                    console.log(loadingCount);
                   if(!loadingMore){
                        loadingMore = true;
                        var current = $(this);
                        current.find('i').toggleClass('fa-spin fa-spinner fa-angle-down');
                       $.getJSON(base + '/ajax/load_generes.php?page='+page,
                            function(data){
                                console.log(data);
                                page = data.page;
                                if(data.body != ''){
                                   $('.load-content .loaded-content-slider-wrapper').append(data.body);
                                    $('.'+data.items_id).owlCarousel({
                                        navigation: true,
                                        navigationText: [
                                            "<i class='ti-angle-left icon-white'></i>",
                                            "<i class='ti-angle-right icon-white'></i>"
                                        ],
                                        autoplay: false,
                                        loop: true,
                                        items: 4,

                                        margin:10,
                                        responsiveClass:true,
                                        responsive:{
                                            0:{
                                                items:1,
                                                nav:true
                                            },
                                            600:{
                                                items:3,
                                                nav:false
                                            },
                                            1000:{
                                                items:5,
                                                nav:true,
                                                loop:false
                                            }
                                        },
                                        lazyLoad: true,
                                        animateOut: 'slideOutDown',
                                        animateIn: 'flipInX'
                                    }).trigger('add.owl.carousel').trigger('refresh.owl.carousel');
                               }
                                totalSlides+=3;
                                counTotalSlides();
                                current.find('i').toggleClass('fa-spin fa-spinner fa-angle-down');
                                loadingMore = false;
                                $('.loaded-content-slider-wrapper').animate({'top':-(heightOfHomeSection*sliderCount)},500);
                                loadingCount++;
                                sliderCount++;
                            }
                        );

                    }
                }
                else{
                    alert(base);

                    $('.loaded-content-slider-wrapper').animate({'top':-(heightOfHomeSection*sliderCount)},500);
                    sliderCount++;
                    loadingCount++;
                }
            });
            $('.load-prev').on('click',function(e){
                e.preventDefault();
                if(sliderCount > 1 ){
                    sliderCount--;
                    loadingCount--;
                    var firstHomeSec = $('.loaded-content-slider-wrapper');
                    var oldCss = parseFloat((firstHomeSec.css('top')).replace('px',''));
                    firstHomeSec.animate({'top': oldCss+heightOfHomeSection},500);
                }
            });
            function counTotalSlides(){
                totalSlides = $('.load-content .home-section').length;
            }
        });
    </script>
    <!-- my code -->
    <script type="text/javascript">
        jQuery(document).ready(function($){
            var page = 1;
            var totalCount = 3;
            var currentSlide = 0;
            var loadingMore = false;
            var loadingCount = 3;
            var sliderCount = 1;
            var totalSlides = 3;
            $('.next').on('click',function(e){
                e.preventDefault();
                if(totalSlides == loadingCount){
                   if(!loadingMore){
                        loadingMore = true;
                        var current = $(this);
                        current.find('i').toggleClass('fa-spin fa-spinner fa-angle-down');
                       $.getJSON(base + '/ajax/load_test.php?page='+page,
                            function(data){
                                page = data.page;
                                if(data.body != ''){
                                   $('.owl-theme').append(data.body);
                                    $('.'+data.items_id).owlCarousel({
                                        loop:false,
                                        navigation: true,
                                        navigationText: [
                                            "<i class='ti-angle-left icon-white'></i>",
                                            "<i class='ti-angle-right icon-white'></i>"
                                        ],
                                        autoplay: false,
                                        items: 4,
                                        lazyLoad: true,
                                        animateOut: 'slideOutDown',
                                        animateIn: 'flipInX'
                                    }).trigger('add.owl-item').trigger('refresh.owl-item');
                               }

                                totalSlides+=3;
                                counTotalSlides();
                                current.find('i').toggleClass('fa-spin fa-spinner fa-angle-down');
                                loadingMore = false;
                                $('.owl-theme').eq(0).animate({'margin-left':-(300*sliderCount)});
                                loadingCount++;
                                sliderCount++;
                            }
                        );

                    }
                }
                else{
                    $('.owl-theme').eq(0).animate({'margin-top':-(280*sliderCount)});
                    sliderCount++;
                    loadingCount++;
                }
            });
            $('.load-prev').on('click',function(e){
                e.preventDefault();
                if(sliderCount > 1 ){
                    sliderCount--;
                    loadingCount--;
                    var firstHomeSec = $('.load-content .home-section').eq(0);
                    var oldCss = parseFloat((firstHomeSec.css('margin-top')).replace('px',''));
                    firstHomeSec.animate({'margin-top': oldCss+280});
                }
            });
            function counTotalSlides(){
                totalSlides = $('.load-content .home-section').length;
            }

        });
    </script>
    <!-- end my code -->
    <script>
  $(document).ready(function(){
//     $('.owl-carousel').owlCarousel({
//     loop:true,
//     margin:10,
//     responsiveClass:true,
//     responsive:{
//         0:{
//             items:1,
//             nav:true
//         },
//         600:{
//             items:3,
//             nav:false
//         },
//         1000:{
//             items:5,
//             nav:true,
//             loop:false
//         }
//     }
// })
    var html = '';
    // var owl = $('.owl-carousel').owlCarousel({
    //     loop:false,
    //     smartSpeed: 100,
    //     autoplay: false,
    //     autoplaySpeed: 100,
    //     //mouseDrag: false,
    //     margin:10,
    //     //animateIn: 'slideInUp',
    //     //animateOut: 'fadeOut',
    //     //nav:false,
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         600:{
    //             items:3
    //         },
    //         1000:{
    //             items:3
    //         }
    //     }
    // });
    // $.ajax({
    //   url: 'https://randomuser.me/api/?results=20&gender=male&nat=us',
    //   dataType: 'json',
    //   success: function(data) {
    //     console.log(data.results);
    //     $.each(data.results, function(k, v) {
    //       var random_num =  Math.floor(Math.random()*60);
    //       owl.trigger('add.owl.carousel', [jQuery('<div class="notification-message"> <img src="'+v.picture.thumbnail+'" class="user-image" alt=""> <div class="user-name">'+ v.name.first+' '+v.name.last+' <span class="lighter">from '+v.location.city+'</span></div> <div class="bought-details">Bought This <br>'+random_num+' minutes ago</div> </div>')]);
    //   });
    //   owl.trigger('refresh.owl.carousel');
    //   }
    // });

  });
</script>
    <!-- Main JS -->
    <script src="<?=THEME_PATH?>/assets/js/app.js"></script>
    <script src="<?=THEME_PATH?>/assets/js/theme.js"></script>
    <?=isset($page['js'])?$page['js']:''?>
        <?php

if((isset($_GET['success']) && $_GET['success'] !='') || (isset($_GET['error']) && $_GET['error'] !='')){
    $title = isset($_GET['success'])?'Success':'Error';
    $msg = isset($_GET['success'])?$_GET['success']:$_GET['error'];
    $class = isset($_GET['success'])?'success':'error';
    ?>
    <div class="global-msg <?=$class?>-msg">
        <div class="gm-content">
            <div class="gm-header">
                <h2><?=$title?></h2>
                <span class="gm-close"><i class="fa fa-times"></i> </span>
            </div>
            <div class="gm-body">
                <p><?=$msg?></p>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($){
           $('.gm-close').on('click',function(e){
               e.preventDefault();
               $('.global-msg').fadeOut(500);
               clean_uri = location.protocol + "//" + location.host + location.pathname;
               window.history.replaceState({}, document.title, clean_uri);
           });
           $('.load-content').on('click','.o-next',function(e){
             e.preventDefault();
             var c = $(this),p = c.parents('.home-section'), page = p.attr('page')+1,mi = p.attr('mi');
             console.log(p,page,mi);
             // $.getJSON(base + '/ajax/load_generes_movies.php?p='+page+'&mi='+mi,
             //      function(data){
             //        console.log(data);
             //          p.attr('page',data.page)
             //          if(data.body != ''){
             //             p.find('.owl-wrapper-outer .owl-wrapper').append(data.body);
             //             var owl = $(".movie-slider-1").data('owlCarousel');
             //              owl.trigger('refresh.owl.carousel');
             //         }
             //      }
             //  );
           });
        });
    </script>
    <?php
}
?>
        <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content payment-modal">
<div class="modal-body">
<div class="panel panel-danger">
	<div class="panel-heading">
		<div class="panel-title">
			<span class="changing-form"><?=$muviko->translate('Sign_In')?></span>
            <span class="changing-form hidden">Forgot Password</span>
		</div>
	</div>
	<div class="panel-body">
        <div class="changing-form" id="login-form">
            <form action="<?=$muviko->getDomain()?>/login.php" method="post">
                <div class="form-group">
                    <label>
                        <span><?=$muviko->translate('Email')?></span>
                    </label>
                    <input type="email" name="email" required class="form-control" autocomplete="new-password" >
                </div>
                <div class="form-group">
                    <label>
                        <span><?=$muviko->translate('Password')?></span>
                    </label>
                    <input type="password" name="password" required class="form-control"  autocomplete="new-password">
                </div>
                <button type="submit" name="login" class="btn btn-danger btn-fill pull-right"><?=$muviko->translate('Sign_In')?></button>
                <a href="<?=$muviko->getDomain()?>/register.php" class="btn btn-default btn-fill pull-right" style="margin-right:5px;"><?=$muviko->translate('Create_Account')?></a>
                <a href="#" class="btn btn-default btn-fill pull-right form-changing" id="f" style="margin-right:5px;">Forgot Password</a>
            </form>
        </div>
        <div class="hidden changing-form" id="forgot">
            <form action="<?=$muviko->getDomain()?>/forgot-password.php" method="post">
                <div class="form-group">
                    <label>
                        <span><?=$muviko->translate('Email')?></span>
                    </label>
                    <input type="email" name="email" required class="form-control" autocomplete="new-password" >
                </div>
                <button type="submit" name="forgot" class="btn btn-danger btn-fill pull-right">Forgot Password</button>
                <a href="<?=$muviko->getDomain()?>/register.php" class="btn btn-default btn-fill pull-right" style="margin-right:5px;"><?=$muviko->translate('Create_Account')?></a>
                <a href="#login" class="btn btn-default btn-fill pull-right form-changing" id="l" style="margin-right:5px;"><?=$muviko->translate('Sign_In')?></a>
            </form>
        </div>
	</div>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
        $(document).ready(function() {
            $("#f").click(function () {
          $('#forgot').removeClass('hidden');
          $('#login-form').addClass('hidden');
            });
            $("#l").click(function () {
          $('#forgot').addClass('hidden');
          $('#login-form').removeClass('hidden');
            });

        });
    </script>
    <style>
        /* common */
       /* .owl-item{
            width: 100% !important;
        margin-right: 10px !important;
    }*/
.ribbon {
  width: 175px;
  height: 118px;
  overflow: hidden;
  position: absolute;
  margin-left: 15px;
}
.ribbon::before,
.ribbon::after {
  position: absolute;
  z-index: -1;
  content: '';
  display: block;
  border: 5px solid #2980b9;
}
.ribbon span {
  position: absolute;
  display: block;
  width: 310px;
  padding: 9px 0;
  background-color: #3498db;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
  color: #fff;
  font: 700 18px/1 'Lato', sans-serif;
  text-shadow: 0 1px 1px rgba(0,0,0,.2);
  text-transform: uppercase;
  text-align: center;
}

/* top left*/
.ribbon-top-left {
  top: -10px;
  left: -10px;
}
.ribbon-top-left::before,
.ribbon-top-left::after {
  border-top-color: transparent;
  border-left-color: transparent;
}
.ribbon-top-left::before {
  top: 0;
  right: 0;
}
.ribbon-top-left::after {
  bottom: 0;
  left: 0;
}
.ribbon-top-left span {
  right: -25px;
  top: 30px;
  transform: rotate(-45deg);
}

    </style>
    </body>
</html>
