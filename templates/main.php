<?php
$searchValue = $_GET['search'] ?? '';

// если выбрана вкладка фильтра по датам, то меняем параметры в ссылке
$taskFilterStr = '';
if($taskFilter != 1){
    $taskFilterStr = '&taskFilter='.$taskFilter;
}
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
                        <a class="main-navigation__list-item-link" href="index.php?projectid='.$project['id'].$taskFilterStr.'">'.$project['name'].'</a>
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
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="get" autocomplete="off">
            <input class="search-form__input" type="text" name="search" value="<?=$searchValue?>" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <?php
            // если выбран пункт в фильтре проектов, то меняем гет параметры в ссылках
            $projectIdFilter = '';
            if(isset($_GET['projectid'])){
                $projectIdFilter = '&projectid='.$_GET['projectid'];
            }
        ?>
        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/index.php?taskFilter=1<?=$projectIdFilter?>" class="tasks-switch__item <?php if($taskFilter == 1) echo 'tasks-switch__item--active ';?>">Все задачи</a>
                <a href="/index.php?taskFilter=2<?=$projectIdFilter?>" class="tasks-switch__item <?php if($taskFilter == 2) echo 'tasks-switch__item--active ';?>">Повестка дня</a>
                <a href="/index.php?taskFilter=3<?=$projectIdFilter?>" class="tasks-switch__item <?php if($taskFilter == 3) echo 'tasks-switch__item--active ';?>">Завтра</a>
                <a href="/index.php?taskFilter=4<?=$projectIdFilter?>" class="tasks-switch__item <?php if($taskFilter == 4) echo 'tasks-switch__item--active ';?>">Просроченные</a>
            </nav>

            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <input class="checkbox__input visually-hidden show_completed" type="checkbox" 
                <?php
                    if ($show_complete_tasks === 1) {
                        echo "checked";
                    }
                ?>
                >
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">

            <?php
                // если нужный ключ есть в массиве, то...
                if(isset($_GET['projectid'])){
                    // id активного проекта
                    $activeProjectId = $_GET['projectid'];
                    // выбран ли проект с определенным id (пустое или нет поле с id проекта)
                    $projectIdIsset = TRUE;
                } else {
                    $projectIdIsset = FALSE;
                }

                // проверка корректности id проекта, указанного в параметре запроса
                if ( $projectIdIsset && !projectIdCheck($activeProjectId, $projectList)){
                    echo '404. Page not found';
                } elseif (empty($taskList) && $searchValue){
                    echo 'По вашему запросу ничего не найдено';
                } else {
                    foreach ($taskList as $task) {
                        // далее идет код для фильтра дел по категориям
                        if ( $projectIdIsset && $activeProjectId !== $task['categoryId'] || !deadlineFilter($task['deadline'], $taskFilter)){
                            continue;
                        }

                        $deadLineIsComing = deadLineLeftHours($task['deadline']);
                        if ($show_complete_tasks === 0 && intval($task['isComplete']) === 1) {
                            continue;
                        }

                        echo '
                            <tr class="tasks__item task';
                            if ($task['isComplete']) {
                                echo ' task--completed';
                            }

                            if ($deadLineIsComing < 24 && $deadLineIsComing >= 0 && !$task['isComplete'] && $deadLineIsComing !== 'noDeadline') {
                                echo ' task--important';
                            }

                            echo '">
                                <td> <a class="button button--done-project"
                                href="/index.php?taskComplete='.$task['taskId'].'" target="project_add">&#9745;</a></td>
                                <td class="task__select">
                                    <label class="checkbox task__checkbox">
                                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                                        <span class="checkbox__text">'.$task['name'].'</span>
                                    </label>
                                </td>

                                <td class="task__file">';

                                if(isset($task['file'])){
                                    echo '<a class="download-link" href="';
                                    echo $task['file'];
                                    echo '" download></a>';
                                    
                                }
                                
                                echo '</td>

                                <td class="task__date">';
                                // не удержался и прикрутил сообщение, если просрал дедлайн
                                if ($deadLineIsComing < 0) {
                                    echo 'Wasted!';
                                } else {
                                    echo $task['deadline'];
                                }
                                echo '</td>
                            </tr>
                        ';
                    }
                }

                
            ?>
            
        
        </table>
            </main>
</div>