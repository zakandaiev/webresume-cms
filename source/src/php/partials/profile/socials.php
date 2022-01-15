<?php

$socials = json_decode($GLOBALS["person_socials"], true);
if(empty($socials)) $socials = array();

$icons = array();
$icons_dir = "img/socials";
if(file_exists($icons_dir)) {
	$icons = glob($icons_dir."/*.{svg,png,jpg,gif}", GLOB_BRACE);
}

?>

<section class="section">
	<?php if(isset($_GET["add"])): ?>
		<h2 class="section__title">Add a social</h2>
		<form action="add_social" data-redirect="/profile/socials" method="post">
			<label>
				Icon
				<br><small>Must be in <?=$GLOBALS["site_url"]?>/img/socials and have .svg, .png, .jpg or .gif extension</small>
			</label>
			<select name="icon" required>
				<?php foreach ($icons as $icon): ?>
					<?php $icon_name = basename($icon); ?>
					<option value="<?=$icon_name?>"><?=$icon_name?></option>
				<?php endforeach; ?>
			</select>
			<label>Url</label>
			<input type="text" name="url" placeholder="Url" minlength="2" required>
			<button type="submit" class="btn">Add</button>
		</form>
	<?php elseif(isset($_GET["edit"])): ?>
		<?php
			$edit_social = array();
			if(isset($socials[$_GET["edit"]])) {
				$edit_social = $socials[$_GET["edit"]];
			}
		?>
		<h2 class="section__title">Edit a social</h2>
		<?php if(!empty($edit_social)): ?>
			<form action="edit_social" data-redirect="/profile/socials" method="post">
				<label>
					Icon
					<br><small>Must be in <?=$GLOBALS["site_url"]?>/img/socials and have .svg, .png, .jpg or .gif extension</small>
				</label>
				<select name="icon" required>
					<?php foreach ($icons as $icon): ?>
						<?php $icon_name = basename($icon); ?>
						<option value="<?=$icon_name?>" <?php if($edit_social["icon"] === $icon_name): ?>selected<?php endif; ?>><?=$icon_name?></option>
					<?php endforeach; ?>
				</select>
				<label>Url</label>
				<input type="text" name="url" value="<?=$edit_social['url']?>" placeholder="Url" minlength="2" required>
				<input type="hidden" name="social_id" value="<?=$_GET["edit"]?>">
				<button type="submit" class="btn">Edit</button>
			</form>
		<?php else: ?>
			<p>There is no such social.</p>
		<?php endif; ?>
	<?php else: ?>
		<h2 class="section__title">Socials</h2>
		<div class="table-top">
			<p>Total: <b data-table-count><?=count($socials)?></b></p>
			<a href="/profile/socials?add" class="btn icon" title="Add a social"><?=getSvg("img/icons/plus.svg")?></a>
		</div>
		<?php if(!empty($socials)): ?>
			<table>
				<thead>
					<tr>
						<th>Url</th>
						<th>Icon</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="sortable" data-name="person_socials">
					<?php foreach($socials as $key => $social): ?>
						<tr>
							<td><a href="<?=$social['url']?>" class="color-primary bordered" target="_blank"><?=$social["url"]?></a></td>
							<td><?=$social["icon"]?></td>
							<td class="table__tools">
								<button class="sortable__handle" title="Sort"><?=getSvg("img/icons/bars.svg")?></button>
								<a href="/profile/socials?edit=<?=$key?>" title="Edit"><?=getSvg("img/icons/edit.svg")?></a>
								<button data-del="delete_social/<?=$key?>" title="Delete"><?=getSvg("img/icons/delete.svg")?></button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p>No socials found.</p>
		<?php endif; ?>
	<?php endif; ?>
</section>