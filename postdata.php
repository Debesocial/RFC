<!-- CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

<!-- TAB -->
<div class="container mt-5">
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="post-tab" data-toggle="tab" href="#post" role="tab" aria-controls="post" aria-selected="true">POST</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="view-tab" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="false">SAP Table</a>
		</li>
	</ul>
	<div class="tab-content" id="myTabContent">

		<!-- POST DATA -->
		<div class="tab-pane fade show active" id="post" role="tabpanel" aria-labelledby="post-tab">
			<div class="container">
				<div class="row">
					<div class="col-sm mt-5">
						<div class="card">
							<div class="card-header">
								<b>POST Data to SAP</b>
							</div>
							<div class="card-body">
								<form method="post" action="">
									<div class="form-group">
										<input type="number" class="form-control" name="param1" placeholder="param1" required>
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="param2" placeholder="param2" required>
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="param3" placeholder="param3" required>
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="param4" placeholder="param4" required>
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="param5" placeholder="param5" required>
									</div>
									<button value="submit" class="form-control btn-success" type="submit" id="submit">POST</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- VIEW TABLE -->
		<div class="tab-pane fade" id="view" role="tabpanel" aria-labelledby="view-tab">
			<div class="container">
				<div class="row">
					<div class="col-sm mt-5">
						<div class="card">
							<div class="card-header">
								<b>SAP Table</b><small> ( Up to 20 Rows )</small>
							</div>
							<div class="card-body">
								<table class="table table-sm">
									<thead>
										<tr>
											<th scope="col">Param1</th>
											<th scope="col">Param2</th>
											<th scope="col">Param3</th>
											<th scope="col">Param4</th>
											<th scope="col">Param5</th>
											<th scope="col">Date</th>
											<th scope="col">Time</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$json_string = file_get_contents("http://mandiricoal.co.id:1880/RFC/VIEWDATA/");
										$array 		 = json_decode($json_string, true);
										foreach($array['ITAB'] as $key => $value): ?>
											<tr>
												<td><?= $value['FIELD1']; ?></td>
												<td><?= $value['FIELD2']; ?></td>
												<td><?= $value['FIELD3']; ?></td>
												<td><?= $value['FIELD4']; ?></td>
												<td><?= $value['FIELD5']; ?></td>
												<td><?= substr($value['DATES'],0,4).'-'.substr($value['DATES'],4,2).'-'.substr($value['DATES'],6,2);?></td>
												<td><?= substr($value['TIMES'],0,2).':'.substr($value['TIMES'],2,2).':'.substr($value['TIMES'],4,2);?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// Set the URL to the API endpoint
	$url = 'http://mandiricoal.co.id:1880/RFC/POSTDATA/';

// Define the data to be sent to the API
	$data = array(
    'PARAM1' => $_POST['param1'],     // Contoh Parameter 1    
    'PARAM2' => $_POST['param2'],     // Contoh Parameter 2       
    'PARAM3' => $_POST['param3'],     // Contoh Parameter 3
    'PARAM4' => $_POST['param4'],     // Contoh Parameter 4
    'PARAM5' => $_POST['param5']      // Contoh Parameter 5
);

// Use array_push correctly to add $data to $data[]
	array_push($data, $data);

// Convert the data to a JSON string
	$data_string = json_encode(array_filter($data));

// Initialize a new cURL session
	$ch = curl_init();

// Set the cURL options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

// Execute the cURL request and capture the response
	$out = curl_exec($ch);

// Close the cURL session
	curl_close($ch);

	// OUTPUT MESSAGE
	echo "<script type='text/javascript'>alert('$out');
	history.back();
	</script>";

}?>
