<?php

class Layout extends Data
{
	public function newHomeMovieItem($movie_id, $movie_thumb_image, $movie_name, $movie_year, $movie_rating, $is_series, $last_season, $watch, $echo = true)
	{
		$picture = $this->getUploadsPath() . '/poster_images/' . $movie_thumb_image;
		//$uid = $muviko->user->id;
		$check = $this->db->query("SELECT * FROM my_watched WHERE movie_id='" . $movie_id . "' AND user_id='" . $this->user_id . "'");
		$style = '';
		if (!@getimagesize($picture)) {
	  	$picture = 	getposterImg($movie_id);
		//	$picture = $this->getDomain().'/images/default-poster.jpg';//$this->getUploadsPath() . '/masonry_images/' . $movie_thumb_image;
		}
		$output = '<div class="item">';

		$output .='
		<a href="' . $this->getDomain() . '/titles.php?id=' . $movie_id . '">
		<img src="' . $picture . '"> ';

		if ($check->num_rows != 0) {
			$output .= '<div class="ribbon ribbon-top-left"><span>watched</span></div>';
		}
		$output .= '<div class="play">
		<i class="icon icon-play3"></i>
		</div>
		</a>
		<div class="title">' .$movie_name.' ('.$movie_year.')'.' </div>
		';
		$output .= '</div>';
		if ($echo) {
			echo $output;
		}
		return $output;
	}
	public function newHomeMovieItem_mylist($movie_id, $movie_thumb_image, $movie_name, $movie_year, $movie_rating, $is_series, $last_season, $echo = true)
	{
		$picture = $this->getUploadsPath() . '/poster_images/' . $movie_thumb_image;
		$style = '';
		if (!@getimagesize($picture)) {
			$picture = $this->getUploadsPath() . '/masonry_images/' . $movie_thumb_image;
		}
		$output = '<div class="item">';
		$output .=
			'<a href="' . $this->getDomain() . '/my_list.php?delid=' . $movie_id . '" class="alink" id="alinkid"><i class="fa fa-close"></i></a>
		<a href="' . $this->getDomain() . '/titles.php?id=' . $movie_id . '">
		<img src="' . $picture . '">
		<div class="play">
		<i class="icon icon-play3"></i>
		</div>
		</a>
		<div class="title"> ' . $movie_name . ' </div>
		';
		$output .= '</div>';
		if ($echo) {
			echo $output;
		}
		return $output;
	}
	public function newHomeMovieItem1($movie_id, $movie_thumb_image, $movie_name)
	{
		echo '<div class="item">';
		$picture = $this->getUploadsPath() . '/masonry_images/' . $movie_thumb_image;
		if ($movie_thumb_image == '') {
			$picture = $this->getDomain() . '/images/default-image.png';
		}
		echo
			'
		<a href="' . $this->getDomain() . '/episode/' . $movie_id . '">
		<img src="' . $picture . '">
		<div class="play">
		<i class="icon icon-play3"></i>
		</div>
		</a>
		<div class="title"> ' . $movie_name . ' </div>
		';
		echo '</div>';
	}

	public function newHomeActorItem($movie_id, $movie_name, $movie_thumb_image)
	{
		echo '<div class="item">';
		$picture = $this->getUploadsPath() . '/actors/' . $movie_thumb_image;
		if ($movie_thumb_image == '') {
			$picture = $this->getDomain() . '/images/default-user.png';
		}
		echo
			'
		<a href="' . $this->getDomain() . '/actor_profile.php?name=' . $movie_id . '">
		<img src="' . $picture . '">

		</a>
		<div class="title"> ' . $movie_name . ' </div>
		';
		echo '</div>';
	}


	public function homeSectionHeading($name, $url, $echo = true)
	{if($name==""){
	    $name = "Series";
	}
		$output = '
		<h1>
		<a href="' . $url . '">
		' . $name . '
		<i class="ti-angle-right"></i>
		</a>
		</h1>';
		if ($echo) {
			echo $output;
		}
		return $output;
	}



