<?php
// если массив с ошибками есть, то обрабатываем его
$errorNameClass = isset($formErrors['name']) ? 'form__input--error' : '';
$errorEmailClass = isset($formErrors['email']) ? 'form__input--error' : '';
$errorPasswordClass = isset($formErrors['password']) ? 'form__input--error' : '';

?>

<div class="content">
        <section class="content__side">
          <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

          <a class="button button--transparent content__side-button" href="authorization.php">Войти</a>
        </section>

        <main class="content__main">
          <h2 class="content__main-heading">Регистрация аккаунта</h2>

          <form class="form" action="registration.php" method="post" autocomplete="off">
            <div class="form__row">
              <label class="form__label" for="email">E-mail <sup>*</sup></label>

              <input class="form__input <?= $errorEmailClass?>" type="text" name="email" id="email" value="<?= getPostVal('email')?>" placeholder="Введите e-mail">

            <?php
              // если есть ошибка для этого поля, то выводим текст ошибки
              if(isset($formErrors['email'])) {
                echo "<p class = 'form__message'>". $formErrors['email']."</p>";
              }
            ?>
            </div>

            <div class="form__row">
              <label class="form__label" for="password">Пароль <sup>*</sup></label>

              <input class="form__input <?= $errorPasswordClass?>" type="password" name="password" id="password" value="<?= getPostVal('password')?>" placeholder="Введите пароль">
              <?php
              // если есть ошибка для этого поля, то выводим текст ошибки
              if(isset($formErrors['password'])) {
                echo "<p class = 'form__message'>". $formErrors['password']."</p>";
              }
              ?>
            </div>

            <div class="form__row">
              <label class="form__label" for="name">Имя <sup>*</sup></label>

              <input class="form__input <?= $errorNameClass?>" type="text" name="name" id="name" value="<?= getPostVal('name')?>" placeholder="Введите пароль">
              <?php
              // если есть ошибка для этого поля, то выводим текст ошибки
              if(isset($formErrors['name'])) {
                echo "<p class = 'form__message'>". $formErrors['name']."</p>";
              }
              ?>
            </div>

            <div class="form__row form__row--controls">
              <?php
                if(!empty($formErrors)){
                  echo ' <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>';
                }
              ?>
             

              <input class="button" type="submit" name="" value="Зарегистрироваться">
            </div>
          </form>
        </main>
      </div>