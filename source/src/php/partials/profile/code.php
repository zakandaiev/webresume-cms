<?php

$code_query = $pdo->query("
	SELECT
		t_code.*,
		t_portfolio.title as portfolio_title,
		t_portfolio.url as portfolio_url
	FROM {$prefix}_code t_code
		LEFT JOIN {$prefix}_portfolio t_portfolio
		ON t_code.portfolio_id=t_portfolio.id
	ORDER BY t_code.cdate DESC;
");
$code = $code_query->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="section">
	<?php if(isset($_GET["add"])): ?>
		<h2 class="section__title">Add a code snippet</h2>
		<form action="add_code" data-redirect="/profile/code" method="post">
			<label>Title</label>
			<input type="text" name="title" placeholder="Title" minlength="2" maxlength="512" required>
			<label>Portfolio case</label>
			<select name="portfolio_id">
				<option selected value>Don't apply</option>
				<?php
					$portfolio_query = $pdo->query("SELECT * FROM {$prefix}_portfolio WHERE enabled is true ORDER BY cdate DESC;");
					$portfolio = $portfolio_query->fetchAll(PDO::FETCH_ASSOC);
				?>
				<?php foreach($portfolio as $item):?>
					<option value="<?=$item["id"]?>"><?=$item["title"]?></option>
				<?php endforeach; ?>
			</select>
			<label>Extension</label>
			<input type="text" name="extension" placeholder="Extension" minlength="2" maxlength="32" required>
			<label>Code</label>
			<textarea name="code" placeholder="Code" rows="1" required></textarea>
			<label>Enabled</label>
			<label class="switcher">
				<input type="checkbox" name="enabled" checked>
				<span class="slider round"></span>
			</label>
			<button type="submit" class="btn">Add</button>
		</form>
	<?php elseif(isset($_GET["edit"])): ?>
		<?php
			$edit_code = array();
			foreach($code as $code) {
				if($code["id"] == $_GET["edit"]) {
					$edit_code = $code;
					break;
				}
			}
		?>
		<h2 class="section__title">Edit a code</h2>
		<?php if(!empty($edit_code)): ?>
			<form action="edit_code" data-redirect="/profile/code" method="post">
				<label>Title</label>
				<input type="text" name="title" value="<?=$edit_code["title"]?>" placeholder="Title" minlength="2" maxlength="512" required>
				<label>Portfolio case</label>
				<select name="portfolio_id">
					<option value>Don't apply</option>
					<?php
						$portfolio_query = $pdo->query("SELECT * FROM {$prefix}_portfolio WHERE enabled is true ORDER BY cdate DESC;");
						$portfolio = $portfolio_query->fetchAll(PDO::FETCH_ASSOC);
					?>
					<?php foreach($portfolio as $item):?>
						<?php
							$selected_status = '';
							if($item["id"] === $edit_code["portfolio_id"]) {
								$selected_status = 'selected';
							}
						?>
						<option value="<?=$item["id"]?>" <?=$selected_status?>><?=$item["title"]?></option>
					<?php endforeach; ?>
				</select>
				<label>Extension</label>
				<input type="text" name="extension" value="<?=$edit_code["extension"]?>" placeholder="Extension" minlength="2" maxlength="32" required>
				<label>Code</label>
				<textarea name="code" placeholder="Code" rows="1" required><?=$edit_code["code"]?></textarea>
				<label>Enabled</label>
				<label class="switcher">
					<input type="checkbox" name="enabled" <?php if($edit_code["enabled"]): ?>checked<?php endif; ?>>
					<span class="slider round"></span>
				</label>
				<input type="hidden" name="code_id" value="<?=$_GET["edit"]?>">
				<button type="submit" class="btn">Edit</button>
			</form>
		<?php else: ?>
			<p>There is no such code snippet.</p>
		<?php endif; ?>
	<?php else: ?>
		<h2 class="section__title">Code snippets</h2>
		<div class="table-top">
			<p>Total: <b data-table-count><?=count($code)?></b></p>
			<a href="/profile/code?add" class="btn icon" title="Add a code"><?=getSvg("img/icons/plus.svg")?></a>
		</div>
		<?php if(!empty($code)): ?>
			<table>
				<thead>
					<tr>
						<th>Title</th>
						<th>Portfolio case</th>
						<th>Extension</th>
						<th>Enabled</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($code as $code): ?>
						<tr>
							<td><?=$code["title"]?></td>
							<td>
								<?php if(isset($code["portfolio_id"])): ?>
									<a href="/portfolio/<?=$code["portfolio_url"]?>" class="color-primary bordered" target="_blank"><?=$code["portfolio_title"]?></a>
								<?php else: ?>
									<?=getSvg("img/icons/minus.svg")?>
								<?php endif; ?>
							</td>
							<td><span class="label <?=extensionLabelClass($code["extension"])?>"><?=$code["extension"]?></span></td>
							<td>
								<?php if($code["enabled"]): ?>
									<?=getSvg("img/icons/plus.svg")?>
								<?php else: ?>
									<?=getSvg("img/icons/minus.svg")?>
								<?php endif; ?>
							</td>
							<td class="table__tools">
								<a href="/profile/code?edit=<?=$code["id"]?>" title="Edit"><?=getSvg("img/icons/edit.svg")?></a>
								<button data-del="delete_code/<?=$code["id"]?>" title="Delete"><?=getSvg("img/icons/delete.svg")?></button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p>No code snippets found.</p>
		<?php endif; ?>
	<?php endif; ?>
</section>