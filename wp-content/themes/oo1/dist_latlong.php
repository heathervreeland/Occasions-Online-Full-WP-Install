<html>
<body>
<?php
ob_implicit_flush(true);
require( '../../../wp-load.php' );
$result = pod_query("SELECT * FROM ao_dist_locations");
echo "<h1>Geocoding distribution location addresses...</h1>";
while ($row = mysql_fetch_assoc($result))
{
	if ($row['dist_longitude'] == '' || $row['dist_longitude'] == '') {
		echo "<p>Geocoding {$row[dist_id]} - {$row[dist_location]}...<br />";
		$pid = $row['dist_id'];
		$adresse = $row['dist_address1']; // grab the content of the "adresse" field in your pod
		$ville = $row['dist_city']; // grab the content of the "ville" field in your pod, ville is french for town
		$state = $row['dist_state']; // grab the content of the "ville" field in your pod, ville is french for town
		$zip = $row['dist_zip']; // grab the content of the "ville" field in your pod, ville is french for town
		
		$badstrings = array (" ","'","-","<br>"); // strings we shouldn't pass in the url
		$goodstrings = array ("+","",",",""); // strings we are going to replace them with
			
		$niceadresse = str_replace($badstrings,$goodstrings,$adresse); // convert address to url compatible address
		$niceville =  str_replace($badstrings,$goodstrings,$ville); // convert town to a url compatible town
		$nicestate =  str_replace($badstrings,$goodstrings,$state); // convert town to a url compatible town
		$nicezip =  str_replace($badstrings,$goodstrings,$zip); // convert town to a url compatible town
		$rawurl = $niceadresse.",".$niceville.",".$nicestate.",".$nicezip; // add them all together
		$niceurl = strtolower($rawurl); // make sure it is all lowercase, not sure why i did this :)
		
		$geourl = "http://maps.google.com/maps/api/geocode/json?address=".$niceurl."&sensor=false";
		echo "$geourl<br />";
		$geoinfo = file_get_contents($geourl); // get the geocoded info back in json format into the variable you could use curl for better performance but this is more compatible
		$decoded = json_decode($geoinfo); // decode json geoinfo into an object
		
		// make sure value is returned and allow manual change
		if ($decoded->status == "OK") {
			$latitude = $decoded->results[0]->geometry->location->lat; // copy lat into the field called lat in your pod
			$longitude = $decoded->results[0]->geometry->location->lng; // copy long into the field called long in your pod
			$sql = "UPDATE ao_dist_locations SET dist_latitude='$latitude', dist_longitude='$longitude' WHERE dist_id=$pid" ;
			pod_query($sql);
			echo "done... ($latitude / $longitude)<p/>";
		}
		else {
			$sql = "UPDATE ao_dist_locations SET dist_latitude='', dist_longitude='' WHERE dist_id=$pid" ;
			pod_query($sql);
			echo "failed... ({$decoded->status})<p/>";
		}
	}
}
?>
</body>
</html>
