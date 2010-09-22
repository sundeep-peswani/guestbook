<?php $flash = flash(); ?>
<?php if (isset($flash['error'])): ?>
<p class="error"><?=$flash['error']?></p>
<?php endif; ?>
<form method="POST" action="<?=url_for('admin', 'login')?>">
	<p>
		<label for="username">Username</label>
		<input type="text" id="username" name="user[username]" value="<?=h($user['username'])?>" />
	</p>
	
	<p>
		<label for="password">Password</label>
		<input type="password" id="password" name="user[password]" value="<?=h($user['password'])?>" />
	</p>
	
	<input type="submit" value="Log In" /> or <a href="/">cancel</a>
</form>