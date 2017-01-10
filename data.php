<?php

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

	//ühendan sessiooniga
	require("functions.php");

	//kui ei ole sisseloginud, suunan login lehele
	if (!isset($_SESSION["userId"])) {
		header("Location: login.php");
		exit();
	}

	//kas aadressireal on logout
	if (isset($_GET["logout"])) {

		session_destroy();

		header("Location: login.php");
		exit();

	}


	if ( isset($_POST["name"]) &&
		 isset($_POST["sort"]) &&
     isset($_POST["producer"]) &&
     isset($_POST["rating"]) &&
		 !empty($_POST["name"]) &&
     !empty($_POST["sort"]) &&
     !empty($_POST["producer"]) &&
		 !empty($_POST["rating"])
	) {


		$name = cleanInput($_POST["name"]);
    $sort = cleanInput($_POST["sort"]);
    $producer = cleanInput($_POST["producer"]);
    $rating = cleanInput($_POST["rating"]);


		saveEvent($name, $sort, $producer, $rating);
	}

	$people = getAllData();

?>
<h1>Data</h1>

<?php echo$_SESSION["userEmail"];?>

<?=$_SESSION["userEmail"];?>

<p>
	Tere tulemast <?=$_SESSION["userEmail"];?>!
	<a href="?logout=1">logi välja</a>
</p>

<h2>Salvesta uus jook</h2>
<form method="POST" >

	<label>Nimetus</label><br>
	<input name="name" type="text"><br>

  <label>Liik</label><br>
  <input name="sort" type="text"><br>

  <label>Tootja</label><br>
  <input name="producer" type="text"><br>

	<label>Hinnang vahemikus 1-5</label><br>
	<input name="rating" type="number" min="1" max="5">

	<br><br>

	<input type="submit" value="Salvesta">

</form>


<h2>Arhiiv</h2>

<?php

	$html = "<table>";

		$html .= "<tr>";
    	$html .= "<th>ID</th>";
      $html .= "<th>Nimetus</th>";
      $html .= "<th>Liik</th>";
			$html .= "<th>Tootja</th>";
			$html .= "<th>Hinnang</th>";
		$html .= "</tr>";

		//iga liikme kohta massiivis
		foreach ($people as $p) {

			$html .= "<tr>";
        $html .= "<td>".$p->id."</td>";
        $html .= "<td>".$p->name."</td>";
        $html .= "<td>".$p->sort."</td>";
				$html .= "<td>".$p->producer."</td>";
				$html .= "<td>".$p->rating."</td>";
			$html .= "</tr>";

		}

	$html .= "</table>";

	echo $html;
?>
