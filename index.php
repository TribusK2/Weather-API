<?php

	$errorsData = array("citiesCount" => '');

	// Get cities from form
	if(isset($_GET['submit'])){
		$city1 = $_GET['city1'];
		$city2 = $_GET['city2'];
		$city3 = $_GET['city3'];
		$city4 = $_GET['city4'];
		$citysCount = (bool)$_GET['city1'] + (bool)$_GET['city2'] + (bool)$_GET['city3'] + (bool)$_GET['city4'];
		if($citysCount<2){
			$errorsData['citiesCount'] = 'Liczba miast jest za mała, aby je porównać';
		}else{
			echo $citysCount;
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
				<h4 class="col text-center">Ranking miast</h4>
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