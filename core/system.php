<?php
define("UPLOAD_PATH",dirname(dirname(__FILE__))."/uploads/");
class Core {
	public $db;
	public $domain;
	public $settings;
	public $language_array;

	function __construct($db,$domain) {
		$this->db = $db;
		$this->domain = $domain;
		$this->getSettings();
	}

	public function getDomain() {
		return $this->domain;
	}

	public function hashPassword($password) {
		return hash('sha512',$password);
	}

	public function getSettings() {
		$settings = $this->db->query("SELECT * FROM settings");
		$settings = $settings->fetch_object();
		$this->settings = $settings;
	}

	public function getUploadsPath() {
		return $this->domain.'/uploads';
	}

	public function getExtendPath($in_theme_dir=false) {
		$settings = $this->settings;
		if($in_theme_dir == false) {
			return 'themes/'.$settings->theme.'/extend.php';
		} else {
			return '../extend.php';
		}
	}

	public function getHeaderPath() {
		$settings = $this->settings;
		return 'themes/'.$settings->theme.'/inc/top.php';
	}

	public function getPagePath($page) {
		$settings = $this->settings;
		return 'themes/'.$settings->theme.'/layout/'.$page.'.php';
	}

	public function getFooterPath() {
		$settings = $this->settings;
		return 'themes/'.$settings->theme.'/inc/bottom.php';
	}

	public function getThemePath() {
		$settings = $this->settings;
		return $this->domain.'/themes/'.$settings->theme;
	}

	public function getLanguage($sub_dir='') {
		$path = dirname(dirname(__FILE__));
		if(isset($_SESSION['fl_language'])) {
			$language = $path.'/languages/'.$_SESSION['fl_language'].'/language.php';
		} else {
			$language = $path.'/languages/'.$this->settings->default_language.'/language.php';
		}
		require($language);
		$this->language_array = $lang;
	}

	public function translate($str) {
		return $this->language_array[$str];
	}

	public function getCountryByIP($ip) {
		$html = file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip);
		$html = unserialize($html);
		return $html['geoplugin_countryName'];
	}
}

class Session extends Core {
	public $session_id;
	public $user_id;
	public $user_email;
	public $user_name;
	public $user_phone;
	public $profile_id;
	public $is_admin;
	public $user;

	public function generateSessionID() {
		return substr(md5(time()),0,10);
	}

	public function startUserSession($session) {
		if(!empty($session['fl_session_id'])) {
			$_SESSION['fl_session_id'] = $this->session_id = $session['fl_session_id'];
			$_SESSION['fl_user_id'] = $this->user_id = $session['fl_user_id'];
			$_SESSION['fl_language'] = $session['fl_language'];


			if(isset($session['is_admin']) && $session['is_admin'] == 1) {
				$_SESSION['is_admin'] = $this->is_admin = $session['is_admin'];
			}
			if(isset($this->user_id)) {
				$this->setUser($this->user_id);
			}
		}
	}

