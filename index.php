<?php

	$errorsData = array("citiesCountError" => '');
	
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
			$errorsData['citiesCountError'] = 'Liczba miast jest za mała, aby je porównać!';
		}else{
			$query = [];
			$apiKeys = [
				'1034c5a64ea7fd87277b36aaa4496a84',
				'183388a008ece52339de1b013456249b',
				'2ac700374182899bbe8d43dc4d52d3c6',
				'66ed475d4d5b55a23ee8f7f72568a304'
			];
			// $v=0;
			$v=1;
			foreach ($citiesNames as $city) {
				// if(!empty($apiKeys[$v])){		
				if(!empty($apiKeys[$v-1])){		
					// array_push($query, json_decode(file_get_contents('api.openweathermap.org/data/2.5/weather?q='.$city.'&APPID='.$apiKeys[$v])));
					array_push($query, json_decode(file_get_contents('data/example'.$v.'.json')));
					$v++;
				}else{
					header("Location: keyalert.php");
					die();
				}
			}

			// Save API response to files
			$v = 1;
			foreach ($query as $city) {
				file_put_contents('data/city'.$v.'.json', json_encode($city));
				$v++;
			}

			// // This is function to test aplication without request to API
			// $v=1;
			// foreach ($citiesNames as $city) {
			// 	array_push($query, json_decode(file_get_contents('data/example'.$v.'.json')));
			// 	$v++;
			// }

			// Creat cities objects
			$cities = [];
			for ($i=0; $i < count($citiesNames); $i++) {
				if(!empty($citiesNames[$i])){
					$city = new stdClass();
					$city->cityName = $citiesNames[$i];
					$city->parameters = $query[$i];
					array_push($cities, $city);
				}		
			}

			// Set fuction to descending sort by temp
			function sort_temp($a, $b) {
				$a = $a->parameters->main->temp;
				$b = $b->parameters->main->temp;
				if($a == $b){
					return 0;
				}
				return ($a > $b) ? -1 : 1;
			}

			// Set fuction to ascending sort by wind
			function sort_wind($a, $b) {
				$a = $a->parameters->wind->speed;
				$b = $b->parameters->wind->speed;
				if($a == $b){
					return 0;
				}
				return ($a < $b) ? -1 : 1;
			}

			// Set fuction to ascending sort by humidity
			function sort_humidity($a, $b) {
				$a = $a->parameters->main->humidity;
				$b = $b->parameters->main->humidity;
				if($a == $b){
					return 0;
				}
				return ($a < $b) ? -1 : 1;
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
				$city->temp_pos = array_search($city->cityName, array_column($tempArray, 'cityName'))+1;
				$city->wind_pos = array_search($city->cityName, array_column($windArray, 'cityName'))+1;
				$city->humidity_pos = array_search($city->cityName, array_column($humidityArray, 'cityName'))+1;
			}

			// Calculate the summary position of cities
			$temp_factor = 0.6;
			$wind_factor = 0.3;
			$umidity_factor = 0.1;
			foreach($cities as $city){
				$city->score = 	(100 - 10 * ($city->temp_pos - 1)) * $temp_factor + 
								(100 - 10 * ($city->wind_pos - 1)) * $wind_factor +
								(100 - 10 * ($city->humidity_pos - 1)) * $umidity_factor;
			}

			// Sort cities by result
			function sort_score($a, $b) {
				$a = $a->score;
				$b = $b->score;
				if($a == $b){
					return 0;
				}
				return ($a > $b) ? -1 : 1;
			}
				
			usort($cities, "sort_score");
			
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
			<?php echo '<div class="text-danger">'.$errorsData["citiesCountError"].'</div>' ?>
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
									<th scope="col" class="text-center">Wynik</th>
									<th scope="col" class="text-center">Temperatura</th>
									<th scope="col" class="text-center">Wiatr</th>
									<th scope="col" class="text-center">Wilgotność</th>
							  	</tr>
							</thead>
							<tbody>
							<?php
							if(!empty($cities)){
								$j = 0;
							 	foreach($cities as $city){
							?>
								<tr>
									<th scope="row"><?php $j += 1; echo $j?></th>
									<td><?php echo $city->cityName ?></td>
									<td class="text-center"><?php echo $city->score ?></td>
									<td class="text-center"><?php echo $city->parameters->main->temp - 273.15 ?> <sup>o</sup>C</td>
									<td class="text-center"><?php echo $city->parameters->wind->speed ?> m/s</td>
									<td class="text-center"><?php echo $city->parameters->main->humidity ?> %</td>
							  	</tr>
							<?php 
								}
							} 
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
		<!-- Ranking result end-->
	</div>
	
</body>
</html>