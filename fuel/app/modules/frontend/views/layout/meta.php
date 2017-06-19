<?php
	if(isset($meta['title'])){
?>

	<title><?=$meta['title']?></title>
	<meta name="description" content="<?=$meta['description']?>">
	<meta name="keywords" content="<?=$meta['keywords']?>">

	<meta property="og:title" content="<?=$meta['og_title']?>" />
	<meta property="og:site_name" content="<?=$meta['og_site_name']?>" />
	<meta property="og:description" content="<?=$meta['og_description']?>" />
	<meta property="og:url" content="<?=$meta['og_url']?>"/>
	<meta property="og:image" content="<?=$meta['og_image']?>" />
<?php
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="shortcut icon" href="/assets/frontend/img/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="icon" href="/assets/frontend/img/favicon.ico" type="image/vnd.microsoft.icon">

