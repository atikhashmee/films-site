<?php

class User extends System {

	public $session_id;
	public $user_id;
	public $email;
	public $name;
	public $profile_id; 

	function __construct($db,$domain,$session_id,$user_id,$email,$name,$profile_id) {
		$this->db = $db;
		$this->domain = $domain;
		$this->session_id = $session_id;
		$this->user_id = $user_id;
		$this->email = $email;
		$this->name = $name;
		$this->profile_id = $profile_id;
	}
	
	public function loginSession() {
		$_SESSION['fl_auth'] = true;
		$_SESSION['fl_session_id'] = $this->session_id;
		$_SESSION['fl_user_id'] = $this->user_id;
		$_SESSION['fl_email'] = $this->email;
		$_SESSION['fl_name'] = $this->name;
		$_SESSION['fl_profile'] = $this->profile_id;
		$_SESSION['fl_login_time'] = time();
	}

	public function getProfilePicture() {
		$profile = $this->db->query("SELECT profile_avatar FROM profiles WHERE id='".$_SESSION['fl_profile']."'");
		$profile = $profile->fetch_object();
		return $this->getDomain().'/assets/images/avatars/'.$profile->profile_avatar.'.png';
	}

	public function getProfiles() {
		return $this->db->query("SELECT * FROM profiles WHERE user_id='".$_SESSION['fl_user_id']."'");
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

	public function sex() {
		echo $this->domain;
	}
}