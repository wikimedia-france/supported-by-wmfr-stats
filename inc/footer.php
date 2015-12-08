<?php 
$timerStop = microtime(true);
$timeSpent= $timerStop - $timerStart;
?>

<footer class="footer">
	<span class="navbar-logo pull-right"><a href="https://github.com/wikimedia-france/supported-by-wmfr-stats"><img title="Developed by Sylvain Boissel" src="img/github-light.png" /></a></span>
	<div class="container">
		<p class="text-muted">Script runtime: <?php echo round($timeSpent,2); ?> seconds.</p>
	</div>      		
</footer>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>