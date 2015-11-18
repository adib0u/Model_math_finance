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

		//$matrice[] = array("Mois","Reste","Interets", "Interet cumulés");
	    $delta = remboursementMens($montantInit, $tauxMensuel, $duree);
	    $sommeInteret = 0;
	    for ($mois = 0; $mois <= 12*$duree; $mois++){
	        # reste à payer en fonction du mois
	        $reste = ($montantInit * pow((1+$tauxMensuel),$mois)) - $delta * ((pow((1+$tauxMensuel),$mois) -1) / ($tauxMensuel));

	        # somme des interets:
	        $interet = $reste * $tauxMensuel;
	        $sommeInteret = $sommeInteret + $interet;

	        # affichage des informations mensuel
	        $matrice[] = array("mois" => $mois, "reste" => round($reste,3), "interet" => round($interet,3), "interetC" => round($sommeInteret,3));
	    }
	    return $matrice;
	}

	function afficherTableau($tab) {
		echo "<table>\n";
		foreach ($tab as $row) {
			echo "<tr>\n";
			foreach ($row as $data) {
				echo "\t<td>". $data. "</td>\n";
			}
			echo "</tr>\n";
		}
	}

	function graphiqueRemboursement($tab) {


		$MyData = new pData();

		$vData = array();
		foreach ($tab as $row) {
			$vData[] = $row["reste"];
		}

		/*Je présente ma série de données à utiliser pour le graphique et je détermine le titre de l'axe vertical avec setAxisName*/  
		 $MyData->addPoints($vData,"Probe 3");
		 $MyData->setSerieWeight("Probe 3",2);
		 $MyData->setAxisName(0,"Reste à payer");

 		$hData = array();
		foreach ($tab as $row) {
			$hData[] = $row["mois"];
		}

		/*J'indique les données horizontales du graphique. Il doit y avoir le même nombre que pour ma série de données précédentes (logique)*/
		 $MyData->addPoints($hData,"Labels");
		 $MyData->setSerieDescription("Labels","Mois");
		 $MyData->setAbscissa("Labels");
		 $MyData->setPalette("Probe 3",array("R"=>255,"G"=>0,"B"=>0));

		/* Je crée l'image qui contiendra mon graphique précédemment crée */
		 $myPicture = new pImage(900,330,$MyData);

		/* Je crée une bordure à mon image */
		 $myPicture->drawRectangle(0,0,899,329,array("R"=>0,"G"=>0,"B"=>0));

		/* J'indique le titre de mon graphique, son positionnement sur l'image et sa police */ 
		 $myPicture->setFontProperties(array("FontName"=>"./pChart2.1.4/fonts/Forgotte.ttf","FontSize"=>11));
		 $myPicture->drawText(200,25,"Reste à payer par mois",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

		/* Je choisi le fond de mon graphique */
		 $myPicture->setFontProperties(array("FontName"=>"./pChart2.1.4/fonts/pf_arma_five.ttf","FontSize"=>6));

		/* Je détermine la taille du graphique et son emplacement dans l'image */
		 $myPicture->setGraphArea(60,40,800,310);

		/* Paramètres pour dessiner le graphique à partir des deux abscisses */
		 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
		 $myPicture->drawScale($scaleSettings);

		/* J'insère sur le côté droit le nom de l'auteur et les droits */ 
		//$myPicture->setFontProperties(array("FontName"=>"./pChart2.1.4/fonts/Bedizen.ttf","FontSize"=>6));
		//$TextSettings = array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Angle"=>90,"FontSize"=>10);
		//$myPicture->drawText(860,300,"Création : FabPlug.com - Tous droits réservés",$TextSettings);

		/* Je dessine mon graphique en fonction des paramètres précédents */
		$myPicture->drawAreaChart();
		$myPicture->drawLineChart(); 

		/* Je rajoute des points rouge (plots) en affichant par dessus les données */
		//$myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80));

		/* J'indique le chemin où je souhaite que mon image soit créée */
		 $myPicture->Render("img/rest_a_payer.png");
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
		}
		td {
			border: 1px solid black;
		}

		th {
			border: 1px solid black;
		}
	</style>
</head>
<body>
	<h1>Calcul du détail d'un emprunt</h1>
	<form action="emprunt.php" methode="get">
		Durée: <input type="text" name="duree"><br>
		Montant: <input type="text" name="mont"><br>
		Taux d'intérêt annuel: <input type="text" name="tx"><br>
		<input type="submit" value="Calculer">
	</form>

	<?php
		if(isset($_GET['duree']) && isset($_GET['mont']) && isset($_GET['tx'])) {
			$duree = $_GET['duree'];
			$montant = $_GET['mont'];
			$taux = $_GET['tx'];

			afficherTableau(remboursementEmprunt($montant, $taux, $duree));
			graphiqueRemboursement(remboursementEmprunt($montant, $taux, $duree));
		}
	?>

</body>
</html>





