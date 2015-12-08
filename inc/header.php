<?php
/**
* Common header for webpages
* Released under BSD license.
*/
$language = isset($_REQUEST['language']) ? $_REQUEST['language'] : 'fr';

$timerStart = microtime(true);

$thisPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php if (isset($page)) {echo $page->title;} ?></title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/style.css">

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body>
