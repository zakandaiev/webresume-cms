<section class="section">
	<h2 class="section__title">General</h2>
	<form action="edit_general" method="post">
		<label>Site name</label>
		<input type="text" name="site_name" value="<?=$GLOBALS["site_name"]?>" placeholder="Site name" minlength="2" maxlength="256" required>
		<label>Site logo</label>
		<?=printFormFiles($GLOBALS["site_logo"])?>
		<input type="file" data-type="image" name="site_logo" accept="image/*">
		<label>Site background</label>
		<?=printFormFiles($GLOBALS["site_background"])?>
		<input type="file" data-type="image" name="site_background" accept="image/*">
		<label>Person position</label>
		<input type="text" name="person_position" value="<?=$GLOBALS["person_position"]?>" placeholder="Person position" maxlength="256">
		<label>Person location</label>
		<input type="text" name="person_location" value="<?=$GLOBALS["person_location"]?>" placeholder="Person location" maxlength="256">
		<label>Person email</label>
		<input type="email" name="person_email" value="<?=$GLOBALS["person_email"]?>" placeholder="Person email" minlength="6" maxlength="256" required>
		<label>Is Person hireable</label>
		<label class="switcher">
			<input type="checkbox" name="person_is_hireable" <?php if($GLOBALS["person_is_hireable"]): ?>checked<?php endif; ?>>
			<span class="slider round"></span>
		</label>
		<label>Person resume</label>
		<?=printFormFiles($GLOBALS["person_resume"])?>
		<input type="file" data-type="resume" name="person_resume">
		<label>Google Analytics ID</label>
		<input type="text" name="site_analytics_gtag" value="<?=$GLOBALS["site_analytics_gtag"]?>" placeholder="G-**********" minlength="2" maxlength="24">
		<label>Pagination limit</label>
		<input type="number" name="site_pagination_limit" value="<?=$GLOBALS["site_pagination_limit"]?>" min="1" max="1000" required>
		<button type="submit" class="btn">Save</button>
	</form>
</section>

<section class="section">
	<h2 class="section__title">Account</h2>
	<form action="edit_account" method="post">
		<label class="half-width left">Login</label>
		<label class="half-width right">Email</label>
		<input class="half-width left" type="text" name="login" value="<?=$user["login"]?>" placeholder="Login" minlength="4" maxlength="128" required>
		<input class="half-width right" type="email" name="email" value="<?=$user["email"]?>" placeholder="Email" minlength="6" maxlength="256" required>
		<button type="submit" class="btn">Save</button>
	</form>
</section>

<section class="section">
	<h2 class="section__title">Change password</h2>
	<form action="edit_password" method="post" data-reset>
		<label class="half-width left">Current password</label>
		<label class="half-width right">New password</label>
		<input class="half-width left" type="password" name="password_current" placeholder="Current password" minlength="4" maxlength="256" required>
		<input class="half-width right" type="password" name="password_new" placeholder="New password" minlength="4" maxlength="256" required>
		<button type="submit" class="btn">Change</button>
	</form>
</section>