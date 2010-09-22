<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Admin</title>
<?=render('admin/admin.js.php', null, array())?>
<style type="text/css">
	.approved {
		color: green;
	}
	.unapproved {
		color: black;
	}
	.disapproved {
		color: red;
	}
</style>
</head>
<body>
<div id="content">
<p id="nav">
</p>
<?= $content; ?>
</div>
<hr>
</body>
</html>