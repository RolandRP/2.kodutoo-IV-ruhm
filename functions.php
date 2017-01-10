<?php
	require("../config.php");

	// see fail peab olema siis seotud kõigiga kus
	// tahame sessiooni kasutada
	// saab kasutada nüüd $_SESSION muutujat
	session_start();

  // DATABASE MUUTUJA LIIGUTATUD SAMUTI CONFIG FAILI, TEENUSEPAKKUJA ANNAB SAMA NIME NII DB'LE KUI KASUTAJALE

	// functions.php

	function signup($email, $password) {

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

		$stmt = $mysqli->prepare("INSERT INTO webprog (email, password) VALUE (?, ?)");
		echo $mysqli->error;

		$stmt->bind_param("ss", $email, $password);

		if ( $stmt->execute() ) {
			echo "õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}

	}

	function login($email, $password) {

		$notice = "";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

		$stmt = $mysqli->prepare("
			SELECT id, email, password, created
			FROM webprog
			WHERE email = ?
		");

		echo $mysqli->error;

		//asendan küsimärgi
		$stmt->bind_param("s", $email);

		//rea kohta tulba väärtus
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);

		$stmt->execute();

		//ainult SELECT'i puhul
		if($stmt->fetch()) {
			// oli olemas, rida käes
			//kasutaja sisestas sisselogimiseks
			$hash = hash("sha512", $password);

			if ($hash == $passwordFromDb) {
				echo "Kasutaja $id logis sisse";

				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				//echo "ERROR";

				header("Location: data.php");
				exit();

			} else {
				$notice = "parool vale";
			}


		} else {

			//ei olnud ühtegi rida
			$notice = "Sellise emailiga ".$email." kasutajat ei ole olemas";
		}


		$stmt->close();
		$mysqli->close();

		return $notice;





	}



	function saveEvent($name, $sort, $producer, $rating) {

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

		$stmt = $mysqli->prepare("INSERT INTO beers (name, sort, producer, rating) VALUE (?, ?, ?, ?)");
		echo $mysqli->error;

		$stmt->bind_param("sssi", $name, $sort, $producer, $rating);

		if ( $stmt->execute() ) {
			echo "õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}

	}

	function getAllData() {

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("
			SELECT id, name, sort, producer, rating
			FROM beers
		");
		$stmt->bind_result($id, $name, $sort, $producer, $rating);
		$stmt->execute();

		$results = array();

		// tsükli sisu tehakse nii mitu korda, mitu rida
		// SQL lausega tuleb
		while ($stmt->fetch()) {

			$human = new StdClass();
			$human->id = $id;
			$human->name = $name;
      $human->sort = $sort;
      $human->producer = $producer;
      $human->rating = $rating;


			//echo $color."<br>";
			array_push($results, $human);

		}

		return $results;

	}


	function cleanInput($input) {

		// input = "  romil  ";
		$input = trim($input);
		// input = "romil";

		// võtab välja \
		$input = stripslashes($input);

		// html asendab, nt "<" saab "&lt;"
		$input = htmlspecialchars($input);

		return $input;

	}





	/*function sum($x, $y) {

		return $x + $y;

	}

	echo sum(12312312,12312355553);
	echo "<br>";


	function hello($firstname, $lastname) {
		return
		"Tere tulemast "
		.$firstname
		." "
		.$lastname
		."!";
	}

	echo hello("romil", "robtsenkov");
	*/
?>
