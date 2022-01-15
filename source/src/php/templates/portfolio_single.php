<section class="section">
	<h2 class="section__title"><?php if($user["is_logged"]): ?><a href="/profile/portfolio?edit=<?=$portfolio['id']?>"><?=getSvg("img/icons/edit.svg")?></a> <?php endif; ?><?=$portfolio["title"]?></h2>
	<div class="gallery">
		<figure class="gallery__item" data-fancybox="gallery" data-src="/<?=$portfolio['main_image']?>" data-preload="false"><img src="/<?=$portfolio['main_image']?>" alt="<?=$portfolio['title']?>"></figure>
		<?php
			$images = json_decode($portfolio["images"], true);
		?>
		<?php if(!empty($images)): foreach($images as $image): ?>
			<figure class="gallery__item" data-fancybox="gallery" data-src="/<?=$image?>" data-preload="false"><img src="/<?=getSmallUploadPath($image)?>" alt="<?=$portfolio['title']?>"></figure>
		<?php endforeach; endif; ?>
	</div>
</section>

<?php if(!empty($portfolio["description"])): ?>
	<section class="section">
		<h2 class="section__title">Description</h2>
		<div class="section__content"><?=$portfolio["description"]?></div>
	</section>
<?php endif; ?>

<?php
	$details = json_decode($portfolio["details"], true);
?>
<?php if(!empty($details)): ?>
	<section class="section">
		<h2 class="section__title">Details</h2>
		<table>
			<tbody>
				<?php if(!empty($details["technologies"])): ?>
					<tr>
						<th>Technologies</th>
						<td><?=$details["technologies"]?></td>
					</tr>
				<?php endif; ?>
				<?php if(!empty($details["features"])): ?>
					<tr>
						<th>Features</th>
						<td><?=$details["features"]?></td>
					</tr>
				<?php endif; ?>
				<?php if(!empty($details["date"])): ?>
					<tr>
						<th>Date</th>
						<td><?=$details["date"]?></td>
					</tr>
				<?php endif; ?>
				<?php if(!empty($details["link"])): ?>
					<tr>
						<th>Link</th>
						<td><a href="<?=$details['link']?>" target="_blank"><?=$details["link"]?></a></td>
					</tr>
				<?php endif; ?>
				<?php if(!empty($details["github"])): ?>
					<tr>
						<th>Github</th>
						<td><a href="<?=$details['github']?>" target="_blank"><?=$details["github"]?></a></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</section>
<?php endif; ?>

<?php
	$code_query = $pdo->prepare("SELECT * FROM {$prefix}_code WHERE portfolio_id=:portfolio_id AND enabled is true ORDER BY `order` ASC;");
	$code_query->bindParam(":portfolio_id", $portfolio["id"]);
	$code_query->execute();
	$code = $code_query->fetchAll(PDO::FETCH_ASSOC);
?>
<?php if(!empty($code)): ?>
	<section class="section line-numbers">
		<h2 class="section__title">Code snippets</h2>
		<?php if($user["is_logged"]): ?><div class="sortable" data-name="sort_code"><?php endif; ?>
			<?php foreach($code as $item): ?>
				<div class="code" <?php if($user["is_logged"]): ?>data-code-id="<?=$item['id']?>"<?php endif; ?>>
					<h4 class="code__title">
						<span class="label <?=extensionLabelClass($item["extension"])?>"><?=$item["extension"]?></span> <?=$item["title"]?>
						<?php if($user["is_logged"]): ?>
							<button class="sortable__handle" title="Sort"><?=getSvg("img/icons/bars.svg")?></button>
						<?php endif; ?>
					</h4>
					<div class="code__body">
						<pre><code class="language-<?=$item['extension']?>"><?=$item["code"]?></code></pre>
					</div>
				</div>
			<?php endforeach; ?>
		<?php if($user["is_logged"]): ?></div><?php endif; ?>
	</section>
<?php endif; ?>

<?php
	$portfolio_extra_query = $pdo->prepare("
		SELECT
			JSON_OBJECT(
				'title', prev_title,
				'url', prev_url,
				'main_image', prev_image
			) as portfolio_extra_prev,
			JSON_OBJECT(
				'title', next_title,
				'url', next_url,
				'main_image', next_image
			) as portfolio_extra_next
		FROM (
			SELECT id,
				LAG(title) OVER (ORDER BY cdate) as prev_title,
				LAG(url) OVER (ORDER BY cdate) as prev_url,
				LAG(main_image) OVER (ORDER BY cdate) as prev_image,
				LEAD(title) OVER (ORDER BY cdate) as next_title,
				LEAD(url) OVER (ORDER BY cdate) as next_url,
				LEAD(main_image) OVER (ORDER BY cdate) as next_image
			FROM {$prefix}_portfolio
			WHERE enabled is true
		) t
		WHERE id=:portfolio_id;
	");
	$portfolio_extra_query->bindParam(":portfolio_id", $portfolio["id"]);
	$portfolio_extra_query->execute();
	$portfolio_extra = $portfolio_extra_query->fetch(PDO::FETCH_ASSOC);
	$portfolio_extra_prev = json_decode($portfolio_extra["portfolio_extra_prev"], true);
	$portfolio_extra_next = json_decode($portfolio_extra["portfolio_extra_next"], true);
?>

<?php if(!empty($portfolio_extra_prev["url"]) || !empty($portfolio_extra_next["url"])): ?>
	<section class="section">
		<nav class="extra-nav">
			<?php if(!empty($portfolio_extra_prev["url"])): ?>
				<a class="extra-nav__item prev" href="/portfolio/<?=$portfolio_extra_prev["url"]?>">
					<span class="extra-nav__subtitle">Previous case</span>
					<span class="extra-nav__title"><?=$portfolio_extra_prev["title"]?></span>
					<?php if(!empty($portfolio_extra_prev["main_image"])): ?>
						<img class="extra-nav__img" src="/<?=getSmallUploadPath($portfolio_extra_prev['main_image'])?>" alt="<?=$portfolio_extra_prev['title']?>">
					<?php endif; ?>
				</a>
			<?php endif; ?>
			<?php if (!empty($portfolio_extra_next["url"])): ?>
				<a class="extra-nav__item next" href="/portfolio/<?=$portfolio_extra_next["url"]?>">
					<span class="extra-nav__subtitle">Next case</span>
					<span class="extra-nav__title"><?=$portfolio_extra_next["title"]?></span>
					<?php if(!empty($portfolio_extra_next["main_image"])): ?>
						<img class="extra-nav__img" src="/<?=getSmallUploadPath($portfolio_extra_next['main_image'])?>" alt="<?=$portfolio_extra_next['title']?>">
					<?php endif; ?>
				</a>
			<?php endif; ?>
		</nav>
	</section>
<?php endif; ?>