<?php
include_once 'vizHealth_db.php';

$age = $_POST['age'];
$yr = $_POST['year'];
//$query = "SELECT * FROM `diabetes_ages_percent` where `age` = '".$data_var."'"; 
$query = "SELECT abbr,percentage FROM `obesity_age_long_percent` where `age` = '".$age."' and `year` = '".$yr."'";
$result=mysqli_query($con,$query); // The result of your query
//$result = mysqli_fetch_all($result);
while ($tableRow = mysqli_fetch_assoc($result)) { // Loops 3 times if there are 3 returned rows... etc  
$data[] = $tableRow; 
}
//var_dump($data);
echo json_encode( $data );
?>