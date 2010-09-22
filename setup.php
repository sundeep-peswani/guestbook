<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Guestbook Setup</title>
</head>
<body>
<h1>Guestbook Setup</h1>
<?php

function exceptions_error_handler($severity, $message, $filename, $lineno)
{
  if (error_reporting() == 0) return;
  if (error_reporting() & $severity)
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
}
set_error_handler('exceptions_error_handler');

$localhost = preg_match('/^localhost(\:\d+)?/', $_SERVER['HTTP_HOST']);
$env = $localhost ? ENV_DEVELOPMENT : ENV_PRODUCTION;

require_once('conf.php');
$dsn = sprintf('mysql:host=%s;port=%u;dbname=%s', $host, $port, $dbname);
try
{
	$db = new PDO($dsn, $username, $password);
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$db->exec(file_get_contents('db/schema.sql'));
	$db->exec(file_get_contents('db/setup.sql'));
	if ($env == ENV_DEVELOPMENT) {
		$db->exec(file_get_contents('db/temp-data.sql'));
	}	
?>
<p>Setup succesful. <a href="index.php">Let's go !</a></p>
<? } catch(Exception $e) { ?>
<p><strong>Setup failed:</strong> <code><?=$e?></code>
<? } ?>
</body>
</html>