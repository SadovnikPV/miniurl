<?php
	include_once "includes/header.php" ;

	if (isset($_GET['url']) && !empty($_GET['url'])) {
		$url = strtolower(trim($_GET['url']));

		$link = get_link_info($url);
		
		if (empty($link)) {
			header('Location: 404.php');
			echo "Not found!";
			die;
		}
		update_views($url);
		header('Location: ' .  $link['long_link']);
		die;
	} 

?>
	<main class="container">
		<section class="main-page">
			<div class="main-page__head">
				<h2 class="main-page__title">Сервис сокращения ссылок</h2>
			</div>
			<?php if (!isset($_SESSION['user']['id']) && empty($_SESSION['user']['id'])) { ?>
			<div class="main-page__reg-tip">
				<h2 class="main-page__subtitle">Для начала работы необходимо <a href="<?php echo get_url('register.php'); ?>">зарегистрироваться</a> или <a href="<?php echo get_url('login.php'); ?>">войти</a> под своей учетной записью</h2>
			</div>
			<?php } ?>
			<div class="main-page__statistics">
				<h2 class="main-page__stat-subtitle">Наша статистика:</h2>
				<div class="main-page__statistics-item statistics-item">
					<h2 class="statistics-item__text">Пользователей в системе: <?php echo $users_count ?></h2>
				</div>
				<div class="main-page__statistics-item statistics-item">
					<h2 class="statistics-item__text">Ссылок в системе: <?php echo $links_count ?></h2>
				</div>
				<div class="main-page__statistics-item statistics-item">
					<h2 class="statistics-item__text">Всего переходов по ссылкам: <?php echo $views_count ?></h2>
				</div>
			</div>
		</section>
	</main>

<?php include "includes/footer.php" ?>

