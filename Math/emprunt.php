<?php
	include("pChart2.1.4/class/pData.class.php");
	include("pChart2.1.4/class/pDraw.class.php");
	include("pChart2.1.4/class/pImage.class.php");
	include("pChart2.1.4/class/pPie.class.php");
	include("pChart2.1.4/class/pIndicator.class.php");

   	# Calcul du montant à payer (delta) par mois d'un emprunt 'montantInit', 
	# de durée 'duree' (en année) avec un taux mensuel = 'tx' (compris entre 0 et 1).
	function remboursementMens($montantInit, $tx, $duree) {
		return $tx * $montantInit * (pow((1+$tx),(12*$duree)) / (pow((1+$tx),(12*$duree)) - 1));
	}

	# affiche les informations mensuel relatives au remboursement de 'montantInit'
	# avec un taux mensuel 'tauxMensuel'
	# sur une durée de 'duree' ans.
	# ainsi que le somme des interets cummulés de l'emprunt
	function remboursementEmprunt($montantInit, $tauxAnnuel, $duree) {
		$matrice = array();

		$tauxMensuel = $tauxAnnuel/12;

		//$matrice[] = array("Mois","Reste","Interets", "Interet cumulés", "aPayer/mois");
	    $delta = remboursementMens($montantInit, $tauxMensuel, $duree);
	    $sommeInteret = 0;
	    for ($mois = 0; $mois <= 12*$duree; $mois++){
	        # reste à payer en fonction du mois
	        $reste = ($montantInit * pow((1+$tauxMensuel),$mois)) - $delta * ((pow((1+$tauxMensuel),$mois) -1) / ($tauxMensuel));

	        # somme des interets:
	        $interet = $reste * $tauxMensuel;
	        $sommeInteret = $sommeInteret + $interet;

	        # affichage des informations mensuel
	        if ($mois > 0) {
	        	$matrice[] = array("mois" => $mois, "reste" => $reste, "interet" => $interet, "interetC" => $sommeInteret, "aPayer" => $matrice[$mois-1]["reste"] + $interet - $reste);
	        } else {
	        	$matrice[] = array("mois" => $mois, "reste" => $reste, "interet" => $interet, "interetC" => $sommeInteret, "aPayer" => 0);
	        }
	    }
	    return $matrice;
	}

	# permet d'afficher un tableau html du tableau passé en paramettre avec son en-tete.
	function afficherTableau($tab, $head) {
		echo "<table>\n";
		
		echo "<tr>";
		foreach ($head as $row) {
			echo "<th>$row</th>";
		}
		echo "</tr>";

		foreach ($tab as $row) {
			echo "<tr>\n";
			foreach ($row as $data) {
				echo "\t<td>". round($data,3). "</td>\n";
			}
			echo "</tr>\n";
		}

		echo "</table>";
	}

	# Permet de créer les graphes en donnant en parametre
	# un taleau de données verticals
	# un taleau de données horizontals
	# le titre du graphique
	# le label vertical
	# le label horizontal
	function createGraphe($vData, $hData, $titre, $vLabel, $hLabel) {

		$MyData = new pData();

		/*Je présente ma série de données à utiliser pour le graphique et je détermine le titre de l'axe vertical avec setAxisName*/  
		$MyData->addPoints($vData,"vertical");
		$MyData->setSerieWeight("vertical",2);
		$MyData->setAxisName(0,$vLabel);

		/*J'indique les données horizontales du graphique. Il doit y avoir le même nombre que pour ma série de données précédentes (logique)*/
		$MyData->addPoints($hData,"horizontal");
		$MyData->setSerieDescription("horizontal", $hLabel);
		$MyData->setAbscissa("horizontal");
		$MyData->setPalette("vertical",array("R"=>255,"G"=>0,"B"=>0));


		/* Je crée l'image qui contiendra mon graphique précédemment crée */
		$myPicture = new pImage(900,400,$MyData);

		/* Je crée une bordure à mon image */
		$myPicture->drawRectangle(0,0,899,399,array("R"=>0,"G"=>0,"B"=>0));

		/* J'indique le titre de mon graphique, son positionnement sur l'image et sa police */ 
		$myPicture->setFontProperties(array("FontName"=>"./pChart2.1.4/fonts/Forgotte.ttf","FontSize"=>11));
		$myPicture->drawText(200,25,$titre,array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

		/* Je choisi la font de mon graphique */
		$myPicture->setFontProperties(array("FontName"=>"./pChart2.1.4/fonts/pf_arma_five.ttf","FontSize"=>6));

		/* Je détermine la taille du graphique et son emplacement dans l'image */
		$myPicture->setGraphArea(60,40,800,380);

		/* Paramètres pour dessiner le graphique à partir des deux abscisses */
		$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>FALSE,"CycleBackground"=>TRUE, "LabelSkip"=>4);
		$myPicture->drawScale($scaleSettings);

		/* Je dessine mon graphique en fonction des paramètres précédents */
		$myPicture->drawAreaChart();
		$myPicture->drawLineChart(); 

		/* J'indique le chemin où je souhaite que mon image soit créée */

		$myPicture->Render("img/". $titre .".png");
	}

	# Permet de retourner dans un tableau les informations finales apres calculs du détail du remboursemet (sous la forme d'une chaine de caractere)
	# "moyenneAPayer": la moyenne de ce qu'il faut payer par mois
	# "totalPaye": le détail du total payé (montant initial + interet cumulés)
	function getInfoFinal() {

	}
