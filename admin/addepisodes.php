<?php
function get_episodes($name, $year = "") { //url to connect to 
$url = "http://imdbapi.poromenos.org/js/?name=".urlencode($name); if(trim($year) == "") { $url.="&year=".urlencode($year); } $curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $url); 
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
$curlData = curl_exec($curl); 
curl_close($curl); 
$data = json_decode($curlData, true); $arr = array(); 
//restructuring data to by seasons and episodes 
foreach($data as $key => $val) { 
	if(is_array($val)) { 
		foreach($val as $key => $value) { 
			if($key == "episodes") { 
				foreach($value as $num) { 
					$arr[$num['season']][$num['number']] = $num['name']; 
				} 
			} 
		} 
	} 
	else { echo "<li>".$key.": ".$val."</li>"; } } //sorting episodes 
foreach($arr as $key => $season) { 
	ksort($arr[$key]); 
} //sorting seasons and returning array 
ksort($arr); 
return $arr; 
} 
$episodes = get_episodes("Daredevil"); 
echo "<pre>"; print_r($episodes); echo "</pre>";

?>