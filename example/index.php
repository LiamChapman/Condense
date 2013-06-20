<?php require '../condense.php' ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Condense Example</title>
		<!-- Due to the example, having to put in some custom settings -->
		<script src="<?= new Condense('/example/scripts/', 'app', '/example/cache/app') ?>"></script>
		<!-- CSS example with custom settings -->
		<link rel="stylesheet" href="<?= new Condense('/example/styles/', 'main', '/example/cache/global', 'css', '//@require'); ?>" type="text/css" />
	</head>
	<body>
		<h1>Condense Example</h1>
		<div id="target"></div>
	</body>
</html>
