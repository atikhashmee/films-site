<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$movies = $admin->getFilms();
//print_r($movies);
//$_POST["keyword"]
//$query ="SELECT * FROM country WHERE country_name like '" . $_POST["keyword"] . "%' ORDER BY country_name LIMIT 0,6";
$getSeriesId = $db->query("SELECT id FROM genres WHERE genre_name LIKE '%Series%'")->fetch_assoc()['id'];
//debug($getSeriesId);
//echo "SELECT * FROM movies WHERE movie_genres LIKE '%$getSeriesId%' AND movie_name LIKE '".$_GET['keyword']."%' ORDER BY movie_name LIMIT 0,6";
$movies = $db->query("SELECT * FROM movies WHERE movie_genres LIKE '%$getSeriesId%' AND movie_name LIKE '".$_GET['keyword']."%' ORDER BY movie_name LIMIT 0,6");
?>
<ul id="country-list">
<?php
while($movie = $movies->fetch_object()) { 
      if($movie->is_series==1){
        ?>
        <li onClick="selectCountry('<?php echo $movie->movie_name; ?>');" data-option-array-index="<?php echo $movie->id; ?>"><?php echo $movie->movie_name; ?>
        	
        </li>
        <?php
      }
     // echo json_encode($data);
    } 
?>
</ul>