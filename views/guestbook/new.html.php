<?php $flash = flash(); ?>
<?php if (isset($flash['error'])): ?>
<p class="error"><?=$flash['error']?></p>
<?php endif; ?>
<form method="POST" action="<?=url_for('new')?>">
	<p>
		<label for="author">Name</label>
		<input type="text" id="author" name="entry[author]" value="<?=h($entry['author'])?>" />
	</p>
	
	<p>
		<label for="message">Message</label>
		<textarea id="message" name="entry[message]"><?=h($entry['message'])?></textarea>
	</p>
	
	<input type="submit" value="Post" /> or <a href="/">cancel</a>
</form>