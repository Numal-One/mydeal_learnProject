

<?php
// если массив с ошибками есть, то обрабатываем его
$errorNameClass = isset($formErrors['name']) ? 'form__input--error' : '';
?>

<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
            <?php
                foreach ($projectList as $project) {
                    
                    echo '
                    <li class="main-navigation__list-item';

                    // делаем активным пункт списка проектов
                    if (isset($_GET['projectid']) && $_GET['projectid'] === $project['id']){
                        echo ' main-navigation__list-item--active';
                    }

                    echo '">
                        <a class="main-navigation__list-item-link" href="index.php?projectid='.$project['id'].'">'.$project['name'].'</a>
                        <span class="main-navigation__list-item-count">'.projectsInTaskListCount($taskList, $project['name']).'</span>
                    </li>
                    ';
                }
            ?>

            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button"
        href="addproject.php" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form"  action="addproject.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input <?= $errorNameClass?>" type="text" name="name" id="project_name" value="<?= getPostVal('name')?>" placeholder="Введите название проекта">
            <?php
                // если есть ошибка для этого поля, то выводим текст ошибки
                if(isset($formErrors['name'])) {
                    echo "<p class = 'form__message'>". $formErrors['name']."</p>";
                }
             ?>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
        </form>
    </main>
</div>