	public function verifySession($login_required) {
		$session_id = $this->session_id;
		if(!empty($session_id)) {
			$session = $this->db->query("SELECT * FROM sessions WHERE session_id='".$session_id."'");
			$session = $session->fetch_object();
			if($session->is_active == 0) {
				if($login_required == true) {
					header('Location: '.$this->getDomain().'/logout.php');
					exit;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			if($login_required == true) {
				header('Location: '.$this->getDomain().'/logout.php');
				exit;
			} else {
				return false;
			}
		}
	}

	public function endSession() {
		$this->db->query("UPDATE sessions SET is_active='0' WHERE session_id='".$this->session_id."'");
		session_destroy();
		header('Location: index.php');
		exit;
	}

	public function endSessions() {
		$this->db->query("UPDATE sessions SET is_active='0' WHERE user_id='".$this->user_id."'");
		session_destroy();
		header('Location: index.php');
		exit;
	}

	public function getSessions() {
		$sessions = $this->db->query("SELECT * FROM sessions WHERE user_id='".$this->user_id."'");
		return $sessions;
	}

	public function readableSessionStatus($status) {
		if($status == 1) {
			return 'Active';
		} else {
			return 'Inactive';
		}
	}

	public function setUser($user_id) {
		$user = $this->db->query("SELECT * FROM users WHERE id='".$user_id."' LIMIT 1");
		if($user->num_rows >= 1) {
			$user = $user->fetch_object();
			$this->user = $user;
		}
	}
}

class Movie extends Session {
	public $movie_id;
	public $episode_id;

	public function setMovie($movie_id){
		$this->movie_id = $movie_id;
	}

	public function setEpisode($episode_id){
		$this->episode_id = $episode_id;
	}

	public function canWatch($free_to_watch) {
		if($this->user->is_subscriber == 1 || $free_to_watch == 1 || $this->user->is_admin == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function subscriberAction() {
		if((isset($this->user->is_subscriber) && $this->user->is_subscriber) == 1 || (isset($this->user->is_admin) && $this->user->is_admin == 1)) {
			return true;
		} else {
			return false;
		}
	}

	public function getSuggestions($movie_genres) {
		$genres = explode(',',$movie_genres);
		$primary_genre = $genres[0];
		$suggestions = $this->db->query("SELECT * FROM movies WHERE (movie_genres LIKE '%".$primary_genre."%' OR movie_genres
			LIKE '%".$primary_genre."' OR movie_genres LIKE '".$primary_genre."%') AND id != ".$this->movie_id." ORDER BY RAND() LIMIT 10");
		return $suggestions;
	}

	public function isKid() {
		if(isset($_SESSION['is_kid'])) {
			return true;
		} else {
			return false;
		}
	}

	public function getFeaturedMovie() {
		if(isset($_SESSION['is_kid'])) {
			$featured_movie = $this->db->query("SELECT * FROM movies WHERE is_featured='1' AND is_kid_friendly='1' ORDER BY RAND() LIMIT 1");
		} else {
			$featured_movie = $this->db->query("SELECT * FROM movies WHERE is_featured='1' ORDER BY RAND() LIMIT 1");
		}
		return $featured_movie->fetch_object();
	}

	public function getSeasons() {
		return $this->db->query("SELECT * FROM seasons WHERE movie_id='".$this->movie_id."' ORDER BY season_number ASC");
	}

	public function getDefaultSeason() {
		$default = $this->db->query("SELECT * FROM seasons WHERE movie_id='".$this->movie_id."' ORDER BY season_number ASC LIMIT 1");
		$default = $default->fetch_object();
		return $default;
	}

	public function movieGenresToText($movie_genres) {
		$genres_id = explode(',',$movie_genres);
		$arr = $genres_id;
		foreach($genres_id as $genre_id) {
			$genre = $this->db->query("SELECT * FROM genres WHERE id='".$genre_id."' LIMIT 1");
			$genre = $genre->fetch_object();
			echo $genre->genre_name;
			if(next($arr)) {
				echo ', ';
			}
		}
	}
}

class Admin extends Movie {
	public function verifyAdmin($redirect) {
		if($redirect == true) {
			if($this->is_admin == 1) {
				if(basename($_SERVER['PHP_SELF']) == 'index.php') {
					header('Location: dashboard.php');
					exit;
				}
			} else {
				header('Location: '.$this->getDomain().'/logout.php');
				exit;
			}
		} else {
			if($this->is_admin == 1) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function getUserRank($is_admin,$is_subscriber) {
		if($is_admin == 0 && $is_subscriber == 0) {
			return 'User';
		} elseif($is_admin == 0 && $is_subscriber == 1) {
			return 'Subscriber';
		}
		elseif($is_admin == 0 && $is_subscriber == 2) {
			return 'Gold';
		}
		else {
			return 'Admin';
		}
	}

	public function getKidFriendly($is_kid_friendly) {
		if($is_kid_friendly == 1) {
			return '<i class="ti-check"></i>';
		} else {
			return '<i class="ti-close"></i>';
		}
	}

	public function getThemes($theme_dir) {
		$themes = scandir($theme_dir);
		return $themes;
	}

	public function trimNames($names) {
		$names = explode(' ',$names);
		$names[1] = substr($names[1],0,1);
		return $names[0].' '.$names[1].'.';
	}
}

class Data extends Admin {
	public $limit = 150;
	public function getStatistics() {
		$users = $this->db->query("SELECT COUNT(*) AS num FROM users");
		$users = $users->fetch_object();
		$videos = $this->db->query("SELECT COUNT(*) AS num FROM movies");
		$videos = $videos->fetch_object();
		$episodes = $this->db->query("SELECT COUNT(*) AS num FROM episodes");
		$episodes = $episodes->fetch_object();
		$result = new stdClass();
		$result->users = $users->num;
		$result->videos = $videos->num;
		$result->episodes = $episodes->num;
		return $result;
	}
	public function getUsers($page=1,$search_query='',$orderby='') {
        $start_from = ($page-1) * $this->limit;
        $WHERE = '';
        if($search_query != ''){
            $WHERE = " WHERE US.name LIKE '%$search_query%' OR US.email LIKE '%$search_query%'";
        }
        $query = "SELECT US.* FROM users AS US LEFT JOIN sessions AS SS ON US.id=SS.user_id GROUP BY SS.user_id ORDER BY SS.time $orderby LIMIT $start_from, $this->limit";
		$users = $this->db->query($query);
		return $users;
	}
	public function getUser($user_id=false,$find=false,$column=false) {
		if($find == false) {
			$user = $this->db->query("SELECT * FROM users WHERE id='".$user_id."'");
			$user = $user->fetch_object();
		} else {
			$user = $this->db->query("SELECT * FROM users WHERE ".$column."='".$find."'");
			$user = $user->fetch_object();
		}
		return $user;
	}
	public function getGenres($limit=false,$empty=true,$page=1) {
		if(is_numeric($limit)) {
			$limit = $limit;
		} else {
			$limit = $this->limit;
		}
        $start_from = ($page-1) * $limit;
        $PAGE_LIMIT = " LIMIT $start_from,$limit";
		if($empty == true) {
			$genres = $this->db->query("SELECT * FROM genres ORDER BY id DESC ".$PAGE_LIMIT."");
		} else {
			$genres = $this->db->query("SELECT * FROM genres AS genre WHERE EXISTS
				(SELECT id FROM movies WHERE movie_genres LIKE CONCAT('%', genre.id ,'%')) ORDER BY id DESC ".$PAGE_LIMIT."");
		}
		return $genres;
	}
	public function getGenre($genre_id) {
		$genre = $this->db->query("SELECT * FROM genres WHERE id='".$genre_id."'");
		$genre = $genre->fetch_object();
		return $genre;
	}
	public function getMovies() {
		$movies = $this->db->query("SELECT * FROM movies where from_type='video' ORDER BY id DESC");
		return $movies;
	}
	public function getFilms($search_query = '',$page = 1) {
		$start_from = ($page-1) * $this->limit;
		$WHERE = '';
		if($search_query != ''){
			$WHERE = " AND (movie_name LIKE '%$search_query%' OR movie_plot LIKE '%$search_query%' OR imdbid LIKE '%$search_query%' OR alternative_titles LIKE '%$search_query%')";
		}
		$movies = $this->db->query("SELECT * FROM movies WHERE from_type='film' $WHERE ORDER BY id DESC LIMIT $start_from, $this->limit");
		return $movies;
	}
	public function pagination($totalRecords,$page,$page_limit = '')
	{
		$limit = ($page_limit != '')?$page_limit:$this->limit;
		$adjacents = 3;
		if(strpos(current_page_url(), "page")){
			if(strpos(current_page_url(), "?")){
				$targetpage= substr(current_page_url(), 0, strpos(current_page_url(), "page"));
			}
			else{
				$targetpage= substr(current_page_url(), 0, strpos(current_page_url(), "page"))."?";
			}
		}
		else{
			if(strpos(current_page_url(), "?")){
				$targetpage= substr(current_page_url(), 0).'&';
			}
			else{
				$targetpage= substr(current_page_url(), 0).'?';
			}
		}
		if ($page == 0) $page = 1;
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil($totalRecords/$limit);
		$lpm1 = $lastpage - 1;
		$pagination = "";
		if($lastpage > 1)
		{
			$pagination .= "<ul class=\"pagination\">";
			//previous button
			if ($page > 1)
			  $pagination.= '<li><a href="'.$targetpage.'page='.$prev.'"><i class="fa fa-angle-left"></i></a></li>';
			//pages
			if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
			{
			  for ($counter = 1; $counter <= $lastpage; $counter++)
			  {
				if ($counter == $page)
				  $pagination.= '<li class="active"><a href="javascript:void(0)">'.$counter.'</a>';
				else
				  $pagination.= '<li><a href="'.$targetpage.'page='.$counter.'">'.$counter.'</a>';
			  }
			}
			elseif($lastpage > 5 + ($adjacents * 2))  //enough pages to hide some
			{
			  //close to beginning; only hide later pages
			  if($page < 1 + ($adjacents * 2))
			  {
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
				  if ($counter == $page)
					$pagination.= "<li class=\"active\"><a href=\"javascript:void(0)\">$counter</a>";
				  else
					$pagination.= '<li><a href="'.$targetpage.'page='.$counter.'">'.$counter.'</a>';
				}
				$pagination.= "<li><a href=\"javascript:void(0)\">...</a>";
				$pagination.= '<li><a href="'.$targetpage.'page='.$lpm1.'">'.$lpm1.'</a>';
				$pagination.= '<li><a href="'.$targetpage.'page='.$lastpage.'">'.$lastpage.'</a>';
			  }
			  //in middle; hide some front and some back
			  elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			  {
				$pagination.= '<li><a href="'.$targetpage.'page=1">1</a>';
				$pagination.= '<li><a href="'.$targetpage.'page=2">2</a>';
				$pagination.= "<li><a href=\"javascript:void(0)\">...</a>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
				  if ($counter == $page)
					$pagination.= "<li class=\"active\"><a href=\"javascript:void(0)\">$counter</a>";
				  else
					$pagination.= '<li><a href="'.$targetpage.'page='.$counter.'">'.$counter.'</a>';
				}
				$pagination.= "<li><a href=\"javascript:void(0);\">...</a>";
				$pagination.= '<li><a href="'.$targetpage.'page='.$lpm1.'">'.$lpm1.'</a>';
				$pagination.= '<li><a href="'.$targetpage.'page='.$lastpage.'">'.$lastpage.'</a>';
			  }
			  //close to end; only hide early pages
			  else
			  {
				$pagination.= '<li><a href="'.$targetpage.'page=1">1</a>';
				$pagination.= '<li><a href="'.$targetpage.'page=2">2</a>';
				$pagination.= "<li><a href=\"javascript:void(0);\">...</a>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
				  if ($counter == $page)
					$pagination.= "<li class=\"active\"><a href=\"javascript:void(0)\">$counter</a>";
				  else
					$pagination.= '<li><a href="'.$targetpage.'page='.$counter.'">'.$counter.'</a>';
				}
			  }
			}
			//next button
			if ($page < $counter - 1)
			  $pagination.= '<li><a href="'.$targetpage.'page='.$next.'"><i class="fa fa-angle-right"></i></a>';
			$pagination.= "</ul>\n";
		}
		return $pagination;
	}
	public function paginationCat($totalRecords,$page,$page_limit = ''){
		$limit = ($page_limit != '')?$page_limit:$this->limit;
		$adjacents = 3;
		$url =   $_SERVER["REQUEST_URI"];
		global $domain;
	  $Ar = explode('/',$url);
	  /*  echo '<pre>';
	  print_r($url);
	  exit;  */
	  $targetpage	= 'category.php?id='.$_GET['id'].'&';
//echo current_page_url();
		// if(strpos(current_page_url(), "page")){
		// 	if(strpos(current_page_url(), "?")){
		// 		$targetpage= substr(current_page_url(), 0, strpos(current_page_url(), "page"));
		// 	}
		// 	else{
		// 		$targetpage= substr(current_page_url(), 0, strpos(current_page_url(), "page"))."?";
		// 	}
		// }
		// else{
		// 	if(strpos(current_page_url(), "?")){
		// 		$targetpage= substr(current_page_url(), 0).'&';
		// 	}
		// 	else{
		// 		$targetpage= substr(current_page_url(), 0).'?';
		// 	}
		// }
		if ($page == 0) $page = 1;
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil($totalRecords/$limit);
		$lpm1 = $lastpage - 1;
		$pagination = "";
		if($lastpage > 1)
		{
			$pagination .= "<ul class=\"pagination\">";
			//previous button
			if ($page > 1)
				$pagination.= '<li><a href="'.$targetpage.'page='.$prev.'"><i class="fa fa-angle-left"></i></a></li>';
			//pages
			if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
			{
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
				if ($counter == $page)
					$pagination.= '<li class="active"><a href="javascript:void(0)">'.$counter.'</a>';
				else
					$pagination.= '<li><a href="'.$targetpage.'page='.$counter.'">'.$counter.'</a>';
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))  //enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2))
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"javascript:void(0)\">$counter</a>";
						else
						$pagination.= '<li><a href="'.$targetpage.'page='.$counter.'">'.$counter.'</a>';
					}
					$pagination.= "<li><a href=\"javascript:void(0)\">...</a>";
					$pagination.= '<li><a href="'.$targetpage.'page='.$lpm1.'">'.$lpm1.'</a>';
					$pagination.= '<li><a href="'.$targetpage.'page='.$lastpage.'">'.$lastpage.'</a>';
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= '<li><a href="'.$targetpage.'page=1">1</a>';
					$pagination.= '<li><a href="'.$targetpage.'page=2">2</a>';
					$pagination.= "<li><a href=\"javascript:void(0)\">...</a>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"javascript:void(0)\">$counter</a>";
						else
						$pagination.= '<li><a href="'.$targetpage.'page='.$counter.'">'.$counter.'</a>';
					}
					$pagination.= "<li><a href=\"javascript:void(0);\">...</a>";
					$pagination.= '<li><a href="'.$targetpage.'page='.$lpm1.'">'.$lpm1.'</a>';
					$pagination.= '<li><a href="'.$targetpage.'page='.$lastpage.'">'.$lastpage.'</a>';
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= '<li><a href="'.$targetpage.'page=1">1</a>';
					$pagination.= '<li><a href="'.$targetpage.'page=2">2</a>';
					$pagination.= "<li><a href=\"javascript:void(0);\">...</a>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"javascript:void(0)\">$counter</a>";
						else
						$pagination.= '<li><a href="'.$targetpage.'page='.$counter.'">'.$counter.'</a>';
					}
				}
			}
			//next button
			if ($page < $counter - 1)
				$pagination.= '<li><a href="'.$targetpage.'page='.$next.'"><i class="fa fa-angle-right"></i></a>';
			$pagination.= "</ul>\n";
		}
		return $pagination;
	}
	public function getMovie($movie_id=false,$movie_page=false) {
		if($movie_page == true) {
			$movie_id = $this->movie_id;
		}
		$movie = $this->db->query("SELECT * FROM movies WHERE id='".$movie_id."'");
		$movie = $movie->fetch_object();
		return $movie;
	}
	public function getMovie1($movie_id=false,$movie_page=false) {
		$movie_id = $this->episode_id;
		$movie = $this->db->query("SELECT * FROM episodes WHERE id='".$movie_id."'");
		$movie = $movie->fetch_object();
		return $movie;
	}
	public function getSeason($season_id) {
		$season = $this->db->query("SELECT * FROM seasons WHERE id='".$season_id."'");
		$season = $season->fetch_object();
		return $season;
	}

	public function getEpisodes($search_query = '',$page = 1) {
		$start_from = ($page-1) * 10;
        $WHERE = '';
		if($search_query != ''){
			$WHERE = "AND (episode_name LIKE '%$search_query%' OR episode_description LIKE '%$search_query%'  OR episode_number LIKE '%$search_query%')";
		}
		$episodes = $this->db->query("SELECT * FROM episodes WHERE episode_name<>'' $WHERE ORDER BY id DESC LIMIT $start_from, 10");
		return $episodes;
	}
	public function getEpisode($episode_id) {
		$episode = $this->db->query("SELECT * FROM episodes WHERE id='".$episode_id."'");
		$episode = $episode->fetch_object();
		return $episode;
	}
	public function getActors($movie=false,$movie_id=false,$search_query = '',$page = 1) {
		if($movie == false) 
		{
			$start_from = ($page-1) * $this->limit;
			$WHERE = '';
			if($search_query != ''){
				$WHERE = " WHERE actor_name LIKE '%$search_query%'";
			}
	 	} 
	  	else 
		{
	    $relations = $this->db->query("SELECT DISTINCT  actor_id FROM episodes WHERE movie_id='".$this->movie_id."' AND actor_id!='' group by movie_id,season_id ORDER BY id ASC");
		
			  if($relations->num_rows===0)
			  {
		
				$relations = $this->db->query("SELECT * FROM actor_relations WHERE movie_id='".$this->movie_id."' ORDER BY id ASC");
				
				while($relation = $relations->fetch_object()) {
					$actor = $this->db->query("SELECT * FROM actors WHERE id in ('".$relation->actor_id."')");
					$actors[] = $actor->fetch_object();
        		}
			}
			else
			{
				while($relation = $relations->fetch_object()) {
					$actorsData = explode(',',$relation->actor_id);
					foreach ($actorsData as $acData) {
						$actor = $this->db->query("SELECT * FROM actors WHERE id='".$acData."'");
						$actor = $actor->fetch_object();
						$actors[] = $actor;
					}
				}

			}
			return $actors;
		}
  	}
	public function getActors1($actorsid) {
		$actors = array();

		$actor = $this->db->query("SELECT * FROM actors WHERE id='".$actorsid."'");
		if(!empty($actor)){
		while($actor1 = $actor->fetch_object()) {
			$actors[] = $actor1;
		}
	}
	return $actors;

}


	public function getActor($actor_id) {
		$actor = $this->db->query("SELECT * FROM actors WHERE id='".$actor_id."'");
		$actor = $actor->fetch_object();
		return $actor;
	}
	public function getmActor($movie_id) {
		$mactors = array();
		$mactor = $this->db->query("SELECT * FROM actor_relations WHERE movie_id='".$movie_id."'");
		$mactor = $mactor->fetch_object();
		return $mactor;
	}
	public function getPages() {
		$pages = $this->db->query("SELECT * FROM pages ORDER BY id ASC");
		return $pages;
	}
	public function getPage($page_id) {
		$page = $this->db->query("SELECT * FROM pages WHERE id='".$page_id."'");
		$page = $page->fetch_object();
		return $page;
	}
}
if(!function_exists('debug')){
	function debug($value){
		switch (gettype($value)) {
			case 'array':
					echo '<pre>';
					print_r($value);
					echo '</pre>';
				break;
			default:
				echo '<pre>';
				print_r($value);
				echo '</pre>';
			break;
		}
	}
}
function getposterImg($movie_id){
	return $domain.'/images/default-image.png';
	global $db;
	$getData = $db->query("SELECT movie_poster_image FROM movies WHERE id=$movie_id");
	$picture = $getData->fetch_assoc()['movie_poster_image'];

	$picture = $domain.'/uploads/poster_images/'.$picture;
	if($picture ==''){
		$picture = $domain.'/images/default-image.png';
	}
	return $picture;
}
function current_page_url(){
	return "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
}
function base_url(){
	return "http://".$_SERVER["HTTP_HOST"]."/film";
}
if(!function_exists("client_ip")){
    function client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}
