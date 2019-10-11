<?php

	$errorsData = array("citiesCount" => '');
	
	// Handle the Form Respose
	if(isset($_GET['submit'])){
		
		// Get cities from Form
		$citiesNames = [];
		for ($i = 0; $i < count($_GET)-1; $i++) {
			if(array_values($_GET)[$i]){
				array_push($citiesNames, array_values($_GET)[$i]);
			}
		}

		// Validation the number of cities
		if(count($citiesNames) < 2){
			$errorsData['citiesCount'] = 'Liczba miast jest za mała, aby je porównać!';
		}else{
			$query = [];
			foreach ($citiesNames as $city) {
				// array_push($query, json_decode(file_get_contents('api.openweathermap.org/data/2.5/weather?q='.$city)));
			}
			array_push($query, json_decode(file_get_contents('data/example1.json')));
			array_push($query, json_decode(file_get_contents('data/example2.json')));
			array_push($query, json_decode(file_get_contents('data/example3.json')));
			array_push($query, json_decode(file_get_contents('data/example4.json')));

			$cities = [];

			if(!empty($citiesNames[0])){
				$city1 = new stdClass();
				$city1->name = $citiesNames[0];
				$city1->parameters = $query[0];
				array_push($cities, $city1);
			}

			if(!empty($citiesNames[1])){
				$city2 = new stdClass();
				$city2->name = $citiesNames[1];
				$city2->parameters = $query[1];
				array_push($cities, $city2);
			}

			if(!empty($citiesNames[2])){
				$city3 = new stdClass();
				$city3->name = $citiesNames[2];
				$city3->parameters = $query[2];
				array_push($cities, $city3);
			}

			if(!empty($citiesNames[3])){
				$city4 = new stdClass();
				$city4->name = $citiesNames[3];
				$city4->parameters = $query[3];
				array_push($cities, $city4);
			}	

			// Set fuction to descending sort by temp
			function sort_temp($a, $b) {
				if($a->parameters->main->temp == $b->parameters->main->temp){
					return 0;
				}
				return ($a->parameters->main->temp > $b->parameters->main->temp) ? -1 : 1;
			}

			// Set fuction to ascending sort by wind
			function sort_wind($a, $b) {
				if($a->parameters->wind->speed == $b->parameters->wind->speed){
					return 0;
				}
				return ($a->parameters->wind->speed < $b->parameters->wind->speed) ? -1 : 1;
			}

			// Set fuction to ascending sort by humidity
			function sort_humidity($a, $b) {
				if($a->parameters->main->humidity == $b->parameters->main->humidity){
					return 0;
				}
				return ($a->parameters->main->humidity < $b->parameters->main->humidity) ? -1 : 1;
			}
			
			// Define arrays to sort
			$tempArray = $cities;
			$windArray = $cities;
			$humidityArray = $cities;

			// Sorting arrays
			usort($tempArray, "sort_temp");
			usort($windArray, "sort_wind");
			usort($humidityArray, "sort_humidity");

			// Find cities position by weather parameter
			foreach($cities as $city){
				$city->temp_pos = array_search($city->name, array_column($tempArray, 'name'))+1;
				$city->wind_pos = array_search($city->name, array_column($windArray, 'name'))+1;
				$city->humidity_pos = array_search($city->name, array_column($humidityArray, 'name'))+1;
			}

			print_r($city1->humidity_pos." ".$city1->name." ".$city1->parameters->main->humidity);
			// print_r($city1->wind_pos);
			// print_r($city1->humidity_pos);
			?><br><?php
			print_r($city2->humidity_pos." ".$city2->name." ".$city2->parameters->main->humidity);
			// print_r($city2->wind_pos);
			// print_r($city2->humidity_pos);
			?><br><?php
			print_r($city3->humidity_pos." ".$city3->name." ".$city3->parameters->main->humidity);
			// print_r($city3->wind_pos);
			// print_r($city3->humidity_pos);
			?><br><?php
			print_r($city4->humidity_pos." ".$city4->name." ".$city4->parameters->main->humidity);
			// print_r($city4->wind_pos);
			// print_r($city4->humidity_pos);
			?><br><?php
		
		}
	}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>City weather</title>
		<link rel="stylesheet" href="cdn/bootstrap.css">
</head>
<body>
	<div class="container-fluid">
		<div class="row justify-content-center m-2">
			<h3 class="text-center">Sprawdź, które miasto ma najlepszą pogodę</h3>
		</div>
		
		<hr>
		<!-- Cities input -->
		<div class="row mx-0 justify-content-center">
			<form action="index.php" method="GET">
				<div class="form-row m-2 align-items-end">
					<div class="col">
						<label for="city1">Podaj miasto</label>
						<input type="text" class="form-control" id="city1" name="city1" placeholder="Pierwsze miasto" value="Hurzuf">
					</div>
					<div class="col">
						<label for="city2">Podaj miasto</label>
						<input type="text" class="form-control" id="city2" name="city2" placeholder="Drugie miasto" value="Novinki">
					</div>
					<div class="col">
						<label for="city3">Podaj miasto</label>
						<input type="text" class="form-control" id="city3" name="city3" placeholder="Trzecie miasto" value="Gorkhā">
					</div>
					<div class="col">
						<label for="city4">Podaj miasto</label>
						<input type="text" class="form-control" id="city4" name="city4" placeholder="Czwarte miasto" value="State of Haryāna">
					</div>
					<div class="col">
						<button id="submit" type="submit" name="submit" class="btn btn-primary mx-2">Porównaj</button>
					</div>
				</div>
			</form>
		</div>
		<!-- error message -->
		<div class="row">
			<div class="col text-center">
			<?php echo '<div class="text-danger">'.$errorsData["citiesCount"].'</div>' ?>
			</div>
		</div>
		<!-- Cities input end-->

		<hr>

		<!-- Ranking result -->
		
			<div class="row mx-0">
				<h4 class="col text-center">Ranking z dnia <span>09.09.2019</span></h4>
			</div>
			<div class="row justify-content-center mx-0">
				<div class="col-8 mx-auto">
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
							  	<tr>
									<th scope="col">#</th>
									<th scope="col">Miasto</th>
									<th scope="col">Wynik</th>
									<th scope="col">Temperatura</th>
									<th scope="col">Wiatr</th>
									<th scope="col">Wilgotność</th>
							  	</tr>
							</thead>
							<tbody>
							  	<tr>
									<th scope="row">1</th>
									<td>Mark</td>
									<td>Otto</td>
									<td>@mdo</td>
									<td>@mdo</td>
									<td>@mdo</td>
							  	</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			

		<!-- Ranking result end-->
	</div>
	
</body>
</html>