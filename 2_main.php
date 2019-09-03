<?php
require_once("2_function.php");
$item = main();
?>
<html>
<head>
<meta http-equiv="content-type" charset="utf-8">
<script src="2_interface.js"></script>
<script>
window.onload = function(){
	var item = <?php echo json_encode($item);?>;
	KaitouSeisei(item);
}
</script>
</head>
<body style="font-size:20px">
	<form method="post" class="container">
		<div id="question" class="jumbotron"></div>
		<div id="choices"></div>
		<button id="answer" type="submit" class="btn btn-secondary">解答</button>
	</form>
</body>
<?php 
echo "<pre>"; print_r($item); echo "</pre>";
echo "<pre>"; print_r($_SESSION); echo "</pre>";