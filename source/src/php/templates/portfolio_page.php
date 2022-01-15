<?php
	$portfolio_query = $pdo->query("SELECT * FROM {$prefix}_portfolio WHERE enabled is true ORDER BY cdate DESC LIMIT {$GLOBALS["site_pagination_limit"]};");
	$portfolio = $portfolio_query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if(!empty($portfolio)): ?>
	<section id="portfolio" class="section">
		<h2 class="section__title">Portfolio</h2>
		<?php if(!empty($portfolio)): ?>
			<div class="portfolio">
				<?php foreach($portfolio as $item): ?>
					<?php
						$details = json_decode($item["details"], true);
					?>
					<div class="portfolio__item">
						<a class="portfolio__img" href="/portfolio/<?=$item['url']?>"><img src="/<?=getSmallUploadPath($item['main_image'])?>" alt="<?=$item['title']?>"></a>
						<h3 class="portfolio__title"><?=$item['title']?></h3>
						<p class="portfolio__teaser"><?php if(!empty($details["date"])): ?><?=$details["date"]?><?php else: ?><?=formatDateString($item["cdate"], "d.m.Y")?><?php endif; ?></p>
					</div>
				<?php endforeach; ?>
			</div>
			<footer class="section__footer">
				<button id="portfolio-load-more" class="btn" type="button">Load more</button>
			</footer>
		<?php endif; ?>
	</section>
<?php endif; ?>