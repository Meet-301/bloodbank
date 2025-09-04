<?php
session_start();
//error_reporting(0);
session_regenerate_id(true);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
	header("Location: index.php"); //
} else { ?>
	<table border="1">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Mobile No</th>
				<th>Email</th>
				<th>Age</th>
				<th>Gender</th>
				<th>Blood Group</th>
				<th>address</th>
				<th>Message </th>
				<th>posting date </th>
			</tr>
		</thead>

		<?php
		$filename = "Donor list";
		$sql = "SELECT * from  tblblooddonars ";
		$query = $dbh->prepare($sql);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_OBJ);
		$cnt = 1;
		if ($query->rowCount() > 0) {
			function getAddressFromCoordinates($lat, $lng)
			{
				$apiKey = 'e63d227950ce4e1fb7abbed6718bd981';
				$url = "https://api.opencagedata.com/geocode/v1/json?q=$lat+$lng&key=$apiKey";

				$response = file_get_contents($url);
				$json = json_decode($response, true);

				if ($json && isset($json['results'][0]['formatted'])) {
					return $json['results'][0]['formatted']; // full address
				} else {
					return "Unknown Location";
				}
			}

			$address = "Unknown Location";
			if (!empty($results)) {
				$lat = $results[0]->latitude;
				$lng = $results[0]->longitude;
				$address = getAddressFromCoordinates($lat, $lng);
			}
			foreach ($results as $result) {
				$address = getAddressFromCoordinates($result->latitude, $result->longitude);
				echo '  
<tr>  
<td>' . $cnt . '</td> 
<td>' . $complainNumber = $result->FullName . '</td> 
<td>' . $MobileNumber = $result->MobileNumber . '</td> 
<td>' . $EmailId = $result->EmailId . '</td> 
<td>' . $Gender = $result->Gender . '</td> 
<td>' . $Age = $result->Age . '</td> 
 <td>' . $BloodGroup = $result->BloodGroup . '</td>	
  <td>' . $address . '</td>	 
   <td>' . $BloodGroup = $result->Message . '</td>	
  <td>' . $BloodGroup = $result->PostingDate . '</td>	 					
</tr>  
';
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=" . $filename . "-report.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$cnt++;
			}
		}
		?>
	</table>
<?php } ?>