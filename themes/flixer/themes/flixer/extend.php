<?php

class Layout extends Data {
	public function newHomeMovieItem($movie_id,$movie_thumb_image,$movie_name,$movie_year,$movie_rating,$is_series,$last_season) {
		$picture = $this->getUploadsPath().'/masonry_images/'.$movie_thumb_image;
		echo "bbbb";
		echo $picture;
		$style = '';
		if($movie_thumb_image == '' or !file_exists($picture)){
			$picture = $this->getDomain().'/images/default-poster.jpg';
		}
		echo '<div class="item">';
		echo 
		'
		<a href="'.$this->getDomain().'/titles.php?id='.$movie_id.'">
		<img src="'.$picture.'">
		<div class="play">
		<i class="icon icon-play3"></i>
		</div>
		</a>
		<div class="title"> '.$movie_name.' </div>
		';
		echo '</div>';
	}
	public function newHomeMovieItem1($movie_id,$movie_thumb_image,$movie_name) {
		echo '<div class="item">';
		$picture = $this->getUploadsPath().'/episodes/'.$movie_thumb_image;
		if($movie_thumb_image == '' or !file_exists($picture)){
			$picture = $this->getDomain().'/images/default-poster.jpg';
		}
		echo 
		'
		<a href="'.$this->getDomain().'/episode/'.$movie_id.'">
		<img src="'.$picture.'">
		<div class="play">
		<i class="icon icon-play3"></i>
		</div>
		</a>
		<div class="title"> '.$movie_name.' </div>
		';
		echo '</div>';
	}
	
	public function newHomeActorItem($movie_id,$movie_name,$movie_thumb_image) {
		echo '<div class="item">';
		echo 
		'
		<a href="'.$this->getDomain().'/actor_profile.php?name='.$movie_id.'">
		<img src="'.$this->getUploadsPath().'/actors/'.$movie_thumb_image.'">
	
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


	
	public function newAddListBtn1($movie_id,$color) {
		if(!$this->subscriberAction()) {
			$url = $this->getDomain().'/register';
			$onclick = '';
		} else {
			$url = '#';
			$onclick = 'markaswatched('.$movie_id.'); return false;';
		}
		$check = $this->db->query("SELECT * FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$this->user_id."'  LIMIT 1");
		
		if($check->num_rows >= 1) {
			echo '
			<a href="'.$url.'" class="btn btn-neutral btn-neutral add-list-watch" onclick="'.$onclick.'">
			<i class="ti-check"></i> 
			<span> Remove Watched </span> 
			</a>';
		} else {
			echo '<a href="'.$url.'" class="btn btn-neutral btn-neutral add-list-watch" onclick="'.$onclick.'">
			<i class="ti-plus"></i> 
			<span> Mark as Watched </span>
			</a>';
		}
	}

	public function newAddListBtn111($movie_id,$color) {
		if(!$this->subscriberAction()) {
			$url = $this->getDomain().'/register';
			$onclick = '';
		} else {
			$url = '#';
			$onclick = 'markaswatched111111('.$movie_id.'); return false;';
		}
		$check = $this->db->query("SELECT * FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$this->user_id."'   AND type='episode'   LIMIT 1");
		
		if($check->num_rows >= 1) {
			echo '
			<a href="'.$url.'" class="btn btn-neutral btn-neutral add-list-watch" onclick="'.$onclick.'">
			<i class="ti-check"></i> 
			<span> Remove Watched </span> 
			</a>';
		} else {
			echo '<a href="'.$url.'" class="btn btn-neutral btn-neutral add-list-watch" onclick="'.$onclick.'">
			<i class="ti-plus"></i> 
			<span> Mark as Watched </span>
			</a>';
		}
	}
	public function nextEpisode($episodeId,$color){
		if($this->subscriberAction()) {
			echo '<a href="'.$this->getDomain().'/episode/'.$episodeId.'" class="btn btn-neutral btn-neutral">
				<span> Next Episode </span>
				<i class="ti-arrow-right"></i> 
			</a>';
		}
	}
	public function prevEpisode($episodeId,$color){
		if($this->subscriberAction()) {
			echo '<a href="'.$this->getDomain().'/episode/'.$episodeId.'" class="btn btn-neutral btn-neutral">
				<i class="ti-arrow-left"></i> 
				<span> Previous Episode </span>
				
			</a>';
		}
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
	
	
	public function newAddListBtn11($movie_id,$color) {
		if(!$this->subscriberAction()) {
			$url = $this->getDomain().'/register';
			$onclick = '';
		} else {
			$url = '#';
			$onclick = 'addToList1('.$movie_id.'); return false;';
		}
		$check = $this->db->query("SELECT * FROM my_list WHERE movie_id='".$movie_id."' AND user_id='".$this->user_id."' AND profile_id='".$_SESSION['fl_profile']."' AND type= 'episode' LIMIT 1");
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
			return $this->db->query("SELECT * FROM movies WHERE (movie_name LIKE '%".$query."%' || imdbid =  '".$query."' ) AND is_kid_friendly='1'");
		} else {
			return $this->db->query("SELECT * FROM movies WHERE (movie_name LIKE '%".$query."%' || imdbid =  '".$query."' )");	
		}
	}
	
	public function searchActors($query){
		return $this->db->query("SELECT * FROM actors WHERE actor_name LIKE '%".$query."%'");	
	}

	public function getMyList() {
		return $this->db->query("SELECT * FROM my_list WHERE user_id='".$this->user_id."' AND profile_id='".$_SESSION['fl_profile']."' ORDER BY id DESC");
	}
}