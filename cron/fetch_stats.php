<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * Date: 11.05.2014
 * Time: 11:05
 */

include_once __DIR__ . '/../vendor/autoload.php';

$profileUrl = 'https://github.com/ovr.json';

use Zend\Debug\Debug;

Debug::setSapi(PHP_SAPI);

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<?php
	Debug::dump(json_decode(file_get_contents($profileUrl)));
?>
</body>
</html>
