<?php
require 'class_IMDb.php';
$imdb = new IMDb(true);
$imdb->summary=false;
$movie = $imdb->find_by_id($_REQUEST['imdbid']);

if($movie->response_msg != "Fail") {
$description = $movie->plot->outline;
$title = ($movie->type == "tv_episode")?"Episode":"Movie";
$post_data_json = '<div class="poster" ><h2>'.$title.' Information</h2></div>';

$post_data_json .= '<table>
<tr>
<th>Title</th>
<td>'.$movie->title.'</td>
</tr>
<tr>
<th>Year</th>
<td>'.$movie->year.'</td>
</tr>
<tr>
<th>Ratings</th>
<td>'.$movie->rating.'</td>
</tr>
<tr>
<th>Description</th>
<td>'.$description.'</td>
</tr>
<tr><th>Genres</th><td>';
$genres = $movie->genres;
$count = 0;
foreach($genres as $category) {

	$post_data_json .= $category;
	
	$count++;
	if($count<count($genres)) {
		$post_data_json .= ',';
	}
}
$post_data_json .= '</td></tr><tr><th>Starcast</th><td>';
$cast_summary = $movie->cast_summary;
$count1 = 0;
foreach($cast_summary as $actors) {
	$post_data_json .= $actors->name->name;
	$count1++;
	if($count1<count($cast_summary)) {
		$post_data_json .= ',';
	}
}
echo $post_data_json .= '</td></tr><tr>
<th>Poster</th>
<td><img height="150" src="'.$movie->image->url.'"/></td>
</tr></table>';
}
else {
echo "no";
}
?>