function getTimeLeft($fromTime,$toTime){
	$start  = date_create($fromTime);
	$end 	= date_create($toTime); // Current time and date
	$diff  	= date_diff($start,$end);
	// Output: The difference is 28 years, 5 months, 19 days, 20 hours, 34 minutes, 36 seconds
	if($diff->d > 0){
		$string = $diff->d." days";
	}
	if($diff->m > 0){
		$string = $diff->m." motnhs ".$string;
	}
	if($diff->y > 0){
		$string = $diff->y." years ".$string;
	}
	return $string;
}

if(!function_exists('_gRS')){
    function _gRS($length = 10,$int = false) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($int){$characters = '0123456789';}
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
/*function send_email($to,$subject,$message){
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require('core/php-mailer/Exception.php');
    require('core/php-mailer/PHPMailer.php');
    require('core/php-mailer/SMTP.php');
    require('core/php-mailer/POP3.php');
    return $response;
}*/
function resize($fileName,$fileRealName,$uploadPath='actors/',$quality = 20){
  $image = imagecreatefromstring($fileName);
  $path = $uploadPath.$fileRealName;
  imagejpeg($image, $path, $quality);
  return $path;
  imagedestroy($image);
  imagedestroy($tmp);
}
function generate_postname($input, $replace = '-', $remove_words = true, $words_array = array()) {
	$return = trim(preg_replace('/ \+/', ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input))));
	if($remove_words) { $return = remove_words($return, $replace, $words_array); }
	return str_replace(' ', $replace, $return);
}
function remove_words($input,$replace,$words_array = array(),$unique_words = true){
	$input_array = explode(' ',$input);
	$return = array();
	foreach($input_array as $word)
	{
		if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true))
		{
			$return[] = $word;
		}
	}
	return implode($replace,$return);
}
function run_tmdb_curl($imdbId){
    // $url = "https://api.themoviedb.org/3/find/$imdbId?api_key=bc457c6e89c45bbb2f34a7bdd23688cf&external_source=imdb_id";
    // $output = @file_get_contents($url);
		$url = "https://api.themoviedb.org/3/find/".$imdbId."?api_key=".TMDB_KEY."&external_source=imdb_id";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt_array($curl, array(
		 CURLOPT_URL => $url,
		 CURLOPT_RETURNTRANSFER => true,
		 CURLOPT_ENCODING => "",
		 CURLOPT_MAXREDIRS => 10,
		 CURLOPT_TIMEOUT => 30,
		 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		 ));
		 $response = curl_exec($curl);
		 $json = json_decode($response);
    return $json;
}
function runCurl($url){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt_array($curl, array(
	 CURLOPT_URL => $url,
	 CURLOPT_RETURNTRANSFER => true,
	 CURLOPT_ENCODING => "",
	 CURLOPT_MAXREDIRS => 10,
	 CURLOPT_TIMEOUT => 30,
	 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	 ));
	 $response = curl_exec($curl);
	 $json = json_decode($response);
	return $json;//[''][0]
}

