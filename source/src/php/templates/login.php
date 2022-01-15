<section class="section">
	<h2 class="section__title">Login</h2>
	<form action="/login" method="post" data-redirect="/profile/account">
		<input type="text" name="login" placeholder="Login" minlength="2" maxlength="128" required>
		<input type="password" name="password" placeholder="Password" minlength="4" maxlength="256" required>
		<button class="btn primary" type="submit">Login</button>
	</form>
</section>