<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'>
    <meta name="viewport" content="width=device-width" />
    <title><?=$page['name']?></title>
    <link rel="shortcut icon" type="image/png" href="<?=$muviko->getDomain()?>/img/favicon.png">
    <!-- Plugins -->
    <link href="<?=THEME_PATH?>/assets/bootstrap3/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=THEME_PATH?>/assets/css/owl.carousel.css">
    <link rel="stylesheet" href="<?=THEME_PATH?>/assets/css/owl.transitions.css">
    <link href="<?=THEME_PATH?>/assets/css/jquery.mCustomScrollbar.min.css" rel="stylesheet">
    <script src="<?=THEME_PATH?>/assets/jwplayer/jwplayer.js"></script>
    <link href="<?=THEME_PATH?>/assets/css/animate.css" rel="stylesheet">
    <link href="<?=THEME_PATH?>/assets/css/chosen.min.css" rel="stylesheet">
    <link href="<?=THEME_PATH?>/assets/css/chosen-bootstrap.css" rel="stylesheet">
    <link href="<?=THEME_PATH?>/assets/tel-input/css/intlTelInput.css" rel="stylesheet">
    <script src="<?=THEME_PATH?>/assets/js/nprogress.js"></script>
    <link href="<?=THEME_PATH?>/assets/css/nprogress.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <!-- Main CSS -->
    <link href="<?=THEME_PATH?>/assets/css/plugins.css" rel="stylesheet">
    <link href="<?=THEME_PATH?>/assets/css/theme.css" rel="stylesheet">
    <link href="<?=THEME_PATH?>/assets/css/style.css" rel="stylesheet">
    <!--  Fonts and icons     -->
    <link href="<?=THEME_PATH?>/assets/css/icomoon.css" rel="stylesheet" type="text/css" />
    <link href="<?=THEME_PATH?>/assets/css/themify-icons.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script>
    var base = '<?=THEME_PATH?>';
    var uploads_path = '<?=UPLOADS_PATH?>'
    var user_id = '<?=$muviko->user->id?>';
    jwplayer.key="ZyhJxcZOVHN31Dyb/lJ3yKqsIxUDlXfTiPdsdw==";
    NProgress.start();
    </script>
</head>
<body <?php if(isset($page['footer']) && $page['footer'] == false) { echo 'class="no-footer"'; } ?>>