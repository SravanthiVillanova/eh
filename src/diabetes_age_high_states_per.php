<?php
include_once 'vizHealth_db.php';

$yr = $_POST['year'];
$age = $_POST['age'];
$query = "SELECT `abbr` as 'State',`percentage` FROM `diabetes_age_percent` where `age` = '".$age."' and `year` = '".$yr."' order by `percentage` desc limit 10";

$result=mysqli_query($con,$query); // The result of your query
while ($tableRow = mysqli_fetch_assoc($result)) { // Loops 3 times if there are 3 returned rows... etc  
$data[] = $tableRow; 
}

echo json_encode( $data );
?>