require('dboperation.php');



if (!function_exists('_db')) {
	function _db()
	{
		return new BasicDb;
	}
}


if (!function_exists('_storeFilm')) {
	function _storeFilm($imdbid, $ext=null)
	{
		$err_arr = [];
		$IMDB_result = run_tmdb_curl($imdbid);
		$data = get_object_vars($IMDB_result);
		$imdb_type = '';
		$imdb_data = null;
		$video_source = '';
		if ($ext!=null) {
			if (isset($ext->video_url) && !empty($ext->video_url)) {
				$video_source = $ext->video_url;
			}
		} 

		if (isset($data['movie_results']) && !empty($data['movie_results'])) 
		{
			$imdb_type = 'movie';
			$imdb_data = isset($data['movie_results'][0])?$data['movie_results'][0]:null;
		}
		elseif(isset($data['tv_results']) && !empty($data['tv_results']))
		{
			$imdb_type = 'tv';
			$imdb_data = isset($data['tv_results'][0])?$data['tv_results'][0]:null;
		}
		else
		{
			$err_arr [] = $imdbid.' Item not supported currently';
		}

		if ($imdb_data!=null && $imdb_type!='') 
		{
			/*Geners Start*/
			$genres = $imdb_data->genre_ids;
			$make_geners = [];
			$make_geners = _makeMovieGenres($genres);
			$genres_video = implode(",",$make_geners);
			/*Geners Ends*/

			/*Actors Starts*/
			$imdb_type_id =  $imdb_type=='tv'?$imdb_data->id:$imdbid;
			$make_actors = [];
			$make_actors = _makeActorsLists($imdb_type_id, $imdb_type);
			if (isset($make_actors['status_code']) && $make_actors['status_code'] == 34) {
				$err_arr[] = 'Actors Lists not found for the given ID '.$imdb_type_id.' type='.$imdb_type;
			}
			$makeactors = implode(",", $make_actors);
			$actors = explode(",", $makeactors);
			/*Actors Ends*/


			/* store  video to server */
			if ($video_source != "") 
			{
				$new_file_name = $imdbid . '_video.mp4';
				$the_file = '../uploads/videos/' . $new_file_name;
				if (file_exists($the_file)) {
					unlink($the_file);
					file_put_contents($the_file, file_get_contents($video_source));
				}
				else 
				{
					file_put_contents($the_file, file_get_contents($video_source));
				}
				
			}
			/* end store video to server */

			$description = $imdb_data->overview;
			$video_name = $imdb_type=='tv'?addslashes($imdb_data->name):addslashes($imdb_data->title);
			$video_description = addslashes($description);
			$video_categories = $genres_video ;
			$is_kid_friendly = 0;
			$im_url = "https://image.tmdb.org/t/p/original" . $imdb_data->poster_path;
			if ($imdb_type == 'tv') 
			{
				if ($im_url != "") 
				{
					$extension = 
					isset(explode('.', $imdb_data->poster_path)[1])?
					explode('.', $imdb_data->poster_path)[1]:'jpg';
					$new_file_name_2 = generate_postname($imdb_data->name) . '_poster' . $imdbid . '.' . $extension;
					$url = $im_url;
					$poster_path =  '../uploads/poster_images/';
					$masonry_path = '../uploads/masonry_images/';
					$poster_image = $poster_path. $new_file_name_2;
					$masnory_image = $masonry_path. $new_file_name_2;
					if (file_exists($poster_image)) {
						unlink($poster_image);
						resize(file_get_contents($im_url), $new_file_name_2, $poster_path, 50);
					}
					else 
					{
						resize(file_get_contents($im_url), $new_file_name_2, $poster_path, 50);
					}


					if (file_exists($masnory_image)) {
						unlink($masnory_image);
						resize(file_get_contents($im_url), $new_file_name_2, $masonry_path, 50);
					}
					else 
					{
						resize(file_get_contents($im_url), $new_file_name_2, $masonry_path, 50);
					}
					/* file_put_contents($img2, file_get_contents($im_url));
					file_put_contents($poster, file_get_contents($im_url)); */
				}
			}

			if ($imdb_type == 'movie') 
			{

				if ($im_url != "") {
					$extension = isset(explode('.', $imdb_data->poster_path)[1])
					?
					explode('.', $imdb_data->poster_path)[1]:'jpg';
					$new_file_name_2 = generate_postname($imdb_data->title) . '_poster' . $imdbid. '.' . $extension;
					$url = $im_url;
					$poster_path = '../uploads/poster_images/';
					$poster_image = $poster_path.$new_file_name_2;
					if (file_exists($poster_image)) {
						unlink($poster_image);
						resize(file_get_contents($im_url), $new_file_name_2, $poster_path, 50);
					}
					else 
					{
						resize(file_get_contents($im_url), $new_file_name_2, $poster_path, 50);
					}
				}
			}
			
			$json = runCurl("https://api.themoviedb.org/3/movie/{$imdbid}/alternative_titles?api_key=".TMDB_KEY);
			$ary = get_object_vars($json);
			if (isset($ary['status_code']) && $ary['status_code'] == 34) {
				$err_arr[] = 'Alernative titles are not found';
			}
			else
			{

				$s = $ary['titles'];
				$key = array();
				$features = '';
				foreach ($s as $value) 
				{
					if ($value->iso_3166_1 == 'US' || $value->iso_3166_1 == 'USA') {
						$key[] = $value->iso_3166_1;
						$features = $value->title;
						$features = str_replace("'", '-' ,$features);
					}
				}
			}
		
		
			
			$rating = $imdb_data->vote_average;
			if (isset($imdb_data->rating)) 
			{
				$rating = round((($imdb_data->rating) / 2), 1);
			}
			
			$year = $imdb_type == 'tv'? 
			explode('-',$imdb_data->first_air_date)[0]:
			explode('-',$imdb_data->release_date)[0];

			
			/* check if movie exits */
			$movieRow = _db()->joinQuery('SELECT * FROM movies where imdbid = "'.$imdbid.'"');
			/* end of checking */
			$moveArr = [
				'movie_name' => $video_name,
				'movie_plot'  =>$video_description,
				'movie_genres'  => $video_categories,
				'movie_poster_image'  => $new_file_name_2,
				'movie_thumb_image'  => $new_file_name_2,
				'movie_rating'  =>$rating,
				'movie_year'  => $year,
			];

			if ($imdb_type == 'tv') {
				$moveArr['is_series'] = 1;
			}
			else
			{
				$moveArr['is_series'] = 0;
				$moveArr['from_type'] = 'film';
			}

			$movie_id = '';
			if ($movieRow->rowCount()>0) {
				$moveRow = $movieRow->fetch(PDO::FETCH_ASSOC);
				$movie_id = $moveRow['id'];
				if ($video_source!='') {
					$moveArr['movie_source'] = $video_source;
				}
				_db()->update('movies', $moveArr, 'imdbid = "'.$imdbid.'"');
			}
			else
			{
				$moveArr['imdbid'] = $imdbid;
				$moveArr['movie_source'] = $video_source;
				_db()->insert('movies', $moveArr);
				$movie_id = _db()->getInsertId();
			}

			if ($movie_id!='') 
			{
				_movieRatingUpdate($movie_id, $rating);
				if(!empty($actors))
				{
					/* run a delet query before making new relations */
					_db()->joinQuery('DELETE FROM `actor_relations` WHERE `movie_id`="'.$movie_id.'"');
					foreach ($actors as $actor => $actor_id) 
					{
						_db()->insert('actor_relations', [
							'movie_id' => $movie_id, 
							'actor_id' => $actor_id,
						]);
					}
				}

				if(!empty($make_geners))
				{
					_db()->joinQuery('DELETE FROM `genres_relations` WHERE `movie_id`="'.$movie_id.'"');
					foreach ($make_geners as $Mg => $Gi) {
						_db()->insert('genres_relations', [
							'movie_id' => $movie_id, 
							'genres_id' => $Gi,
						]);
					}
				}
				_db()->update('movies', [
					'all_starcast' => 'yes'
				], 'id = "'.$movie_id.'"');
			}
			else 
			{
				$err_arr[] = 'Movie Id is not found , Database error, IMDB ID '.$imdbid;
			}

		}
		return [
			'error' => $err_arr,
			'success' => [],
		];
	}
}

