<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$genres = $admin->getGenres();
$actors = $admin->getActors();
// $json=file_get_contents("http://imdbapi.org/?title=titanic");
// $info=json_decode($json);
// print_r($info);
								$curl = curl_init();
								curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
								curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
								curl_setopt_array($curl, array(
								 CURLOPT_URL => "https://api.themoviedb.org/3/find/tt0306414?api_key=".TMDB_KEY."&language=en-US&external_source=imdb_id",
								 CURLOPT_RETURNTRANSFER => true,
								 CURLOPT_ENCODING => "",
								 CURLOPT_MAXREDIRS => 10,
								 CURLOPT_TIMEOUT => 30,
								 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,				
								 ));
								$response = curl_exec($curl);
								//print_r($response);
								$err = curl_error($curl);
								curl_close($curl);
								if ($err) {
								 echo "cURL Error #:" . $err;
								} else {
							 	//echo $response;
							 $json = json_decode($response);
							 $array = get_object_vars($json);
							 // echo '<pre>';
							 // print_r($array['tv_results']);
							 //  echo '</pre>';
							foreach($array['tv_results'] as $cast) {
                echo $name = $cast->name;
                echo $id = $cast->id.'<br>';
                echo $vote_count = $cast->vote_count;
                echo $vote_average = $cast->vote_average;
                echo $first_air_date = $cast->first_air_date;
                echo $poster_path = $cast->poster_path;
                echo $overview = $cast->overview;
                $genre_ids = $cast->genre_ids;
                print_r($genre_ids);
                $origin_country = $cast->origin_country;
                print_r($origin_country); 
            }
          }
 //                  $curl = curl_init();
 //  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
 //  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
 //  curl_setopt_array($curl, array(
 //   CURLOPT_URL => "https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US",
 //   CURLOPT_RETURNTRANSFER => true,
 //   CURLOPT_ENCODING => "",
 //   CURLOPT_MAXREDIRS => 10,
 //   CURLOPT_TIMEOUT => 30,
 //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 //   ));
 //  $response = curl_exec($curl);
 // //print_r($response);
 //  $err = curl_error($curl);
 //  curl_close($curl);
 //    $json = json_decode($response);
 //    $array = get_object_vars($json);
 //    // echo "<pre>";
 //    // print_r($array);
 //    // echo "</pre>";
 //    $aname = $array['name'];
 //    $birthday = $array['birthday'];
 //    $place_of_birth = $array['place_of_birth'];
 //    $biography = addslashes($array['biography']);
 //    $imdb_id = $array['imdb_id'];
 //    $actor_img = $array['profile_path'];
 //     $im_url_c = "https://image.tmdb.org/t/p/original".$actor_img;
 //      if($actor_img != ''){
 //                          $extension = strtolower(end(explode('.',$actor_img)));
 //                          $c_actor = generate_postname($aname).'_actor'.time().'.'.$extension;
 //                          $ur11 = $im_url_c;
 //                          resize(file_get_contents($im_url_c),$c_actor,UPLOAD_PATH.'actors/',50);
 //                           $img = UPLOAD_PATH.'actors/'.$c_actor;
 //            //file_put_contents($img, file_get_contents($im_url_c));

 //                  }
 //                  else {
 //                      $c_actor = "";
 //                  }
 //    $sql1 = "SELECT * FROM actors WHERE actor_name LIKE '%".$aname."%'";
 //                $result1 = $db->query($sql1);
 //                while($row1 = $result1->fetch_assoc()) {
 //                $actor_id = $row1['id'];
 //             echo  $a = "UPDATE actors SET actor_name = '$aname',actor_picture ='$c_actor',actor_nconst = '$nconst',birthday='$birthday', place_of_birth='$place_of_birth',biography='$biography',actor_img_url='$im_url_c',imdbid='$imdb_id' WHERE id = '$actor_id'";
 //                 // if($row1['actor_nconst'] == "") {
 //                $db->query($a);
 //                echo "update";
 //              }
 //            }
 //          }
 //        }
							?>	
							<?php
// $target_dir = "../uploads/";
// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// $uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
// 	//echo $_FILES["fileToUpload"]["tmp_name"];
// 	$urlfrom = "https://image.tmdb.org/t/p/original/mup8hnEWUPP5kOHhk62hTnoCMZ5.jpg";
//     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//     if($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }
// Check if file already exists

    // if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    //     echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    // } else {
    //     echo "Sorry, there was an error uploading your file.";
    // }

?>
<!-- <!DOCTYPE html>
<html>
<body>

<form method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html> -->
<!-- // --> <?php
							// 	$curl = curl_init();
							// 	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
							// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
							// 	curl_setopt_array($curl, array(
							// 	 CURLOPT_URL => "https://api.themoviedb.org/3/person/35776?api_key=".TMDB_KEY."&language=en-US",
							// 	 CURLOPT_RETURNTRANSFER => true,
							// 	 CURLOPT_ENCODING => "",
							// 	 CURLOPT_MAXREDIRS => 10,
							// 	 CURLOPT_TIMEOUT => 30,
							// 	 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,				
							// 	 ));
							// 	$response = curl_exec($curl);
							// 	//print_r($response);
							// 	$err = curl_error($curl);
							// 	curl_close($curl);
							// 	if ($err) {
							// 	 echo "cURL Error #:" . $err;
							// 	} else {
							//  	//echo $response;
							//  $json = json_decode($response);
							//  $array = get_object_vars($json);
							// print_r($array);
							// 		foreach($array as $key =>$value){ 
							// 		echo $key.'='.$value.'<br/>';
							// 		}
							// 	}
							?>	
							<?php
// $target_dir = "../uploads/";
// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// $uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// // Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
// 	//echo $_FILES["fileToUpload"]["tmp_name"];
// 	$urlfrom = "https://image.tmdb.org/t/p/original/mup8hnEWUPP5kOHhk62hTnoCMZ5.jpg";
//     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//     if($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }
// Check if file already exists
 // if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    //     echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    // } else {
    //     echo "Sorry, there was an error uploading your file.";
    // }

?>
<!-- <!DOCTYPE html>
<html>
<body>

<form method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html> -->