 <!-- ----------- Страница редактирования полных ссылок пользователем --------------- -->
<?php
    include_once "header.php";

    $links = get_link_by_id($_GET['id']);
?>

<main class="container">
    <div class="edit-link">
        <div class="edit-link__head">
            <h2 class="edit-link__title">Редактирование ссылки</h2>
        </div>
        <div class="edit-link__main">
            <form class="edit-link__form" action="<?= get_url('includes/edit_link_script.php') ?>" method="post">
                <input class="edit-link__input" type="text" placeholder="Ссылка" value="<?= $links['long_link'] ?>" name="edited_link">
                <input type="hidden" value="<?= $links['id'] ?>" name="link_id">
                <button class="edit-link__button" type="submit">Изменить</button>
            </form>
            <a href="<?= get_url('profile.php') ?>" class="edit-link__link-back">Вернуться в профиль без изменений</a>
        </div>
    </div>
</main>

<?php include "footer.php" ?>