if (!function_exists('_makeMovieGenres')) {
	function _makeMovieGenres($genres)
	{
		$make_geners = [];
		$is_kid_friendly = 0;
		if (count($genres)>0) {
			foreach ($genres as $category) {
				$st = runCurl("https://api.themoviedb.org/3/genre/{$category}?api_key=".TMDB_KEY);
				$array1 = get_object_vars($st);
				if (isset($array1['status_code']) && $array1['status_code'] == 34) {
					continue;
				}
				$catName = $array1['name'];
				$catName = str_replace(' ', '-', $catName);
				$sql = "SELECT * FROM genres WHERE genre_name LIKE '%" . $catName . "%'";
				$result = _db()->joinQuery($sql);
				if ($result->rowCount() <= 0) 
				{
					$catName = str_replace(' ', '-', $catName);
					$newinsert = _db()->insert('genres', [
						'genre_name' => $catName, 
						'is_kid_friendly' =>  $is_kid_friendly
					]);
					$gener_id = _db()->getInsertId();
				} 
				else 
				{
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$gener_id = $row['id'];
					}
				}
				$make_geners[] = $gener_id;
			}
		}
		return $make_geners;
	}
}

if (!function_exists('_movieRatingUpdate')) {
	function _movieRatingUpdate($movie_id, $rating)
	{
		/* check if movie rating exist */
		$this_movie_rating =  _db()
		->joinQuery('SELECT * FROM ratings where movie_id = "'.$movie_id.'"');
		if ($this_movie_rating->rowCount() > 0) {

			_db()->update('ratings', [
				'user_id' => 22, 
				'rating' => $rating
			], 'movie_id = "'.$movie_id.'"');
		}
		else
		{
			_db()->insert('ratings', [
				'movie_id' => $movie_id, 
				'user_id' => 22, 
				'rating' => $rating
			]);
		}
	}
}

