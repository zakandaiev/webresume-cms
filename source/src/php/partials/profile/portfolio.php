<?php

$portfolio_query = $pdo->query("SELECT * FROM {$prefix}_portfolio ORDER BY cdate DESC;");
$portfolio = $portfolio_query->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="section">
	<?php if(isset($_GET["add"])): ?>
		<h2 class="section__title">Add a portfolio</h2>
		<form action="add_portfolio" data-redirect="/profile/portfolio" method="post">
			<label>Title</label>
			<input type="text" name="title" placeholder="Title" minlength="2" maxlength="512" required data-form-behavior="text_to_url" data-target="url">
			<label>Url</label>
			<input type="text" name="url" placeholder="Url" minlength="2" maxlength="128" required data-form-behavior="text_to_url">
			<label>Main image</label>
			<?=printFormFiles(array())?>
			<input type="file" data-type="image" name="main_image" accept="image/*">
			<label>Images</label>
			<?=printFormFiles(array())?>
			<input type="file" data-type="image" name="images" accept="image/*" multiple>
			<label>Description</label>
			<textarea name="description" placeholder="Description" rows="1"><p>Description</p></textarea>
			<div class="contenteditable" contenteditable><p>Description</p></div>
			<label>Technologies</label>
			<textarea name="technologies" placeholder="Technologies" rows="1"></textarea>
			<label>Features</label>
			<textarea name="features" placeholder="Features" rows="1"></textarea>
			<label>Date</label>
			<textarea name="date" placeholder="Date" rows="1"></textarea>
			<label>Link</label>
			<input type="url" name="link" placeholder="Link">
			<label>Github</label>
			<input type="url" name="github" placeholder="Github">
			<label>SEO description</label>
			<textarea name="seo_description" placeholder="SEO description" rows="1"></textarea>
			<label>SEO keywords</label>
			<textarea name="seo_keywords" placeholder="SEO keywords" rows="1"></textarea>
			<label>Date of publication</label>
			<input type="datetime-local" name="cdate" value="<?=date('Y-m-d').'T'.date('H:i:s')?>">
			<label>Enabled</label>
			<label class="switcher">
				<input type="checkbox" name="enabled" checked>
				<span class="slider round"></span>
			</label>
			<button type="submit" class="btn">Add</button>
		</form>
	<?php elseif(isset($_GET["edit"])): ?>
		<?php
			$edit_portfolio = array();
			foreach($portfolio as $portfolio) {
				if($portfolio["id"] == $_GET["edit"]) {
					$edit_portfolio = $portfolio;
					break;
				}
			}
		?>
		<h2 class="section__title">Edit a portfolio</h2>
		<?php if(!empty($edit_portfolio)): ?>
			<?php
				$edit_portfolio["details"] = json_decode($edit_portfolio["details"], true);
			?>
			<form action="edit_portfolio" data-redirect="/profile/portfolio" method="post">
				<label>Title</label>
				<input type="text" name="title" value="<?=$edit_portfolio["title"]?>" placeholder="Title" minlength="2" maxlength="512" required>
				<label>Url</label>
				<input type="text" name="url" value="<?=$edit_portfolio["url"]?>" placeholder="Url" minlength="2" maxlength="128" required>
				<label>Main image</label>
				<?=printFormFiles($edit_portfolio["main_image"])?>
				<input type="file" data-type="image" name="main_image" accept="image/*">
				<label>Images</label>
				<?=printFormFiles($edit_portfolio["images"])?>
				<input type="file" data-type="image" name="images" accept="image/*" multiple>
				<label>Description</label>
				<textarea name="description" placeholder="Description" rows="1"><?=$edit_portfolio["description"]?></textarea>
				<div class="contenteditable" contenteditable>
					<?php if(!empty($edit_portfolio["description"])): ?>
						<?=$edit_portfolio["description"]?>
					<?php else: ?>
						<p>Description</p>
					<?php endif; ?>
				</div>
				<label>Technologies</label>
				<textarea name="technologies" placeholder="Technologies" rows="1"><?php if(isset($edit_portfolio["details"]["technologies"])): ?><?=$edit_portfolio["details"]["technologies"]?><?php endif; ?></textarea>
				<label>Features</label>
				<textarea name="features" placeholder="Features" rows="1"><?php if(isset($edit_portfolio["details"]["features"])): ?><?=$edit_portfolio["details"]["features"]?><?php endif; ?></textarea>
				<label>Date</label>
				<textarea name="date" placeholder="Date" rows="1"><?php if(isset($edit_portfolio["details"]["date"])): ?><?=$edit_portfolio["details"]["date"]?><?php endif; ?></textarea>
				<label>Link</label>
				<input type="url" name="link" value="<?php if(isset($edit_portfolio["details"]["link"])): ?><?=$edit_portfolio["details"]["link"]?><?php endif; ?>" placeholder="Link">
				<label>Github</label>
				<input type="url" name="github" value="<?php if(isset($edit_portfolio["details"]["github"])): ?><?=$edit_portfolio["details"]["github"]?><?php endif; ?>" placeholder="Github">
				<label>SEO description</label>
				<textarea name="seo_description" placeholder="SEO description" rows="1"><?=$edit_portfolio["seo_description"]?></textarea>
				<label>SEO keywords</label>
				<textarea name="seo_keywords" placeholder="SEO keywords" rows="1"><?=$edit_portfolio["seo_keywords"]?></textarea>
				<label>Date of publication</label>
				<input type="datetime-local" name="cdate" value="<?=formatDateString($edit_portfolio["cdate"], 'Y-m-d').'T'.formatDateString($edit_portfolio["cdate"], 'H:i:s')?>">
				<label>Enabled</label>
				<label class="switcher">
					<input type="checkbox" name="enabled" <?php if($edit_portfolio["enabled"]): ?>checked<?php endif; ?>>
					<span class="slider round"></span>
				</label>
				<input type="hidden" name="portfolio_id" value="<?=$_GET["edit"]?>">
				<button type="submit" class="btn">Edit</button>
			</form>
		<?php else: ?>
			<p>There is no such portfolio.</p>
		<?php endif; ?>
	<?php else: ?>
		<h2 class="section__title">Portfolio</h2>
		<div class="table-top">
			<p>Total: <b data-table-count><?=count($portfolio)?></b></p>
			<a href="/profile/portfolio?add" class="btn icon" title="Add a portfolio"><?=getSvg("img/icons/plus.svg")?></a>
		</div>
		<?php if(!empty($portfolio)): ?>
			<table>
				<thead>
					<tr>
						<th>Title</th>
						<th>Url</th>
						<th>Enabled</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($portfolio as $portfolio): ?>
						<tr>
							<td><?=$portfolio["title"]?></td>
							<td><a href="/portfolio/<?=$portfolio["url"]?>" class="color-primary bordered" target="_blank"><?=$portfolio["url"]?></a></td>
							<td>
								<?php if($portfolio["enabled"]): ?>
									<?=getSvg("img/icons/plus.svg")?>
								<?php else: ?>
									<?=getSvg("img/icons/minus.svg")?>
								<?php endif; ?>
							</td>
							<td class="table__tools">
								<a href="/profile/portfolio?edit=<?=$portfolio["id"]?>" title="Edit"><?=getSvg("img/icons/edit.svg")?></a>
								<button data-del="delete_portfolio/<?=$portfolio["id"]?>" title="Delete"><?=getSvg("img/icons/delete.svg")?></button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p>No portfolio found.</p>
		<?php endif; ?>
	<?php endif; ?>
</section>