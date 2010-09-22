<dl class="entries">
<?php foreach($entries as $entry): ?>
<dd class="message"><?=$entry->message?></dd>
<dt class="meta">
- by 
<span class="author"><?=$entry->author?></span>,
<span class="date"><?=date('l, j F, Y, g:ha', $entry->cunixtime)?></span>
</dt>
<?php endforeach; ?>
</dl>