	public function newAddListBtn1($movie_id, $color,$ep_id=null)
	{
		if (!$this->subscriberAction()) {
			$url = $this->getDomain() . '/register.php';
			$onclick = '';
		} else {
			$url = '#';
			$onclick = 'markaswatched(' . $movie_id . '); return false;';
		}
		$movietypequery =  $this->db->query("SELECT is_series FROM movies WHERE id='" . $movie_id . "' ");
		$movietype = $movietypequery->fetch_object();

		if($movietype->is_series==1){

		   $check = $this->db->query("SELECT * FROM my_watched_episodes WHERE movie_id='".$movie_id."' AND user_id='".$this->user_id."' AND episode_id='".$ep_id."' LIMIT 1");

		}else{
			$check = $this->db->query("SELECT * FROM my_watched WHERE movie_id='" . $movie_id . "' AND user_id='" . $this->user_id . "'  LIMIT 1");

		}

		if ($check->num_rows >= 1) {
			echo '
			<a href="' . $url . '" class="btn btn-neutral btn-neutral add-list-watch" onclick="' . $onclick . '">
			<i class="ti-check"></i>
			<span> Remove Watched </span>
			</a>';
		} else {
			echo '<a href="' . $url . '" class="btn btn-neutral btn-neutral add-list-watch" onclick="' . $onclick . '">
			<i class="ti-plus"></i>
			<span> Mark as Watched </span>
			</a>';
		}
	}



	public function newAddListBtn111($movie_id,$color,$ep_no)
	{
		if (!$this->subscriberAction()) {
			$url = $this->getDomain() . '/register.php';
			$onclick = '';
		} else {
			$url = '#';
			$onclick = "markaswatched111111(".$movie_id.",'".$ep_no."'); return false;";
		}

		$episode = $this->db->query("SELECT * FROM episodes WHERE id='".$movie_id."'");
		$episode = $episode->fetch_object();
		$movie_id = $episode->movie_id;
    $ep_id = $episode->id;
		$movietypequery =  $this->db->query("SELECT is_series FROM movies WHERE id='" . $movie_id . "' ");
		$movietype = $movietypequery->fetch_object();

		if($movietype->is_series==1){
		 $check = $this->db->query("SELECT * FROM my_watched_episodes WHERE movie_id='".$movie_id."' AND user_id='".$this->user_id."' AND episode_id='".$ep_id."' LIMIT 1");
  	}else{
		//	$check = $this->db->query("SELECT * FROM my_watched WHERE movie_id='" . $movie_id . "' AND user_id='" . $this->user_id . "'   AND type='episode'   LIMIT 1");
  	}

		if ($check->num_rows >= 1) {
			echo '
			<a href="' . $url . '" class="btn btn-neutral btn-neutral add-list-watch" onclick="' . $onclick . '">
			<i class="ti-check"></i>
			<span> Remove Watched </span>
			</a>';
		} else {
			echo '<a href="' . $url . '" class="btn btn-neutral btn-neutral add-list-watch" onclick="' . $onclick . '">
			<i class="ti-plus"></i>
			<span> Mark as Watched </span>
			</a>';
		}
	}
	public function nextSeason($season_id)
	{
		if ($this->subscriberAction()) {
			$episode_id = $this->db->query("SELECT id FROM episodes WHERE season_id=$season_id ORDER BY id ASC LIMIT 1")->fetch_assoc()['id'];
			echo '<a href="' . $this->getDomain() . '/episode.php?id=' . $episode_id . '" class="btn btn-neutral btn-neutral">
				<span> Next Season </span>
				<i class="ti-arrow-right"></i>
			</a>';
		}
	}
	public function prevSeason($season_id)
	{
		if ($this->subscriberAction()) {
			$episode_id = $this->db->query("SELECT id FROM episodes WHERE season_id=$season_id ORDER BY id DESC LIMIT 1")->fetch_assoc()['id'];
			echo '<a href="' . $this->getDomain() . '/episode.php?id=' . $episode_id . '" class="btn btn-neutral btn-neutral">
				<i class="ti-arrow-left"></i>
				<span> Previous Season </span>

			</a>';
		}
	}
	public function nextEpisode($episodeId, $color)
	{
		if ($this->subscriberAction()) {
			echo '<a href="' . $this->getDomain() . '/episode.php?id=' . $episodeId . '" class="btn btn-neutral btn-neutral">
				<span> Next Episode </span>
				<i class="ti-arrow-right"></i>
			</a>';
		}
	}
	public function prevEpisode($episodeId, $color)
	{
		if ($this->subscriberAction()) {
			echo '<a href="' . $this->getDomain() . '/episode.php?id=' . $episodeId . '" class="btn btn-neutral btn-neutral">
				<i class="ti-arrow-left"></i>
				<span> Previous Episode </span>

			</a>';
		}
	}
	public function newAddListBtn($movie_id, $color)
	{
		if (!$this->subscriberAction()) {
			$url = $this->getDomain() . '/register.php';
			$onclick = '';
		} else {
			$url = '#';
			$onclick = 'addToList(' . $movie_id . '); return false;';
		}
		$profile_id = isset($_SESSION['fl_profile']) ? $_SESSION['fl_profile'] : '';
		$check = $this->db->query("SELECT * FROM my_list WHERE movie_id='" . $movie_id . "' AND user_id='" . $this->user_id . "' AND profile_id='" . $profile_id . "' LIMIT 1");
		$class = '';
		if ($color == 'white') {
			$class = 'btn-neutral';
		} elseif ($color == 'red') {
			$class = 'btn-danger btn-fill';
		} elseif ($color == 'green') {
			$class = 'btn-success btn-fill';
		}
		if ($check->num_rows >= 1) {
			echo '
			<a href="' . $url . '" class="btn ' . $class . ' add-list" onclick="' . $onclick . '">
			<i class="ti-check"></i>
			<span> ' . $this->translate('Added_List') . ' </span>
			</a>';
		} else {
			echo '<a href="' . $url . '" class="btn ' . $class . ' add-list" onclick="' . $onclick . '">
			<i class="ti-plus"></i>
			<span> ' . $this->translate('Add_List') . ' </span>
			</a>';
		}
	}
	public function newAddListBtn11($movie_id, $color)
	{
		if (!$this->subscriberAction()) {
			$url = $this->getDomain() . '/register.php';
			$onclick = '';
		} else {
			$url = '#';
			$onclick = 'addToList1(' . $movie_id . '); return false;';
		}
		$check = $this->db->query("SELECT * FROM my_list WHERE movie_id='" . $movie_id . "' AND user_id='" . $this->user_id . "' AND profile_id='" . $_SESSION['fl_profile'] . "' AND type= 'episode' LIMIT 1");
		if ($color == 'white') {
			$class = 'btn-neutral';
		} elseif ($color == 'red') {
			$class = 'btn-danger btn-fill';
		} elseif ($color == 'green') {
			$class = 'btn-success btn-fill';
		}
		if ($check->num_rows >= 1) {
			echo '
			<a href="' . $url . '" class="btn ' . $class . ' add-list" onclick="' . $onclick . '">
			<i class="ti-check"></i>
			<span> ' . $this->translate('Added_List') . ' </span>
			</a>';
		} else {
			echo '<a href="' . $url . '" class="btn ' . $class . ' add-list" onclick="' . $onclick . '">
			<i class="ti-plus"></i>
			<span> ' . $this->translate('Add_List') . ' </span>
			</a>';
		}
	}
}

