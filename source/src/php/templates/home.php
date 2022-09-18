<?php
	$skills = json_decode($GLOBALS["section_skills"] ?? '[]', true);

	$portfolio_query = $pdo->query("SELECT * FROM {$prefix}_portfolio WHERE enabled is true ORDER BY cdate DESC LIMIT {$GLOBALS["site_pagination_limit"]};");
	$portfolio = $portfolio_query->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="about" class="section">
	<h2 class="section__title">About me</h2>
	<?php if(!empty($GLOBALS["section_about"]) || $user["is_logged"]): ?>
		<div class="section__content" <?php if($user["is_logged"]): ?>contenteditable data-name="section_about"<?php endif; ?>>
			<?php if(!empty($GLOBALS["section_about"])): ?>
				<?=$GLOBALS["section_about"]?>
			<?php elseif($user["is_logged"]): ?>
				<p>Click here to edit</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
		$section_timeline = json_decode($GLOBALS["section_timeline"] ?? '[]', true);
	?>
	<?php if(!empty($section_timeline)): ?>
		<div class="timeline <?php if($user["is_logged"]): ?>sortable<?php endif; ?>" <?php if($user["is_logged"]): ?>data-name="section_timeline"<?php endif; ?>>
			<?php foreach($section_timeline as $key => $item): ?>
				<div class="timeline__item">
					<div class="timeline__left">
						<h4 class="timeline__title" <?php if($user["is_logged"]): ?>contenteditable data-name="section_timeline"<?php endif; ?>><?=$item["left_title"]?></h4>
						<p class="timeline__subtitle" <?php if($user["is_logged"]): ?>contenteditable data-name="section_timeline"<?php endif; ?>><?=$item["left_subtitle"]?></p>
					</div>
					<div class="timeline__right">
						<h4 class="timeline__title" <?php if($user["is_logged"]): ?>contenteditable data-name="section_timeline"<?php endif; ?>><?=$item["right_title"]?></h4>
						<p class="timeline__subtitle" <?php if($user["is_logged"]): ?>contenteditable data-name="section_timeline"<?php endif; ?>><?=$item["right_subtitle"]?></p>
					</div>
					<?php if($user["is_logged"]): ?>
						<div class="timeline__actions">
							<button class="timeline__sort sortable__handle" title="Sort"><?=getSvg("img/icons/sort.svg")?></button>
							<button class="timeline__del" data-del="delete_timeline/<?=$key?>" title="Delete"><?=getSvg("img/icons/close.svg")?></button>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if($user["is_logged"]): ?>
			<div class="text-center"><button id="timeline-add" class="btn icon round" type="button"><?=getSvg("img/icons/plus.svg")?></button></div>
		<?php endif; ?>
	<?php elseif($user["is_logged"]): ?>
		<button id="timeline-add" class="btn" type="button">Add timeline item</button>
	<?php endif; ?>
	<footer class="section__footer">
		<?php if(!empty($GLOBALS["person_resume"])): ?>
			<a class="btn primary" href="/<?=$GLOBALS['person_resume']?>" download="<?=$GLOBALS['site_name']?>">Download resume</a>
		<?php endif; ?>
		<?php if(!empty($portfolio)): ?>
			<a class="btn" href="#portfolio">Explore works</a>
		<?php endif; ?>
	</footer>
</section>

<?php if(!empty($skills) || $user["is_logged"]): ?>
	<section id="skills" class="section">
		<h2 class="section__title">Skills</h2>
		<?php
			$section_skills = $skills;
		?>
		<?php if($user["is_logged"]): ?>
			<div class="table-top">
				<p>Total: <b data-table-count><?=!empty($section_skills) ? count($section_skills) : 0?></b></p>
				<button class="btn icon" data-add="section_skills" title="Add an essence"><?=getSvg("img/icons/plus.svg")?></button>
			</div>
		<?php endif; ?>
		<table>
			<tbody <?php if($user["is_logged"]): ?>class="sortable" data-name="section_skills"<?php endif; ?>>
				<?php if(!empty($section_skills)): foreach($section_skills as $key => $item): ?>
					<tr>
						<th <?php if($user["is_logged"]): ?>contenteditable data-name="section_skills"<?php endif; ?>><?=$item["left"]?></th>
						<td <?php if($user["is_logged"]): ?>contenteditable data-name="section_skills"<?php endif; ?>><?=$item["right"]?></td>
						<?php if($user["is_logged"]): ?>
							<td class="table__tools">
								<button class="sortable__handle" title="Sort"><?=getSvg("img/icons/bars.svg")?></button>
								<button data-del="delete_skills/<?=$key?>" title="Delete"><?=getSvg("img/icons/delete.svg")?></button>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; endif; ?>
			</tbody>
		</table>
	</section>
<?php endif; ?>

<?php if(!empty($portfolio)): ?>
	<section id="portfolio" class="section">
		<h2 class="section__title">Portfolio</h2>
		<?php if(!empty($portfolio)): ?>
			<?php
				$portfolio_pins_query = $pdo->query("SELECT url, title FROM {$prefix}_portfolio WHERE enabled is true AND pinned is true ORDER BY cdate DESC;");
				$portfolio_pins = $portfolio_pins_query->fetchAll(PDO::FETCH_ASSOC);
			?>
			<div class="pins">
				<?php foreach($portfolio_pins as $item): ?>
					<a class="pins__item" href="/portfolio/<?=$item['url']?>">
						<?=getSvg("img/icons/pinned.svg")?>
						<span><?=$item['title']?></span>
					</a>
				<?php endforeach; ?>
			</div>
			<div class="portfolio">
				<?php foreach($portfolio as $item): ?>
					<?php
						$details = json_decode($item["details"] ?? '[]', true);
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

<section id="contact" class="section">
	<h2 class="section__title">Get In Touch</h2>
	<?php if(!empty($GLOBALS["section_contact"]) || $user["is_logged"]): ?>
		<div class="section__content" <?php if($user["is_logged"]): ?>contenteditable data-name="section_contact"<?php endif; ?>>
			<?php if(!empty($GLOBALS["section_contact"])): ?>
				<?=$GLOBALS["section_contact"]?>
			<?php elseif($user["is_logged"]): ?>
				<p>Click here to edit</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<form action="contact_form" method="post" data-reset>
		<input class="half-width left" type="text" name="name" placeholder="Name" required minlength="2" maxlength="32">
		<input class="half-width right" type="email" name="email" placeholder="Email" required minlength="6" maxlength="128">
		<textarea name="message" placeholder="Message" rows="4" required minlength="2" maxlength="500"></textarea>
		<input type="submit" value="Send Message">
	</form>
</section>
