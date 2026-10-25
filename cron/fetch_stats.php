<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * Date: 11.05.2014
 * Time: 11:05
 */

include_once __DIR__ . '/../vendor/autoload.php';

$profileUrl = 'https://github.com/ovr.json';

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<?php
	// Zend\Debug has no Laminas successor, so dump through var_dump().
	echo '<pre>';
	var_dump(json_decode(file_get_contents($profileUrl)));
	echo '</pre>';
?>
</body>
</html>