class Muviko extends Layout
{
	public function getProfilePicture()
	{
		$profile = $this->db->query("SELECT profile_avatar FROM profiles WHERE id='" . $_SESSION['fl_profile'] . "'");
		$profile = $profile->fetch_object();
		return $this->getThemePath() . '/assets/images/avatars/' . $profile->profile_avatar . '.png';
	}

	public function getProfiles()
	{
		$profiles = $this->db->query("SELECT * FROM profiles WHERE user_id='" . $this->user_id . "'");
		return $profiles;
	}

	public function getProfile($profile_id)
	{
		$profile = $this->db->query("SELECT * FROM profiles WHERE id='" . $profile_id . "'");
		$profile = $profile->fetch_object();
		return $profile;
	}

	public function setProfile($profile_id, $is_kid)
	{
		$this->db->query("UPDATE sessions SET profile_id='" . $profile_id . "' WHERE session_id='" . $this->session_id . "'");
		$this->db->query("UPDATE users SET last_profile='" . $profile_id . "' WHERE id='" . $this->user_id . "'");
		$this->profile_id = $profile_id;
		$_SESSION['fl_profile'] = $profile_id;
		if ($is_kid >= 1) {
			$_SESSION['is_kid'] = '1';
		} else {
			unset($_SESSION['is_kid']);
		}
	}

	public function getSections($limit, $page)
	{
		return $this->getGenres($limit, false, $page);
	}
	public function get_total_records($query)
	{
		$count = $this->db->query($query);
		return $count->num_rows;
	}
	public function searchMovie($query)
	{
		if (isset($_SESSION['is_kid'])) {
			return $this->db->query("SELECT * FROM movies WHERE (movie_name LIKE '%{$query}%' OR imdbid LIKE '%{$query}%' OR alternative_titles LIKE '%{$query}%' ) AND is_kid_friendly='1'");
		} else {
			return $this->db->query("SELECT * FROM movies WHERE (movie_name LIKE '%{$query}%' OR imdbid LIKE '%{$query}%' OR alternative_titles LIKE '%{$query}%' )");
		}
	}

	public function searchActors($query)
	{
		return $this->db->query("SELECT * FROM actors WHERE actor_name LIKE '%" . $query . "%'");
	}

	public function getMyList()
	{
		$profile_id = isset($_SESSION['fl_profile']) ? $_SESSION['fl_profile'] : '';
		return $this->db->query("SELECT * FROM my_list WHERE user_id='" . $this->user_id . "' AND profile_id='" . $profile_id . "' ORDER BY id DESC");
	}
}
