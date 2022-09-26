<?php
$koneksi	= mysqli_connect("127.0.0.1","root","","attendance");
$url 		= 'http://mandiricoal.co.id:1880/RFC/ATTENDANCE/';
$no 		= 0;

$query		= mysqli_query($koneksi, "SELECT * FROM log");
while($row 	= mysqli_fetch_array($query)) {
	$data[$no] = array(
		'APPS'  	=> 'SEMAR',  			// Nama Aplikasi
		'MODULE'  	=> 'ATTENDANCE',  		// Nama Module
		'EMPLOYEE'  	=> $row['log_employee'],     	// NIK SAP Karyawan, 	contoh -> 0402
		'DATES'  	=> $row['log_date'],	  	// Tanggal Absensi,  	contoh -> 2022-01-01
		'ABSENCE'  	=> $row['log_absence'],  	// Status Absensi,  	contoh -> H / I / S
		'WORKTIME'  	=> $row['log_worktime'],	// Shift Kerja,  	contoh -> DAY / NIGHT
		'TSTART'  	=> $row['log_time_start'],  	// Absen Datang,	contoh -> 07:00:00
		'TEND'  	=> $row['log_time_end'],	// Absen Pulang, 	contoh -> 17:00:00
		'TOTAL'  	=> $row['log_total'],	  	// Total Jam Kerja,	contoh -> 10
		'ROSTER'  	=> $row['log_roster'],	  	// Roster,		contoh -> H8
	);
	$no++;
	@array_push($data[],$data[$no]);
}

$data_string = json_encode(array_filter($data)); 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
$out = curl_exec($ch);
curl_close($ch);
echo $out;
?> 