if (!function_exists('_makeActorsLists')) {
	 function _makeActorsLists($imdbid, $type = 'movie')
	 {
		$make_actors = [];
		$url = "https://api.themoviedb.org/3/{$type}/{$imdbid}/credits?api_key=".TMDB_KEY;
		if ( $type ==  'movie') {
			$url .= "&external_source=imdb_id";
		}
		else
		{
			$url .= "&language=en-US";
		}
		$json = runCurl($url);
		$array1 = get_object_vars($json);
	
		if (isset($array1['status_code']) && $array1['status_code'] == 34) {
			return $array1;
		}
		if (isset($array1['cast'])) {
			foreach ($array1['cast'] as $cast) {
				$name_c = $cast->name;
				$nconst = $cast->id;
				$name_c = $cast->name;
				$nconst = $cast->id;
				$json = runCurl("https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US");
				$array = get_object_vars($json);
				$aname = addslashes($array['name']);
				$birthday = $array['birthday'];
				$place_of_birth = addslashes($array['place_of_birth']);
				$biography = addslashes($array['biography']);
				$imdb_id = $array['imdb_id'];
				$actor_img = $array['profile_path'];
				$im_url_c = "https://image.tmdb.org/t/p/original" . $actor_img;
				if ($actor_img != '') 
				{
					$get_extension = isset(explode('.', $actor_img)[1])?explode('.', $actor_img)[1]:'jpg';
					$extension = strtolower($get_extension);
					$c_actor = generate_postname($aname) . '_actor_' . $imdb_id . '.' . $extension;
					$ur11 = $im_url_c;
					$file_path = UPLOAD_PATH . 'actors/';
					$the_file = $file_path.$c_actor;
					if (file_exists($the_file)) {
						unlink($the_file);
						resize(file_get_contents($im_url_c), $c_actor, $file_path, 50);
					}
					else 
					{
						resize(file_get_contents($im_url_c), $c_actor, $file_path, 50);
					}
					
					$img = UPLOAD_PATH . 'actors/' . $c_actor;
				} 
				else 
				{
					$c_actor = "";
				}
	
				$sql1 = "SELECT * FROM actors WHERE imdbid ='".$imdb_id."'";
				$result1 = _db()->joinQuery($sql1);
				if ($result1->rowCount() <= 0) 
				{
	
					_db()->insert('actors', [
						'actor_name' => $aname, 
						'actor_picture' =>  $c_actor, 
						'actor_nconst' => $nconst, 
						'birthday' => $birthday, 
						'place_of_birth' =>  $place_of_birth, 
						'biography' => $biography, 
						'actor_img_url' => $im_url_c, 
						'imdbid' => $imdb_id, 
					]);
					$actor_id = _db()->getInsertId();
				} 
				else 
				{
					if ($result1) {
						while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
						{
							$actor_id = $row1['id'];
							_db()->update('actors', [
								'actor_name' => $aname, 
								'actor_picture' => $c_actor, 
								'birthday' => $birthday, 
								'place_of_birth' => $place_of_birth, 
								'biography' => $biography, 
								'actor_img_url' => $im_url_c, 
								'imdbid' => $imdb_id 
							], 
							"id='".$actor_id."'");
						}
					}
				}
				$make_actors[] = $actor_id;
			}
		}
		return $make_actors;
	 }
}


