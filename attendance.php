<?php
// Establish database connection
$koneksi = mysqli_connect("127.0.0.1", "root", "", "attendance");

// Define API endpoint URL
$url = 'http://mandiricoal.co.id:1880/RFC/ATTENDANCE/';

// Initialize array of data to send
$data = array();

// Fetch log data from the database
$query = mysqli_query($koneksi, "SELECT * FROM log");
while ($row = mysqli_fetch_assoc($query)) {
    // Map log data to an array of required fields
	$data[] = array(
		'EMPLOYEE' 	=> $row['log_employee'],
		'DATES' 	=> $row['log_date'],
		'ABSENCE' 	=> $row['log_absence'],
		'WORKTIME' 	=> $row['log_worktime'],
		'TSTART' 	=> $row['log_time_start'],
		'TEND' 		=> $row['log_time_end'],
		'TOTAL' 	=> $row['log_total'],
		'ROSTER' 	=> $row['log_roster']
	);
}

// Filter out any empty elements in the array
$data = array_filter($data);

// Encode data as JSON string
$data_string = json_encode($data);

// Send data to API endpoint via HTTP POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

// Execute the cURL request and capture the response
$out = curl_exec($ch);

// Close the cURL session
curl_close($ch);

// Output response from API endpoint
echo $out;
?>
