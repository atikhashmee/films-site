<?php

session_set_cookie_params(172800);

session_start();

require('../core/config.php');

require('../core/system.php');

$admin = new Data($db,$domain);

$admin->startUserSession($_SESSION);

$admin->verifySession(true);

$admin->verifyAdmin(true);

$errors = '';

$movies = $admin->getFilms();

if(isset($_POST['upload'])) {

  if($_FILES['excel-upload']['name'] != ''){

    $alredyExists = array();

    $video_id = $_POST['video_id'];

     $date = date('Y-m-d H:i:s');

    $db->query("UPDATE movies SET created_date = '".$date."' WHERE id = '".$video_id."'");

    $handle = fopen($_FILES['excel-upload']['tmp_name'],'r');

    while($data = fgetcsv($handle, 1000, ","))
    {

      $episode_number = $data[0];
      $season_number = $data[1];
      $source = $data[2];
      $epName = $data[3];
      $season_subnumber = $data[3];
      $echeck = "SELECT * FROM episodes WHERE episode_number='".$episode_number."'";
      $echeck = $db->query($echeck);
      $echeck->num_rows;
        if ($echeck->num_rows > 0) {
            $alredyExists[] = $episode_number;
        }
        else 
        {
            $checkseason = "SELECT * FROM seasons WHERE season_number='".$season_number."' AND movie_id='".$video_id."'";
            $checkseason = $db->query($checkseason);
            if($checkseason->num_rows<=0)
            {
                $db->query("INSERT INTO seasons (movie_id,season_number) VALUES ('".$video_id."','".$season_number."')");
                $season_id = $db->insert_id;
            }
            else
            {
                $season = $checkseason->fetch_assoc();
                $season_id = $season['id'];
            }
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.themoviedb.org/3/find/{$episode_number}?api_key=".TMDB_KEY."&language=en-US&external_source=imdb_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

if ($err) {
echo "cURL Error #:" . $err;
} else {

$json = json_decode($response);

$array = get_object_vars($json);


if(empty($array['tv_episode_results'])){
    
  $db->query("INSERT INTO episodes (season_id,movie_id,episode_number,episode_name,episode_description,episode_thumbnail,episode_source,is_embed,actor_id,ratings,season_sub_id)

VALUES ('".$season_id."','".$video_id."','".$episode_number."','".$epName."','Discription Coming Soon.','814512c0eb40e98a0f15cf493f8c6096maxresdefault-1.jpg','".$source."','0','','','".$season_subnumber."')");

}

foreach($array['tv_episode_results'] as $episode) {
  $name = addslashes($episode->name);
  $id = $episode->id;
  $vote_count = $episode->vote_count;
  $vote_average = $episode->vote_average;
  $first_air_date = $episode->first_air_date;
  $poster_path = $episode->still_path;
  $overview = addslashes($episode->overview);
  $genre_ids = $episode->genre_ids;
  $origin_country = $episode->origin_country;
  $episode_num_cast = $episode->episode_number;
  $episode_num_cast = $episode->episode_number;
  $season_number_cast = $episode->season_number;
  $show_id = $episode->show_id;
  $make_actors = array();
  $actors = json_decode(@file_get_contents("https://api.themoviedb.org/3/tv/{$show_id}/season/{$season_number_cast}/episode/{$episode_num_cast}/credits?api_key=".TMDB_KEY))->cast;
    foreach ($actors as $actor) 
    {
        $nconst = $actor->id;
        $actor_info = json_decode(@file_get_contents("https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US"));
        $aname = $actor_info->name;
        $birthday = $actor_info->birthday;
        $place_of_birth = $actor_info->place_of_birth;
        $biography = addslashes($actor_info->biography);
        $imdb_id = $actor_info->imdb_id;
        $actor_img = $actor_info->profile_path;
        $im_url_c = "https://image.tmdb.org/t/p/original".$actor_img;
            if($actor_img != '')
            {
                $extension = strtolower(end(explode('.',$actor_img)));
                $c_actor = generate_postname($aname).'_actor'.time().'.'.$extension;
                $ur11 = $im_url_c;
                resize(file_get_contents($im_url_c),$c_actor,UPLOAD_PATH.'actors/',50);
                $img = UPLOAD_PATH.'actors/'.$c_actor;
            }
            else
            {
                $c_actor = "";
            }
            $sql1 = "SELECT * FROM actors WHERE actor_name LIKE '%".$aname."%'";
            $result1 = $db->query($sql1);
            if ($result1->num_rows <= 0) {
                $db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst,birthday,place_of_birth,biography,actor_img_url,imdbid) VALUES ('".$aname."','".$c_actor."','".$nconst."','".$birthday."','".$place_of_birth."','".$biography."','".$im_url_c."','".$imdb_id."')");
                $actor_id = $db->insert_id;
            }
            else 
            {
                while($row1 = $result1->fetch_assoc()) 
                {
                    $actor_id = $row1['id'];
                    $a = "UPDATE actors SET actor_name = '$aname',actor_picture ='$c_actor',actor_nconst = '$nconst',birthday='$birthday', place_of_birth='$place_of_birth',biography='$biography',actor_img_url='$im_url_c',imdbid='$imdb_id' WHERE id = '$actor_id'";
                    $db->query($a);
                }
            }
            $make_actors[] = $actor_id;
    }
    $makeactors = implode(",",$make_actors);
    $im_url = "https://image.tmdb.org/t/p/original".$poster_path;
        if($im_url != "") 
        {
            $extension = strtolower(end(explode('.',$movieOutput->poster_path)));
            $new_file_name_2 = generate_postname($movieOutput->title).'_poster'.time().'.'.$extension;
            $url = $im_url;
            $img2 = '../uploads/episodes/'.$new_file_name_2;
            file_put_contents($img2, file_get_contents($im_url));
            resize($im_url,$new_file_name_2,'../uploads/episodes/');
        }
        if(!empty($name))
        {
            $eName = $name;
        }
        else
        {
            $eName = $epName;
        }
    $result=$db->query("INSERT INTO episodes (season_id,movie_id,episode_number,episode_name,episode_description,episode_thumbnail,episode_source,is_embed,actor_id,ratings,season_sub_id)

VALUES ('".$season_id."','".$video_id."','".$episode_number."','".$eName."','".$overview."','".$new_file_name_2."','".$source."','0','".$makeactors."','".$vote_average."','".$season_subnumber."')");

        $actors = $make_actors;
        $movie_id = $db->insert_id;
        $db->query("INSERT INTO ratings(movie_id,user_id,rating) VALUES ('{$movie_id}','22','{$vote_average}')");
        $sql = "SELECT * FROM actor_relations WHERE actor_id='".$actor_id."' AND movie_id='".$movie_id."'";
        $result = $db->query($sql);
        if ($result->num_rows <= 0) 
        {
            foreach($actors as $actor => $actor_id) 
            {
                $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('{$movie_id}','{$actor_id}')");
            }
        }
        $db->query("UPDATE movies SET all_starcast = 'yes' WHERE id = '{$movie_id}'");

}

}

}

    }

     fclose($handle);
    $db->query("delete from my_watched where movie_id='{$video_id}'");
    header('Location: iepisodes.php?success=1&ae='.implode(',', $alredyExists));

  

exit;

  }

}

$getSeriesId = $db->query("SELECT id FROM genres WHERE genre_name LIKE '%Series%'")->fetch_assoc()['id'];

//debug($getSeriesId);

$movies = $db->query("SELECT * FROM movies WHERE movie_genres LIKE '%$getSeriesId%'");

?>

<!DOCTYPE HTML>

<html>

<head>

    <meta charset="utf-8" />

    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">

    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Admin Panel</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <meta name="viewport" content="width=device-width" />

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <link href="assets/css/animate.min.css" rel="stylesheet" />
    
    <link href="assets/css//bootstrap-select.min.css" rel="stylesheet" />

    <link href="assets/css/theme.css" rel="stylesheet" />

    <link href="assets/css/style.css" rel="stylesheet" />

    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">

    <link href="assets/css/themify-icons.css" rel="stylesheet">

    <style>
    .panel-success .panel-heading {

        background-color: #98CB00 !important;

    }

    .dropdown-toggle {

        border-radius: 6px !important;

    }
    </style>

</head>

<body>

    <div class="wrapper">

        <?php require_once "header.php";?>

        <div class="main-panel">

            <nav class="navbar navbar-default">

                <div class="container-fluid">

                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle">

                            <span class="sr-only">Toggle navigation</span>

                            <span class="icon-bar bar1"></span>

                            <span class="icon-bar bar2"></span>

                            <span class="icon-bar bar3"></span>

                        </button>

                        <a class="navbar-brand" href="#">Bulk Add

                            <a href="<?=$admin->getDomain()?>/admin/imdbs.csv" style="margin-top:21px;" download
                                class="btn btn-success btn-fill btn-xs pull-left">

                                <i class="ti-download"></i>

                                Download Sample File

                            </a>

                        </a>

                    </div>

                    <div class="collapse navbar-collapse">

                        <ul class="nav navbar-nav navbar-right">

                            <li>

                                <a href="<?=$admin->getDomain()?>">

                                    <i class="ti-arrow-left"></i>

                                    <p>Back to User Area</p>

                                </a>

                            </li>

                        </ul>

                    </div>

                </div>

            </nav>
            <div class="container-fluid">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Add Bulk Episodes</h4>
                    </div>
                    <div class="content">



                        <div class="container-fluid">

                            <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->

                            <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->

                            <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"-->
                            <!--    rel="stylesheet" />-->

                            <!--<script-->
                            <!--    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js">-->
                            <!--</script>-->

                            <!--<link-->
                            <!--    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css"-->
                            <!--    rel="stylesheet" />-->



                            <form method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading panel-title">Choose Series</div>
                                            <div class="panel-body">
                                                <select name="video_id" 
                                                    class="form-control selectpicker" id="select-country"
                                                    data-live-search="true" required>
                                                        <?php
                                                            while ($movie = $movies->fetch_object()) 
                                                            {
                                                                if ($movie->is_series == 1) {
                                                                    echo '<option value="' . $movie->id . '"> ' . $movie->movie_name . ' (' . $movie->movie_year . ')' . ' ' . $movie->imdbid . ' </option>';
                                                                }
                                                            }
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading panel-title">Choose your file</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <input type="file" name="excel-upload" class="form-control" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-fill" name="upload">Upload</button>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
            <footer class="footer">

                <div class="container-fluid">

                    <div class="copyright pull-left">

                        &nbsp;

                    </div>

                </div>

            </footer>

        </div>

    </div>

</body>

<script src="assets/js/jquery.js" type="text/javascript"></script>

<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

<script src="assets/js/bootstrap-checkbox-radio.js"></script>

<script src="assets/js/chartist.min.js"></script>

<script src="assets/js/bootstrap-notify.js"></script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="assets/js/bootstrap-select.min.js"></script>

<script src="assets/js/theme.js"></script>

<!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->

<!--<script src="//netsh.pp.ua/upwork-demo/1/js/typeahead.js"></script>-->

</html>
