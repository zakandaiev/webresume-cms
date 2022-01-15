	</main>

	<script>const csrf_token = "<?=$csrf_token?>";</script>
	<script src="/js/main.js"></script>
	<?php if($user["is_logged"]): ?>
		<script src="/js/profile.js"></script>
	<?php endif; ?>
	<?php if(str_starts_with($current_route, "portfolio")): ?>
		<script defer src="/js/portfolio_page.js"></script>
	<?php endif; ?>
	<?php if(!empty($GLOBALS["site_analytics_gtag"])): ?>
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?=$GLOBALS["site_analytics_gtag"]?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '<?=$GLOBALS["site_analytics_gtag"]?>');
		</script>
	<?php endif; ?>
</body>

</html>