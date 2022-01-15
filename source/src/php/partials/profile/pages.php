<?php

$pages_query = $pdo->query("SELECT * FROM {$prefix}_pages ORDER BY cdate DESC;");
$pages = $pages_query->fetchAll(PDO::FETCH_ASSOC);

$templates = array();
$templates_dir = "templates";
if(file_exists($templates_dir)) {
	$templates = glob($templates_dir."/*.php");
}

?>

<section class="section">
	<?php if(isset($_GET["add"])): ?>
		<h2 class="section__title">Add a page</h2>
		<form action="add_page" data-redirect="/profile/pages" method="post">
			<label>Title</label>
			<input type="text" name="title" placeholder="Title" minlength="2" maxlength="512" required data-form-behavior="text_to_url" data-target="url">
			<label>Url</label>
			<input type="text" name="url" placeholder="Url" minlength="2" maxlength="128" required data-form-behavior="text_to_url">
			<label>
				Template
				<br><small>Must be in <?=$GLOBALS ["site_url"]?>/templates and have .php extension</small>
			</label>
			<select name="template">
				<option selected value>Don't apply</option>
				<?php foreach ($templates as $template): ?>
					<?php $template_name = basename($template); ?>
					<option value="<?=$template_name?>"><?=$template_name?></option>
				<?php endforeach; ?>
			</select>
			<label>SEO description</label>
			<textarea name="seo_description" placeholder="SEO description" rows="1"></textarea>
			<label>SEO keywords</label>
			<textarea name="seo_keywords" placeholder="SEO keywords" rows="1"></textarea>
			<label>SEO image</label>
			<?=printFormFiles(array())?>
			<input type="file" data-type="image" name="seo_image" accept="image/*">
			<label>Enabled</label>
			<label class="switcher">
				<input type="checkbox" name="enabled" checked>
				<span class="slider round"></span>
			</label>
			<button type="submit" class="btn">Add</button>
		</form>
	<?php elseif(isset($_GET["edit"])): ?>
		<?php
			$edit_page = array();
			foreach($pages as $page) {
				if($page["id"] == $_GET["edit"]) {
					$edit_page = $page;
					break;
				}
			}
		?>
		<h2 class="section__title">Edit a page</h2>
		<?php if(!empty($edit_page)): ?>
			<form action="edit_page" data-redirect="/profile/pages" method="post">
				<label>Title</label>
				<input type="text" name="title" value="<?=$edit_page["title"]?>" placeholder="Title" minlength="2" maxlength="512" required>
				<label>Url</label>
				<input type="text" name="url" value="<?=$edit_page["url"]?>" placeholder="Url" minlength="2" maxlength="128" required>
				<label>
					Template
					<br><small>Must be in <?=$GLOBALS ["site_url"]?>/templates and have .php extension</small>
				</label>
				<select name="template">
					<option value>Don't apply</option>
					<?php foreach ($templates as $template): ?>
						<?php $template_name = basename($template); ?>
						<option value="<?=$template_name?>" <?php if($template_name === $edit_page["template"]): ?>selected<?php endif; ?>><?=$template_name?></option>
					<?php endforeach; ?>
				</select>
				<label>SEO description</label>
				<textarea name="seo_description" placeholder="SEO description" rows="1"><?=$edit_page["seo_description"]?></textarea>
				<label>SEO keywords</label>
				<textarea name="seo_keywords" placeholder="SEO keywords" rows="1"><?=$edit_page["seo_keywords"]?></textarea>
				<label>SEO image</label>
				<?=printFormFiles($edit_page["seo_image"])?>
				<input type="file" data-type="image" name="seo_image" accept="image/*">
				<label>Enabled</label>
				<label class="switcher">
					<input type="checkbox" name="enabled" <?php if($edit_page["enabled"]): ?>checked<?php endif; ?>>
					<span class="slider round"></span>
				</label>
				<input type="hidden" name="page_id" value="<?=$_GET["edit"]?>">
				<button type="submit" class="btn">Edit</button>
			</form>
		<?php else: ?>
			<p>There is no such page.</p>
		<?php endif; ?>
	<?php else: ?>
		<h2 class="section__title">Pages</h2>
		<div class="table-top">
			<p>Total: <b data-table-count><?=count($pages)?></b></p>
			<a href="/profile/pages?add" class="btn icon" title="Add a page"><?=getSvg("img/icons/plus.svg")?></a>
		</div>
		<?php if(!empty($pages)): ?>
			<table>
				<thead>
					<tr>
						<th>Title</th>
						<th>Url</th>
						<th>Template</th>
						<th>Enabled</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($pages as $page): ?>
						<?php
							if($page["url"] === "home") {
								$page_url = "";
							} else {
								$page_url = $page["url"];
							}
						?>
						<tr>
							<td><?=$page["title"]?></td>
							<td><a href="/<?=$page_url?>" class="color-primary bordered" target="_blank"><?=$page["url"]?></a></td>
							<td>
								<?php if(!empty($page["template"])): ?>
									<?=$page["template"]?>
								<?php else: ?>
									<?=getSvg("img/icons/minus.svg")?>
								<?php endif; ?>
							</td>
							<td>
								<?php if($page["enabled"]): ?>
									<?=getSvg("img/icons/plus.svg")?>
								<?php else: ?>
									<?=getSvg("img/icons/minus.svg")?>
								<?php endif; ?>
							</td>
							<td class="table__tools">
								<a href="/profile/pages?edit=<?=$page["id"]?>" title="Edit"><?=getSvg("img/icons/edit.svg")?></a>
								<button data-del="delete_page/<?=$page["id"]?>" title="Delete"><?=getSvg("img/icons/delete.svg")?></button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p>No pages found.</p>
		<?php endif; ?>
	<?php endif; ?>
</section>