<?php 
page_protect();

$out_file = 'ao_leads.csv';          //This would open file.csv in the blog root.
$use_pod = 'user_profiles';         //The name of the pod to use.
$export = "csv";               // Can be "php" or "csv" (default: php)
$Record = new PodAPI($use_pod, $export); 
$data = $Record->export();

//global $user_ID , $user_level;
//get_currentuserinfo();     //Get the information about the current user.
//if ($user_ID && 10 == $user_level) {                       //Check to see if the user is logged in and is an administrator

//  if (($fp = fopen($out_file, 'w')) === FALSE) {         //Attempt to open $out_file for writing and check for success.
//    die ("Could not open $out_file. Export aborted.");  //Fail if unable to.
//  }

?>
	<table width=600 border=0 cellpadding=0 cellspacing=0>
<?php

  if ('csv' == strtolower($export)) {            //If a CSV file is desired
    $data_keys = array_keys($data[0]);      //Get the pod column labels
    echo '<tr><td><p>', implode(", ", $data_keys), '</p></td></tr>';
    //fputcsv($fp, $data_keys);                    //Write the column labels as the first line of the CSV file.
    foreach ($data as $line) {                    //Loop through the data line by line
       foreach ($line as &$field) {                //Loop through each line of data by field
          if (is_array($field)) {                     //If the field is a PICK column
           array_walk($field, 'comma_trans');      //Translate any commas in the field to HTML "&#44;"
           $field = 'array(' . implode(', ', $field) . ')';  //Implode the items into a comma separated list wrapped in array().
          }
       }
	  echo '<tr><td><p>', implode(", ", $line), '</p></td></tr>';
      //fputcsv($fp, $line);       //Write the line to the file.
    } 
  } else {                           //Otherwise, output the data as PHP
    fwrite($fp, var_export($data, TRUE));
  }
  //fclose($fp); //Close the file
  echo "</table>";

  //echo "Export complete. <a href='$out_file'>Open $out_file</a><br /><br />\n";

function comma_trans(&$item)
{
    $item = strtr($item, ',', '&#44'); //Translates any commas into their HTML entity.
}
?>


