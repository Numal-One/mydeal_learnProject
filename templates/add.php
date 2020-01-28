
<?php

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['project'])){
//   $form = $_POST;

//   $formNameValue = $_POST['name'];
//   $formProjectValue = $_POST['project'];
//   $formDateValue = $_POST['date'];
 
//   if (isset($_FILES['file']) && strlen($_FILES['file']['name']) > 0) {
    
//     echo "<br> 0 ".strlen($_FILES['file']['name']);

//     // сгенерируем уникальное имя для сохраненного файла
//     // разобьем имя файла из формы (который загрузили) на массив со строками. 
//     // Разделителем будет точка (для выделения расширения файла)
//     $file_name = explode( '.', $_FILES['file']['name']);
//     // сгенерируем имя "прибавим" расширение оригинального файла
//     $file_name = uniqid().'.'.$file_name[1];
//     echo "<br> 1 ". $file_name;
    
//     $file_path = ROOT_PATH . '/uploads/';
//     echo "<br> 2 ". $file_path;

//     $file_url = '/uploads/' . $file_name;
//     echo "<br> 3 ". $file_url;
    
//     move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
//     $file_href = "<a download href='$file_url'>$file_name</a>";
//     echo "<br> 4 ". $file_href;
//   }
// }


// if(false) {
//   $sql = "INSERT INTO `task` (project_id, user_id, name, creation_date, deadline, status, file) VALUES (?, ?, ?, NOW(), ?, 0, ?)";
// }
  
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
        href="pages/form-project.html" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="add.php" method="post" autocomplete="off" enctype = "multipart/form-data">
  <div class="form__row">
    <label class="form__label" for="name">Название <sup>*</sup></label>
    
    <!-- input NAME -->
    <input class="form__input" type="text" name="name" id="name" value="" placeholder="Введите название" required>
  </div>

  <div class="form__row">
    <label class="form__label" for="project">Проект <sup>*</sup></label>

    <!-- input SELECT project -->
    <select class="form__input form__input--select" name="project" id="project">

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
    <input class="form__input form__input--date" type="text" name="date" id="date" value="" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
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