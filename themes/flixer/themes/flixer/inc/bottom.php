<?php if(isset($page['footer']) && $page['footer'] == true) { ?>
<footer class="footer footer-black footer-big">
<div class="container">
<div class="row">
<div class="col-md-9 col-md-offset-1 col-sm-9 col-xs-12">
<div class="row">
<div class="col-md-3 col-sm-3 col-xs-6">
<div class="links">
<ul class="stacked-links">
	<li><big>Categories</big></li>
	<?php
	$categories = $muviko->getGenres(5,false);
	while($category = $categories->fetch_object()) {
		?>
		<li>
			<a href="<?=$muviko->getDomain()?>/category/<?=$category->id?>/<?=strtolower($category->genre_name)?>">
				<?=$category->genre_name?>
			</a>
		</li>
		<? } ?>
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
				<a href="<?=$muviko->getDomain()?>/page/<?=$custom_page->id?>">
					<?=$custom_page->page_name?>
				</a>
			</li>
			<li>
				<a href="<?=$muviko->getDomain()?>/login_.php">
					Login
				</a>
			</li> 
			<?php } ?>
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
			<? } ?>
			<?php if(!empty($muviko->settings->twitter_url)) { ?>
			<li>
				<a href="<?=$muviko->settings->twitter_url?>" target="_blank">
					Twitter
				</a>
			</li>
			<? } ?>
		</ul>
	</div>
</div>
<div class="col-md-3 col-sm-3 col-xs-6">
	<div class="links">
		<ul class="stacked-links">
			<?php $statistics = $muviko->getStatistics(); ?>
			<li>
				<h4><?=$statistics->users?><br> <small>users</small></h4>
			</li>
			<li>
				<h4><?=$statistics->videos?><br> <small>videos</small></h4>
			</li>
		</ul>
	</div>
</div>
</div>
<hr>
<div class="copyright">
<div class="pull-left">
	© <?=date('Y')?> <?=$muviko->settings->website_name?>
</div>
<div class="links pull-right">
</div>
</div>
</div>
</div>
</div>
</footer>
<? } ?>
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
window.cookieconsent_options = {"message":"This website uses cookies to ensure you get the best experience while using it","dismiss":"Okay","learnMore":"More info","link":null,"theme":"light-top"};
</script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.10/cookieconsent.min.js"></script>
</script>
<!-- Main JS -->
<script src="<?=THEME_PATH?>/assets/js/app.js"></script>
<script src="<?=THEME_PATH?>/assets/js/theme.js"></script>
<?=$page['js']?>
</body>
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content payment-modal">
<div class="modal-body">
<div class="panel panel-danger">
	<div class="panel-heading">
		<div class="panel-title">
			<?=$muviko->translate('Sign_In')?>
		</div>
	</div>
	<div class="panel-body">
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
			<a href="<?=$muviko->getDomain()?>/register" class="btn btn-default btn-fill pull-right" style="margin-right:5px;"><?=$muviko->translate('Create_Account')?></a>
		</form>
	</div>
</div>
</div>
</div>
</div>
</div>
</html>
