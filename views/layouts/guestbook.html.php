<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Guestbook</title>
</head>
<body>
<h1>Guestbook</h1>
<ul id="nav">
<li><a href="<?=$prev === null ? '#' : url_for($prev)?>">Previous</a></li>
<li><a href="<?=url_for('new')?>">Create a new post</a></li>
<li><a href="<?=$next === null ? '#' : url_for($next)?>">Next</a></li>
</ul>
<div id="content">
<?= $content; ?>
</div>
<hr>
<p>
<a href="<?=url_for('new')?>">Create a new post</a>
</p>
</body>
</html>