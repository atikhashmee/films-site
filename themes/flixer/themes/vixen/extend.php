<?php

class Layout extends Data {
	public function newHomeMovieItem($movie_id,$movie_thumb_image,$movie_name,$movie_year,$movie_rating,$is_series,$last_season) {
		echo '<div class="item">';
		echo 
		'
		<a href="'.$this->getDomain().'/video/'.$movie_id.'">
		<img src="'.$this->getUploadsPath().'/masonry_images/'.$movie_thumb_image.'">
		<div class="play">
		<i class="icon icon-play3"></i>
		</div>
		</a>
		<div class="title"> '.$movie_name.' </div>
		';
		echo '</div>';
	}
	
	public function homeSectionHeading($name,$url) {
		echo '
		<h1>
		<a href="'.$url.'">
		'.$name.' 
		<i class="ti-angle-right"></i>
		</a>
		</h1>';
	}

	public function newAddListBtn($movie_id,$color) {
		if(!$this->subscriberAction()) {
			$url = $this->getDomain().'/register';
			$onclick = '';
		} else {
			$url = '#';
			$onclick = 'addToList('.$movie_id.'); return false;';
		}
		$check = $this->db->query("SELECT * FROM my_list WHERE movie_id='".$movie_id."' AND user_id='".$this->user_id."' AND profile_id='".$_SESSION['fl_profile']."' LIMIT 1");
		if($color == 'white') {
			$class = 'btn-neutral';
		} elseif($color == 'red') {
			$class = 'btn-danger btn-fill';
		} elseif($color == 'green') {
			$class = 'btn-success btn-fill';
		}
		if($check->num_rows >= 1) {
			echo '
			<a href="'.$url.'" class="btn '.$class.' add-list" onclick="'.$onclick.'">
			<i class="ti-check"></i> 
			<span> '.$this->translate('Added_List').' </span> 
			</a>';
		} else {
			echo '<a href="'.$url.'" class="btn '.$class.' add-list" onclick="'.$onclick.'">
			<i class="ti-plus"></i> 
			<span> '.$this->translate('Add_List').' </span>
			</a>';
		}
	}
}

class Muviko extends Layout {
	public function getProfilePicture() {
		$profile = $this->db->query("SELECT profile_avatar FROM profiles WHERE id='".$_SESSION['fl_profile']."'");
		$profile = $profile->fetch_object();
		return $this->getThemePath().'/assets/images/avatars/'.$profile->profile_avatar.'.png';
	}

	public function getProfiles() {
		$profiles = $this->db->query("SELECT * FROM profiles WHERE user_id='".$this->user_id."'");
		return $profiles;
	}

	public function getProfile($profile_id) {
		$profile =$this->db->query("SELECT * FROM profiles WHERE id='".$profile_id."'");
		$profile = $profile->fetch_object();
		return $profile;
	}

	public function setProfile($profile_id,$is_kid) {
		$this->db->query("UPDATE sessions SET profile_id='".$profile_id."' WHERE session_id='".$this->session_id."'");
		$this->db->query("UPDATE users SET last_profile='".$profile_id."' WHERE id='".$this->user_id."'");
		$this->profile_id = $profile_id;
		$_SESSION['fl_profile'] = $profile_id;
		if($is_kid >= 1) { 
			$_SESSION['is_kid'] = '1'; 
		} else {
			unset($_SESSION['is_kid']); 
		}
	}

	public function getSections() {
		return $this->getGenres(false,false);
	}

	public function searchMovie($query){
		if(isset($_SESSION['is_kid'])) {
			return $this->db->query("SELECT * FROM movies WHERE movie_name LIKE '%".$query."%' AND is_kid_friendly='1'");
		} else {
			return $this->db->query("SELECT * FROM movies WHERE movie_name LIKE '%".$query."%'");	
		}
	}

	public function getMyList() {
		return $this->db->query("SELECT * FROM my_list WHERE user_id='".$this->user_id."' AND profile_id='".$_SESSION['fl_profile']."' ORDER BY id DESC");
	}
}