<?php
include("imdbapi.class.php");
require('../core/config.php');
require('../core/system.php');
$position = strpos($_REQUEST['id'], ',');
$movieName = ($position === false)?$_REQUEST['id']:explode(',',$_REQUEST['id']);

$make_actors = array();
if(is_array($movieName)){
    include("class_IMDbnew.php");
	foreach($movieName as $IMDBID){
        $make_actors = array();
		//$mArr = array_change_key_case($i->getMovieInfoById($IMDBID,true), CASE_UPPER);
        $imdb = new Imdb();
        $castArray = $imdb->showCast(true)->get(trim($IMDBID))['Cast:'];
        foreach($castArray as $cast){
            $imdb = new IMDbNEW(true);
            $imdb->summary=false;
            $persons = $imdb->person_by_id($cast['cast_id']);
            $person_image = $persons->image->url;
            $name_c = $cast['name'];
            $nconst = $cast['cast_id'];
            $sql1 = "select * from actors where actor_name like '%".$name_c."%'";
            $result1 = $db->query($sql1);
            if ($result1->num_rows <= 0) {
                $im_url_c = $person_image;
                if(isset($im_url_c) && $im_url_c != "") {
                    $extension = strtolower(end(explode('.',$im_url_c)));
                    $c_actor = md5(mt_rand()).'_actor.'.$extension;
                    $ur11 = $im_url_c;
                    $img11 = '../uploads/actors/'.$c_actor;
                    file_put_contents($img11, file_get_contents($ur11));
                }
                else {
                    $c_actor = "";
                }
                $db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst) VALUES ('".$name_c."','".$c_actor."','".$nconst."')");
                $actor_id = $db->insert_id;
            }
            else {
                while($row1 = $result1->fetch_assoc()) {
                    $actor_id = $row1['id'];
                    if($row1['actor_nconst'] == "") {
                        $db->query("update actors set actor_nconst = '".$nconst."' where id = '".$actor_id."'");
                    }
                }
            }
            $make_actors[] = $actor_id;
        }
        $movis = "select * from movies where imdbid = '".$IMDBID."'";
		$movis_result1 = $db->query($movis);
		while($new_r = $movis_result1->fetch_assoc()) {
			$movie_id = $new_r['id'];
		}
		$actors = $make_actors;
		$db->query("delete from actor_relations where movie_id = '".$movie_id."'");
		foreach($actors as $actor => $actor_id) {
			$db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('".$movie_id."','".$actor_id."')");
		}
		$db->query("update movies set all_starcast = 'yes' where id = '".$movie_id."'");
		/*$cast_summery = $mArr['CAST'];
		if(!empty($cast_summery)) {
			
			foreach($cast_summery as $cast_id=>$casts) {
				
				$person_image = $persons->image->url;
				$name_c = $casts;
				$nconst = $cast_id;
				$sql1 = "select * from actors where actor_name like '%".$name_c."%'";
				$result1 = $db->query($sql1);
				if ($result1->num_rows <= 0) {
					$im_url_c = $person_image;
					if(isset($im_url_c) && $im_url_c != "") {
						$extension = strtolower(end(explode('.',$im_url_c)));
						$c_actor = md5(mt_rand()).'_actor.'.$extension;
						$ur11 = $im_url_c;
						$img11 = '../uploads/actors/'.$c_actor;
						file_put_contents($img11, file_get_contents($ur11));
					}
					else {
						$c_actor = "";
					}
					$db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst) VALUES ('".$name_c."','".$c_actor."','".$nconst."')");
					$actor_id = $db->insert_id;
				}
				else {
					while($row1 = $result1->fetch_assoc()) {
						$actor_id = $row1['id'];
						if($row1['actor_nconst'] == "") {
							$db->query("update actors set actor_nconst = '".$nconst."' where id = '".$actor_id."'");
						}
					}
				}
				$make_actors[] = $actor_id;
			}
		}
		$movis = "select * from movies where imdbid = '".$IMDBID."'";
		$movis_result1 = $db->query($movis);
		while($new_r = $movis_result1->fetch_assoc()) {
			$movie_id = $new_r['id'];
		}
		$actors = $make_actors;
		$db->query("delete from actor_relations where movie_id = '".$movie_id."'");
		foreach($actors as $actor => $actor_id) {
			$db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('".$movie_id."','".$actor_id."')");
		}
		$db->query("update movies set all_starcast = 'yes' where id = '".$movie_id."'");*/	
	}
}
else{
    $make_actors = array();
    $imdb = new Imdb();
    $castArray = $imdb->showCast(true)->get(trim($movieName))['Cast:'];
    foreach($castArray as $cast){
        $imdb = new IMDbNEW(true);
        $imdb->summary=false;
        $persons = $imdb->person_by_id($cast['cast_id']);
        $person_image = $persons->image->url;
        $name_c = $cast['name'];
        $nconst = $cast['cast_id'];
        $sql1 = "select * from actors where actor_name like '%".$name_c."%'";
        $result1 = $db->query($sql1);
        if ($result1->num_rows <= 0) {
            $im_url_c = $person_image;
            if(isset($im_url_c) && $im_url_c != "") {
                $extension = strtolower(end(explode('.',$im_url_c)));
                $c_actor = md5(mt_rand()).'_actor.'.$extension;
                $ur11 = $im_url_c;
                $img11 = '../uploads/actors/'.$c_actor;
                file_put_contents($img11, file_get_contents($ur11));
            }
            else {
                $c_actor = "";
            }
            $db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst) VALUES ('".$name_c."','".$c_actor."','".$nconst."')");
            $actor_id = $db->insert_id;
        }
        else {
            while($row1 = $result1->fetch_assoc()) {
                $actor_id = $row1['id'];
                if($row1['actor_nconst'] == "") {
                    $db->query("update actors set actor_nconst = '".$nconst."' where id = '".$actor_id."'");
                }
            }
        }
        $make_actors[] = $actor_id;
    }
    $movis = "select * from movies where imdbid = '".$movieName."'";
    $movis_result1 = $db->query($movis);
    while($new_r = $movis_result1->fetch_assoc()) {
        $movie_id = $new_r['id'];
    }
    $actors = $make_actors;
    $db->query("delete from actor_relations where movie_id = '".$movie_id."'");
    foreach($actors as $actor => $actor_id) {
        $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('".$movie_id."','".$actor_id."')");
    }
    $db->query("update movies set all_starcast = 'yes' where id = '".$movie_id."'");
    /*$make_actors = array();
	$mArr = array_change_key_case($i->getMovieInfoById($movieName), CASE_UPPER);
	$cast_summery = $mArr['CAST'];
	if(!empty($cast_summery)) {
		include("class_IMDbnew.php");
		foreach($cast_summery as $cast_id=>$casts) {
			$imdb = new IMDbNEW(true);
			$imdb->summary=false;
			$persons = $imdb->person_by_id($cast_id);
			$person_image = $persons->image->url;
			$name_c = $casts;
			$nconst = $cast_id;
			$sql1 = "select * from actors where actor_name like '%".$name_c."%'";
			$result1 = $db->query($sql1);
			if ($result1->num_rows <= 0) {
				$im_url_c = $person_image;
				if(isset($im_url_c) && $im_url_c != "") {
					$extension = strtolower(end(explode('.',$im_url_c)));
					$c_actor = md5(mt_rand()).'_actor.'.$extension;
					$ur11 = $im_url_c;
					$img11 = '../uploads/actors/'.$c_actor;
					file_put_contents($img11, file_get_contents($ur11));
				}
				else {
					$c_actor = "";
				}
				$db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst) VALUES ('".$name_c."','".$c_actor."','".$nconst."')");
				$actor_id = $db->insert_id;
			}
			else {
				while($row1 = $result1->fetch_assoc()) {
					$actor_id = $row1['id'];
					if($row1['actor_nconst'] == "") {
						$db->query("update actors set actor_nconst = '".$nconst."' where id = '".$actor_id."'");
					}
				}
			}
			$make_actors[] = $actor_id;
		}
	}
	$movis = "select * from movies where imdbid = '".$movieName."'";
	$movis_result1 = $db->query($movis);
	while($new_r = $movis_result1->fetch_assoc()) {
		$movie_id = $new_r['id'];
	}
	$actors = $make_actors;
	$db->query("delete from actor_relations where movie_id = '".$movie_id."'");
	foreach($actors as $actor => $actor_id) {
		$db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('".$movie_id."','".$actor_id."')");
	}
	$db->query("update movies set all_starcast = 'yes' where id = '".$movie_id."'");*/
}

header('Location: films.php?success=1');
		exit;
		
?>