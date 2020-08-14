<?php

class Layout {

	public $theme_path;

	function __construct($theme_path) {
		$this->theme_path = $theme_path;
	}

	public function newHomeMovieItem($movie_id,$movie_thumb_image,$movie_name,$movie_year,$movie_rating,$is_series,$last_season) {
		echo 
		'
		<div class="item">
		<a href="#" onclick="openMoviePreview('.$movie_id.','.$is_series.'); return false;">
		<img src="'.$this->theme_path.'/assets/images/masonry_images/'.$movie_thumb_image.'">
		</a>
		<div class="title"> '.$movie_name.' </div>
		<i class="play icon icon-play2"></i>
		';
		echo '</div>';
	}
		public function newHomeMovieItem_mylist($movie_id,$movie_thumb_image,$movie_name,$movie_year,$movie_rating,$is_series,$last_season) {
		echo 
		'
		<div class="item">
		<a href="#" onclick="openMoviePreview('.$movie_id.','.$is_series.'); return false;">
		<img src="'.$this->theme_path.'/assets/images/masonry_images/'.$movie_thumb_image.'">
		</a>
		<div class="title"> '.$movie_name.' </div>
		<i class="play icon icon-play2"></i>
		';
		echo '</div><a href="'.$url.'">delete</a>';
	}
	
	public function homeSectionHeading($name,$url) {
		echo '<h1><a href="'.$url.'">'.$name.' <i class="fa fa-chevron-right"></i></a></h1>';
	}

}

