


<?php
// если массив с ошибками есть, то обрабатываем его
$errorNameClass = isset($formErrors['name']) ? 'form__input--error' : '';
$errorProjectClass = isset($formErrors['project']) ? 'form__input--error' : '';


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
        href="addproject" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>
    
<form class="form"  action="add.php" method="post" autocomplete="off" enctype = "multipart/form-data">
  <div class="form__row">
    <label class="form__label" for="name">Название <sup>*</sup></label>
    
    <!-- input NAME -->
    <input class="form__input <?= $errorNameClass?>" type="text" name="name" id="name" value="<?= getPostVal('name')?>" placeholder="Введите название">
    <?php
    // если есть ошибка для этого поля, то выводим текст ошибки
      if(isset($formErrors['name'])) {
        echo "<p class = 'form__message'>". $formErrors['name']."</p>";
      }
    ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="project">Проект <sup>*</sup></label>

    <!-- input SELECT project -->
    <select class="form__input form__input--select <?= $errorProjectClass?>" name="project" id="project">

      <?php
        foreach ($projectList as $value) {
          echo ' <option value="'.$value['name'].'">'.$value['name'].'</option>';
        }
      ?>
   
    </select>
  </div>

  <div class="form__row">
    <label class="form__label" for="date">Дата выполнения</label>

    <!-- input DATE -->
    <input class="form__input form__input--date" type="text" name="date" id="date" value="<?= getPostVal('date')?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
    <?php
     // если дата не корректна, то выводить текст ошибки
      if(isset($formErrors['date'])) {
        echo "<p class = 'form__message'>". $formErrors['date']."</p>";
      }
    ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="file">Файл</label>

    <div class="form__input-file">

      <!-- input FILE -->
      <input class="visually-hidden" type="file" name="file" id="file" value="">

      <label class="button button--transparent" for="file">
        <span>Выберите файл</span>
      </label>
    </div>

  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">
  </div>
</form>
            </main>
</div>