<?php
    require('../core/config.php');
    require('../core/system.php');
    //$video = 'https://drive.google.com/file/d/1ItGfKy3tqmteJgfmJ6ohOQWrDVf3oSCa/preview';
    $video = 'https://www.youtube.com/watch?v=mzscg6Umdtk';
    $ex = new stdclass;
    $ex->video_url = $video;
    _storeFilm('tt0249371', $ex);
    $data = run_tmdb_curl('tt0249371');
    echo "<pre>";
    //print_r($data->movie_results[0]);
    print_r($data);
    exit;
    return print_r($data);
    ini_set('max_execution_time', '0');

    /* $movies = $db->query('SELECT * FROM `movies`');
    $movies_arr = [];
    while( $movie = $movies->fetch_object())
    {
        $movies_arr[]= $movie;
        
    }
    echo "<pre>";
    echo $movies->num_rows;
    echo '<br>';
    echo count($movies_arr); */
     /* $mansory_image_path= glob("../uploads/masonry_images/*");
     $poster_image_path= glob("../uploads/poster_images/*");
     $folder_path = '../uploads/actors/*';
     $actors_folder= glob($folder_path); //scandir($folder_path); //  
     emptyFolder($actors_folder);
     emptyFolder($mansory_image_path);
     emptyFolder($poster_image_path);

     //init db file
    $db->query('DELETE FROM `genres`');  //genres
    $db->query('DELETE FROM `genres_relations`');  //genres relations
    $db->query('DELETE FROM `actors`');  //actors
    $db->query('DELETE FROM `actor_relations`');  //actors relations
    $db->query('DELETE FROM `ratings`');  //ratings */
  
    /* $imdbid = 'tt2302755';
    $movieOutput = run_tmdb_curl($imdbid)->movie_results[0];
    $db_movie = $db->query('SELECT * FROM `movies` WHERE `imdbid`="'.$imdbid.'" LIMIT 1');
    $result = $db_movie->fetch_object();
    updateMovieInfo($result); */
    /* $db_movies = $db->query('SELECT * FROM `movies` ORDER BY id DESC limit 0,100');
    while($each_movie = $db_movies->fetch_object())
    {
        updateMovieInfo($each_movie);
    } */

    function updateMovieInfo($movie_obj)
    {

        $mov_obj = $movie_obj;
        $is_series =  $mov_obj->is_series;
        $imdbid = $mov_obj->imdbid;
        $movie_id = $mov_obj->id;
        global $db;
        try {
            if($is_series == 1)
            {
                //series test
                $movieOutputs = run_tmdb_curl($imdbid);
                $make_geners = array();
                foreach ($movieOutputs->tv_results as $movieOutput) 
                {
    
                    $tv_id = $movieOutput->id;
                    if ($video_name != "") {
                        $new_file_name = md5(mt_rand()) . '_video.mp4';
                        $url = $video_name;
                        $img = '../uploads/masonry_images/' . $new_file_name;
                        file_put_contents($img, file_get_contents($url));
                    }
                    /*Geners Start*/
                    $genres = $movieOutput->genre_ids;
                    $catName = str_replace(' ', '-', $catName);
                    foreach ($genres as $category) 
                    {
                        $catName = json_decode(@file_get_contents("https://api.themoviedb.org/3/genre/{$category}?api_key=".TMDB_KEY))->name;
                        if ($catName != '') 
                        {
                            $catName = str_replace(' ', '-', $catName);
                            $sql = "SELECT * FROM genres WHERE genre_name LIKE '%" . $catName . "%'";
                            $result = $db->query($sql);
                            if ($result->num_rows <= 0) 
                            {
                                $catName = str_replace(' ', '-', $catName);
                                $newinsert = $db->query("INSERT INTO genres(genre_name,is_kid_friendly) VALUES ('" . $catName . "','" . $is_kid_friendly . "')");
                                $gener_id = $db->insert_id;
                            } 
                            else 
                            {
                                while ($row = $result->fetch_assoc()) {
                                    $gener_id = $row['id'];
                                }
                            }
                        }
                    }
                    $make_geners[] = 25;
                    $genres_video = implode(",",$make_geners);
                    /*Geners Ends*/
    
                    /*Actors Starts*/
                    $json = runCurl("https://api.themoviedb.org/3/tv/{$tv_id}/credits?api_key=".TMDB_KEY."&language=en-US");
                    $array = get_object_vars($json);
                    if ($array['cast']) {
                        foreach ($array['cast'] as $cast) {
                            $name_c = $cast->name;
                            $nconst = $cast->id;
                            $name_c = $cast->name;
                            $nconst = $cast->id;
                            $json = runCurl("https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US");
                            $array = get_object_vars($json);
                            $aname = $array['name'];
                            $birthday = $array['birthday'];
                            $place_of_birth = $array['place_of_birth'];
                            $biography = addslashes($array['biography']);
                            $imdb_id = $array['imdb_id'];
                            $actor_img = $array['profile_path'];
                            $im_url_c = "https://image.tmdb.org/t/p/original" . $actor_img;
                            if ($actor_img != '') {
                                $extension = strtolower(end(explode('.', $actor_img)));
                                $c_actor = generate_postname($aname) . '_actor' . time() . '.' . $extension;
                                $ur11 = $im_url_c;
                                resize(file_get_contents($im_url_c), $c_actor, UPLOAD_PATH . 'actors/', 50);
                                $img = UPLOAD_PATH . 'actors/' . $c_actor;
                            } 
                            else 
                            {
                                $c_actor = "";
                            }
    
    
                            $sql1 = "SELECT * FROM actors WHERE actor_name LIKE '%" . $aname . "%'";
                            $result1 = $db->query($sql1);
                            if ($result1->num_rows <= 0) 
                            {
                                $db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst,birthday,place_of_birth,biography,actor_img_url,imdbid) VALUES ('" . $aname . "','" . $c_actor . "','" . $nconst . "','" . $birthday . "','" . $place_of_birth . "','" . $biography . "','" . $im_url_c . "','" . $imdb_id . "')");
                                $actor_id = $db->insert_id;
                            } 
                            else 
                            {
                                while ($row1 = $result1->fetch_assoc()) {
                                    $actor_id = $row1['id'];
                                    $a = "UPDATE actors SET actor_name = '$aname',actor_picture ='$c_actor',actor_nconst = '$nconst',birthday='$birthday', place_of_birth='$place_of_birth',biography='$biography',actor_img_url='$im_url_c',imdbid='$imdb_id' WHERE id = '$actor_id'";
                                    $db->query($a);
                                }
                            }
                            $make_actors[] = $actor_id;
                        }
                    }
                    
                    $makeactors = implode(",", $make_actors);
                    $actors = explode(",", $makeactors);
                    /*Actors Ends*/
    
    
                    $description = $movieOutput->overview;
                    $video_name = $db->real_escape_string($movieOutput->name);
                    $video_description = $db->real_escape_string($description);
                    $video_categories = $genres_video ;
                    $is_kid_friendly = 0;
                    $im_url = "https://image.tmdb.org/t/p/original" . $movieOutput->poster_path;
                    if ($im_url != "") {
                        $extension = strtolower(end(explode('.', $movieOutput->poster_path)));
                        $new_file_name_2 = generate_postname($movieOutput->name) . '_poster' . time() . '.' . $extension;
                        $url = $im_url;
                        $img2 = '../uploads/poster_images/' . $new_file_name_2;
                        $poster = '../uploads/masonry_images/' . $new_file_name_2;
                        file_put_contents($img2, file_get_contents($im_url));
                        file_put_contents($poster, file_get_contents($im_url));
                        resize($im_url, $new_file_name_2, '../uploads/masonry_images/');
                    }
                    $json = runCurl("https://api.themoviedb.org/3/movie/{$imdbid}/alternative_titles?api_key=".TMDB_KEY);
                    $ary = get_object_vars($json);
                    $s = $ary['titles'];
                    $key = array();
                    $features = '';
                    foreach ($s as $value) {
                        if ($value->iso_3166_1 == 'US' || $value->iso_3166_1 == 'USA') {
                            $key[] = $value->iso_3166_1;
                            $features = $value->title;
                            $features = str_replace("'", $features);
                        }
                    }
                    $rating = $movieOutput->vote_average;
                    if (isset($movie->rating)) {
                        $rating = round((($movie->rating) / 2), 1);
                    }
                    $year = explode('-',$movieOutputs->tv_results[0]->first_air_date)[0];
    
                    $db->query("UPDATE movies
                     set
                       movie_name = '$video_name'
                      ,movie_plot = '$video_description' 
                      ,movie_genres = '$video_categories'
                      ,movie_poster_image = '$new_file_name_2'
                      ,movie_thumb_image = '$new_file_name_2'
                      ,movie_rating = '$rating'
                      ,movie_year = '$year'
                      WHERE id = '$movie_id'
                    ");
    
                    $db->query("INSERT INTO ratings(movie_id,user_id,rating) VALUES ('{$movie_id}','22','{$rating}')");
                    if(!empty($actors))
                    {
                        foreach ($actors as $actor => $actor_id) 
                        {
                            $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('{$movie_id}','{$actor_id}')");
                        }
                    }
        
                    if(!empty($make_geners))
                    {
                        foreach ($make_geners as $make_gener => $geners_id) 
                        {
                            $db->query("INSERT INTO genres_relations(movie_id,genres_id) values($movie_id,$geners_id)");
                        }
                    }
                    $db->query("UPDATE movies SET all_starcast = 'yes' WHERE id = '{$movie_id}'");
                }
            }
            else
            {
        
                $movieOutput = run_tmdb_curl($imdbid)->movie_results[0];
                if (!empty($movieOutput)) 
                {
                    /*Geners Start*/
                    $genres = $movieOutput->genre_ids;
                    $is_kid_friendly = 0;
                    foreach ($genres as $category) {
                        $catName = runCurl("https://api.themoviedb.org/3/genre/{$category}?api_key=".TMDB_KEY)->name;
                        $catName = str_replace(' ', '-', $catName);
                        $sql = "SELECT * FROM genres WHERE genre_name LIKE '%" . $catName . "%'";
                        $result = $db->query($sql);
                        if ($result->num_rows <= 0) 
                        {
                            $catName = str_replace(' ', '-', $catName);
                            $newinsert = $db->query("INSERT INTO genres(genre_name,is_kid_friendly) VALUES ('" . $catName . "','" . $is_kid_friendly . "')");
                            $gener_id = $db->insert_id;
                        } 
                        else 
                        {
                            while ($row = $result->fetch_assoc()) 
                            {
                                $gener_id = $row['id'];
                            }
                        }
                        $make_geners[] = $gener_id;
                    }
                    $genres_video = implode(",", $make_geners);
                    /*Geners Ends*/
    
    
                    /*Actors Starts*/
                    $json = runCurl("https://api.themoviedb.org/3/movie/{$imdbid}/credits?api_key=".TMDB_KEY."&external_source=imdb_id");
                    $array1 = get_object_vars($json);
                    foreach ($array1['cast'] as $cast) {
                        $name_c = $cast->name;
                        $nconst = $cast->id;
                        $name_c = $cast->name;
                        $nconst = $cast->id;
                        $json = runCurl("https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US");
                        $array = get_object_vars($json);
                        $aname = $array['name'];
                        $birthday = $array['birthday'];
                        $place_of_birth = $array['place_of_birth'];
                        $biography = addslashes($array['biography']);
                        $imdb_id = $array['imdb_id'];
                        $actor_img = $array['profile_path'];
                        $im_url_c = "https://image.tmdb.org/t/p/original" . $actor_img;
                        if ($actor_img != '') 
                        {
                            $get_extension = isset(explode('.', $actor_img)[1])?explode('.', $actor_img)[1]:'jpg';
                            $extension = strtolower($get_extension);
                            $c_actor = generate_postname($aname) . '_actor' . time() . '.' . $extension;
                            $ur11 = $im_url_c;
                            resize(file_get_contents($im_url_c), $c_actor, UPLOAD_PATH . 'actors/', 50);
                            $img = UPLOAD_PATH . 'actors/' . $c_actor;
                        } 
                        else 
                        {
                            $c_actor = "";
                        }
    
                        $sql1 = "SELECT * FROM actors WHERE actor_name LIKE '%" . $aname . "%'";
                        $result1 = $db->query($sql1);
                        if (isset($result1->num_rows) && $result1->num_rows <= 0) 
                        {
    
                            $db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst,birthday,place_of_birth,biography,actor_img_url,imdbid) VALUES ('" . $aname . "','" . $c_actor . "','" . $nconst . "','" . $birthday . "','" . $place_of_birth . "','" . $biography . "','" . $im_url_c . "','" . $imdb_id . "')");
                            $actor_id = $db->insert_id;
                        } 
                        else 
                        {
                            if ($result1) {
                                while ($row1 = $result1->fetch_assoc()) 
                                {
                                    $actor_id = $row1['id'];
                                    $a = "UPDATE actors SET actor_name = '$aname',actor_picture ='$c_actor',actor_nconst = '$nconst',birthday='$birthday', place_of_birth='$place_of_birth',biography='$biography',actor_img_url='$im_url_c',imdbid='$imdb_id' WHERE id = '$actor_id'";
                                    $db->query($a);
                                }
                            }
                        }
                        $make_actors[] = $actor_id;
                    }
                    
        
                    $makeactors = implode(",", $make_actors);
                    $actors = explode(',', $makeactors);
                    /*Actors Ends*/
        
                    $description = $movieOutput->overview;
                    $video_name = $db->real_escape_string($movieOutput->title);
                    $video_description = $db->real_escape_string($description);
                    $video_categories = $genres_video;
                    $is_kid_friendly = 0;
                    $im_url = "https://image.tmdb.org/t/p/original" . $movieOutput->poster_path;
    
                    if ($video_name != "") 
                    {
                        $new_file_name = md5(mt_rand()) . '_video.mp4';
                        $url = $video_name;
                        $img = '../uploads/masonry_images/' . $new_file_name;
                        file_put_contents($img, file_get_contents($url));
                    }
    
                    if ($im_url != "") {
                        $extension = strtolower(end(explode('.', $movieOutput->poster_path)));
                        $new_file_name_2 = generate_postname($movieOutput->title) . '_poster' . time() . '.' . $extension;
                        $url = $im_url;
                        $img2 = '../uploads/poster_images/' . $new_file_name_2;
                        file_put_contents($img2, file_get_contents($im_url));
                        resize($im_url, $new_file_name_2, '../uploads/masonry_images/');
                    }
                    
                        $json = runCurl("https://api.themoviedb.org/3/movie/{$imdbid}/alternative_titles?api_key=".TMDB_KEY);
                        $ary = get_object_vars($json);
                        $s = $ary['titles'];
                        $key = array();
                        $features = '';
                        foreach ($s as $value) 
                        {
                            if ($value->iso_3166_1 == 'US' || $value->iso_3166_1 == 'USA') {
                                $key[] = $value->iso_3166_1;
                                $features = $value->title;
                                $features = str_replace("'", $features);
                            }
                        }
                        $rating = $movieOutput->vote_average;
                        if (isset($movie->rating)) 
                        {
                            $rating = round((($movie->rating) / 2), 1);
                        }
                        $year = explode('-',$movieOutput->release_date)[0];
    
                        $db->query("UPDATE movies
                            set
                                movie_name = '$video_name'
                                ,movie_plot = '$video_description' 
                                ,movie_genres = '$video_categories'
                                ,movie_poster_image = '$new_file_name_2'
                                ,movie_thumb_image = '$new_file_name_2'
                                ,movie_rating = '$rating'
                                ,movie_year = '$year'
                                WHERE id = '$movie_id'
                            ");
    
                        $db->query("INSERT INTO ratings(movie_id,user_id,rating) VALUES ('{$movie_id}','22','{$rating}')");
                        
                        if(!empty($actors))
                        {
                            foreach ($actors as $actor => $actor_id) 
                            {
                                $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('{$movie_id}','{$actor_id}')");
                            }
                        }
    
                        if(!empty($make_geners))
                        {
                            foreach ($make_geners as $Mg => $Gi) {
                                $db->query("INSERT INTO genres_relations(movie_id,genres_id) values($movie_id,$Gi)");
                            }
                        }
                        $db->query("UPDATE movies SET all_starcast = 'yes' WHERE id = '{$movie_id}'");
                }
            }
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e->getMessage());
        }
        
    }


    function emptyFolder($glob_results)
    {
        foreach ($glob_results as $single_file) 
        {
            if(is_file($single_file))
            {
                unlink($single_file);
            }
        }
    }
     

?>