?>
   

<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Remoursement d'un emprunt</title>
	<style type="text/css">
		table {
			border-collapse:collapse;
			border: 1px solid black;
			display: inline-block;
			float: right;
		}
		td {
			border: 1px solid black;
		}

		th {
			border: 1px solid black;
		}

		img {

		}
	</style>
</head>
<body>
	<h1>Calcul du détail d'un emprunt</h1>
	<form action="emprunt.php" methode="get">
		Durée du remboursement: <input type="text" name="duree"><br>
		Montant à emprunter: <input type="text" name="mont"><br>
		Taux d'intérêt annuel en pourcentage: <input type="text" name="tx"><br>
		<input type="submit" value="Calculer">
	</form>

	<?php
		if(isset($_GET['duree']) && isset($_GET['mont']) && isset($_GET['tx'])) {
			$duree = $_GET['duree'];
			$montant = $_GET['mont'];
			$taux = $_GET['tx'];


		// affiche le tableau des données
			$head = array("Mois","Reste","Interets", "Interet cumulés", "aPayer"); //entete du tableau
			$rembMens = remboursementEmprunt($montant, $taux, $duree);

			afficherTableau($rembMens, $head); // affichage du tableau

		// créé le graphe du remboursement de l'emprunt
			$restAPayer = $rembMens; // tableau des données sans les titres des colonnes
			$restAPayerV = array(); // tableau des données verticles
			foreach ($restAPayer as $row) {
				$restAPayerV[] = $row["reste"];
			}

			$restAPayerH = array(); // tableau des données horizontals
			foreach ($restAPayer as $row) {
				$restAPayerH[] = $row["mois"];
			}
			createGraphe($restAPayerV, $restAPayerH, "Reste a payer par mois", "Reste (€)", "Mois");
			echo '<img src="img/Reste a payer par mois.png">';

		// crée le graphe de l'évolution des interets mensuels
			$interetMens = $rembMens;
			$interetMensV = array();
			foreach ($interetMens as $row) {
				$interetMensV[] = $row["interet"];
			}
			$interetMensH = array();
			foreach ($interetMens as $row) {
				$interetMensH[] = $row["mois"];
			}
			createGraphe($interetMensV, $interetMensH, "Interet a payer par mois", "Interet (€)", "Mois");
			echo '<img src="img/Interet a payer par mois.png">';

		// crée le graphe des interets cummulés
			$interetMensC = $rembMens;
			$interetMensCV = array();
			foreach ($interetMensC as $row) {
				$interetMensCV[] = $row["interetC"];
			}
			$interetMensCH = array();
			foreach ($interetMensC as $row) {
				$interetMensCH[] = $row["mois"];
			}
			createGraphe($interetMensCV, $interetMensCH, "Interet cumules par mois", "Interet cumulé (€)", "Mois");
			echo '<img src="img/Interet cumules par mois.png">';

		// affichages des info finales utils à l'utilisateur
			# Moyenne des àPayer par mois:
			$sumMoyAPayer = 0;
			foreach ($rembMens as $row) {
				$sumMoyAPayer += $row["aPayer"];
			}
			echo "Moyenne de ce qu'il faut payer par mois: ". round($sumMoyAPayer/($duree*12),3) ."<br>";

			# détail du total payé apres la durée (montant init + interets cumulés)
			echo "Detail du montant payé apres ". $duree ."ans: ".$montant ." + ". round($rembMens[$duree*12]["interetC"],3)." = <b>".round($montant+$rembMens[$duree*12]["interetC"],3)."</b>";
		}
	?>

</body>
</html>





