<html>
	<head>
		<title>Pizzerie</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script>
			function controllo_campi()
			{
				var valore=document.getElementById("lim").value;
				var esito=false;
				var verifica=/^\d{1,2}$/
				if(document.getElementById("lim").value!=""&&document.getElementById("cit").value!=""&&document.getElementById("que").value!="")
					if(valore.match(verifica)&&parseInt(valore)<51)
						esito=true;
				if(!esito)
					alert("E' presente almeno un campo vuoto o compilato in modo errato!")
				return esito;
			}
		</script>
	</head>
	<body>
		<?php
			if(isset($_POST["lim"]))
			{
				$lim=$_POST["lim"];
			}
			else
			{
				$lim=50;
			}
			if(isset($_POST["cit"]))
			{
				$cit=$_POST["cit"];
			}
			else
			{
				$cit="Bergamo";
			}
			if(isset($_POST["que"]))
			{
				$que=$_POST["que"];
			}
			else
			{
				$que="pizzeria";
			}
			
			# Questo script chiama un'API e la inserisce in una tabella
			# Indirizzo dell'API da richiedere
			$indirizzo_pagina="https://api.foursquare.com/v2/venues/search?v=20161016&query=$que&limit=$lim&intent=checkin&client_id=QKGI0KS3UVFYS4XASHYAEOACR2EBXJ0YNZ1FKWQWWSTG4XO3&client_secret=2NSYG5SJVTDVLLLCNUN4ZV5FR23NE0LJOY2YTPKEAXVVRZBK&near=$cit";
			# Codice di utilizzo di cURL
			# Chiama l'API e la immagazzina in $json
			$ch = curl_init() or die(curl_error());
			curl_setopt($ch, CURLOPT_URL,$indirizzo_pagina);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$json=curl_exec($ch) or die(curl_error());
			
			# Decodifico la stringa json e la salvo nella variabile $data
			$data = json_decode($json);
			
			# Controllo valori
			if (count($data->response->venues)<$lim);
				$lim=count($data->response->venues);
			
			# Stampa della tabella delle pizzerie.
			if($lim!=0)
			{
				echo "<table>";
					echo "<tr>";
						echo "<th>Nome</th>";
						echo "<th>Latitudine</th>";
						echo "<th>Longitudine</th>";
					echo "</tr>";
					for($i=0; $i<$lim; $i++)
					{
						echo "<tr>";
							echo "<td>";
							echo $data->response->venues[$i]->name;
							echo "</td>";
							echo "<td>";
							echo $data->response->venues[$i]->location->lat;
							echo "</td>";
							echo "<td>";
							echo $data->response->venues[$i]->location->lng;
							echo "</td>";
						echo "</tr>";
					}
				echo "</table>";
			}
			else
				echo "ERRORE, NON SONO STATE TROVATE CORRISPONDENZE CON I CAMPI SELEZIONATI!";
			# Stampa di eventuali errori
			echo curl_error($ch);
			curl_close($ch);

			if($lim==0)
				$lim=50;
			
			for($e=0; $e<strlen($cit); $e++)
				if($cit[$e]=='+')
					$cit[$e]=' ';
			for($e=0; $e<strlen($que); $e++)
				if($cit[$e]=='+')
					$cit[$e]=' ';
		?>
	</body>
</html>
