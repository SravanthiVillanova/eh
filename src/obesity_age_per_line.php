<?php
include_once 'vizHealth_db.php';

$data_var = $_POST['age'];
$query = "SELECT * FROM `obesity_ages_wide_percent` where `age` = '".$data_var."'"; 
$result=mysqli_query($con,$query); // The result of your query
//$result = mysqli_fetch_all($result);
while ($tableRow = mysqli_fetch_assoc($result)) { // Loops 3 times if there are 3 returned rows... etc  
$data[] = $tableRow; 
}
//var_dump($result);
echo json_encode( $data );
?>