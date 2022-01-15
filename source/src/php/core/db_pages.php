<?php

$db_pages_query = $pdo->query("SELECT * FROM {$prefix}_pages WHERE enabled is true ORDER BY cdate DESC;");
$db_pages = $db_pages_query->fetchAll(PDO::FETCH_ASSOC);