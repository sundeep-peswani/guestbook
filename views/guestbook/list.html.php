<dl class="entries">
<?php foreach($entries as $entry): ?>
<?php 
	$id = $entry->id;
	$author = $entry->author;
	$message = $entry->message;
	$date = date('l, j F, Y, g:ha', $entry->cunixtime);
	$approval = !is_admin() ? '' : sprintf(
		'%sapproved', 
		($entry->approved == 0 ? 'un' : ($entry->approved < 0 ? 'dis' : ''))
	);
	$rel = !is_admin() ? '' : sprintf(' rel="%s"', $approval);
?>
<dd class="message entry-<?=$id?> <?=$approval?>"<?=$rel?>><?=$message?></dd>
<dt class="meta entry-<?=$id?> <?=$approval?>"<?=$rel?>>
- by 
<span class="author"><?=$author?></span>,
<span class="date"><?=$date?></span>
</dt>
<?php if (is_admin()): ?>
<span class="actions"<?=$rel?>>
	<a href="admin/<?=$id?>/approve" class="approve">Approve</a>
	<a href="admin/<?=$id?>/disapprove" class="disapprove">Disapprove</a>
	<a href="admin/<?=$id?>/delete" class="delete">Delete</a>
</span>
<?php endif; ?>
<?php endforeach; ?>
</dl>