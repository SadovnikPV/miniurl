<?php
	include_once "includes/functions.php";
?>
<!doctype html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/css/bootstrap-icons.css">
	<link rel="stylesheet" href="/css/style.css">
	<title><?= SITE_NAME ?></title>
</head>

<body>
	<header>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container">
				<a class="navbar-brand" href="<?= get_url('index.php') ?>"><?= SITE_NAME ?></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link" aria-current="page" href="<?= get_url('index.php') ?>">Главная</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" href="<?= get_url('profile.php') ?>">Профиль</a>
						</li>
					</ul>
					<form class="d-flex" action="includes/add.php" method="post">
						<input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
						<input class="form-control me-2" type="text" placeholder="Ссылка" aria-label="Ссылка" name="link">
						<button class="btn btn-success" type="submit"><i class="bi bi-plus-lg"></i></button>
					</form>
					<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
						<li class="nav-item header__login-block">
							<a href="<?= get_url('profile.php') ?>" class="header__login-link"><?= $_SESSION['user']['login']; ?></a>
							<a href="<?= get_url('includes/logout.php') ?>" class="btn btn-primary">Выйти</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</header>