<?php
include_once 'vizHealth_db.php';

$yr = $_POST['year'];

$data1 = getData($con,$yr,'Total_Adults','Adults');
$data2 = getData($con,$yr,'18-24','18-24');
$data3 = getData($con,$yr,'25-34','25-34');
$data4 = getData($con,$yr,'35-44','35-44');
$data5 = getData($con,$yr,'45-54','45-54');
$data6 = getData($con,$yr,'55-64','55-64');
$data7 = getData($con,$yr,'65+','65Plus');

$data31 = array_map("modifyArray", $data1, $data2);
$data41 = array_map("modifyArray", $data31, $data3);
$data51 = array_map("modifyArray", $data41, $data4);
$data61 = array_map("modifyArray", $data51, $data5);
$data71 = array_map("modifyArray", $data61, $data6);
$data = array_map("modifyArray", $data71, $data7);
//var_dump($data);

function getData($con,$yr,$age,$ag_col)
{
	$query = "SELECT abbr as 'State',percentage as '".$ag_col."' FROM `obesity_age_long_percent` where `age` = '".$age."' and `year` = '".$yr."'";
	$result=mysqli_query($con,$query); // The result of your query
	while ($tableRow = mysqli_fetch_assoc($result)) { // Loops 3 times if there are 3 returned rows... etc  
		$rows[] = $tableRow; 
	}
	return $rows;
}

function modifyArray($a, $b)
{
    if (!empty($a) && !empty($b)) {
        return array_merge($a, $b);
    } else if (!empty($a) && empty($b)) {
        return $a;
    }  else if (empty($a) && !empty($b)) {
        return $b;
    }
}

echo json_encode( $data );
?>