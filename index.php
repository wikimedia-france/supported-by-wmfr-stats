<?php
session_start();

include_once("lib/pageInterface.lib.php");
$page= new pageInterface("Fichiers soutenus par Wikimédia France");

# MySQL connection
$ts_mycnf = parse_ini_file("/data/project/ash-dev/replica.my.cnf");

try {
        $bdd = new PDO('mysql:host=commonswiki.labsdb;dbname=commonswiki_p', $ts_mycnf['user'], $ts_mycnf['password']);
} catch (Exception $e) {
        die('Error: ' . $e->getMessage());
}

# Dates
$current_year = date("Y");
$current_month = date("m");

if ($current_month > 6) {
	$year = $current_year;
	$start_month = "01";
	$end_month = "06";
} else {		
	$year = $current_year - 1;
	$start_month = "06";
	$end_month = "12";
}

$display_start_date = "$year-$start_month";
$display_end_date = "$year-$end_month";

# POST result handling
if(isset($_REQUEST['submit'])) { 

	$input_start_date = trim(htmlentities($_REQUEST['date-start']));
	if (preg_match("/^\d{4}-\d{2}$/", $input_start_date)) {
		$query_start_date = new DateTime($input_start_date . '-01 00:00:00' ); 
		$display_start_date = $input_start_date;
	} else {
		$page->alert("La date de début doit être de la forme AAAA-MM","danger");
	}

	$input_end_date = trim(htmlentities($_REQUEST['date-end']));
	if (preg_match("/\d{4}-\d{2}/", $input_end_date)) {
		$query_end_date = new DateTime($input_end_date . '-01 23:59:59' ); 
		$display_end_date = $input_end_date;
	} else {
		$page->alert("La date de fin doit être de la forme AAAA-MM","danger");
	}
}

include_once("inc/header.php");
?>

<div class="container theme-showcase" role="main">
<div class="jumbotron">
	<h1>Fichiers soutenus par Wikimédia France</h1>
	<p>Cet outil donne un certain nombre de statistiques à propos des fichiers publiés sur <a href="https://commons.wikimedia.org">Wikimedia Commons</a>
	avec le soutien de Wikimédia France sur une période de temps donnée, en se basant sur la catégorie
	<a href="https://commons.wikimedia.org/wiki/Category:Media_supported_by_Wikimedia_France">Category:Media supported by Wikimedia France</a>.</p>

	<div id="helpToggle" style="float: right;">[Cliquer pour afficher/cacher l'aide]</div>
</div>

<div id="helpSection" <?php if ( isset($_FILES["csv"])) { echo 'style="display: none;"';}?> >
	<h3><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Aide</h3>
	<!-- TODO : complete documentation -->
	<ol>
		<li>TODO</li>
	</ol>
</div>

<!-- The query form -->
<h3><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Date</h3>

<form role="form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="form-inline">

  <div class="form-group">
    <label for="date-start">Mois de début</label>
    <input type="text" class="form-control" id="date-start" name="date-start" value="<?php echo "$display_start_date"; ?>">
  </div>
  <div class="form-group">
    <label for="date-end">Mois de fin</label>
    <input type="text" class="form-control" id="date-end" name="date-end" value="<?php echo "$display_end_date"; ?>">
  </div>
  <input type="submit" name="submit" value="Afficher"  class="btn btn-default" >
</form>

<!-- If dates have been set, treat the request. -->
<?php


if (isset($query_start_date) && isset($query_end_date)) {
	$req = $bdd->prepare("SELECT  img_user_text AS uploader, COUNT(image.img_name) AS image_count
						  FROM image, page, categorylinks
						  WHERE page.page_id=categorylinks.cl_from AND image.img_name = page.page_title
						  AND .categorylinks.cl_to = \"Media_supported_by_Wikimedia_France\"
						  AND img_timestamp BETWEEN :query_start_date AND :query_end_date GROUP BY uploader ORDER BY image_count DESC;");

	$req->execute(array(
        'query_start_date' => $query_start_date->format('YmdHis'),
        'query_end_date'=> $query_end_date->format('YmtHis')
	));

	$data = $req->fetchAll();
	echo 'data: ';
	print_r($data) ;

	$all_uploaders = array();
	$total_files = 0;


	echo "<table class='table table-striped'>";
	echo "<th><td>Contributeur</td><td>Photos</td></th>";
	foreach ($data as $key => $value) {
		$all_uploaders[] = $value['uploader'];
		$total_files+= $value['image_count'];
		echo "<tr><td><a href='https://commons.wikimedia.org/wiki/User:" . $value['uploader'] . "'>". $value['uploader'] . "</a></td>
		<td>" . $value['image_count'] . "</td></tr>";
	}
	echo "</table>";

	
	echo "<p>Nombre de fichiers : " . $total_files . ".</p>";
	echo "<p>Estimation du temps passé : " . $total_files * .2 . " h.</p>";

	sort($all_uploaders);
	echo "<h4>Liste brute des contributeurs</h4>";
	echo "<textarea>";
	foreach ($all_uploaders as $key => $value) {
		echo "$value\n";
	}
	echo "</textarea>";//*/
}

?>
			
</div> <!-- End of container div -->

<!--Specific Javascript for this page -->
<script>
$(document).ready(function(){
	$("#helpToggle").click(function(){
		$("#helpSection").toggle("slow");
	});
});
</script>

<?php include_once("inc/footer.php"); ?>