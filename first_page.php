<?php
	require_once('sessionstart.php');
	require_once('connect_defines.php');

	 //Выаод заголовка страницы
	$page_title = 'Там, где противоположности сходятся';
	require_once('header.php');
	//Обнуление сообщения об ошибке
	$error_msg = "";
	//Если пользователь ещё не вошёл в приложение, попытка войти
	if (!isset($_SESSION['user_id'])) {

		if (isset($_POST['submit'])) {
			//Соединение с базой данных
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
			//Получение введеных пользователем данныхх
			$user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
			$user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

			if (!empty($user_username) && !empty($user_password)) {
				//Если через форму отправляются непустые значения, поиск имени пользователя и его пароля в БД
				$query = "SELECT `ID`, `Ник`, `Пароль` FROM `MISMATCH_USER` WHERE `Ник`='$user_username' AND `Пароль`= SHA('$user_password')";
				$data = mysqli_query($dbc, $query);
				//Если в таблице БД нашлась запрашиваемая строка...
				if (mysqli_num_rows($data) == 1) {
					//Вход в приложение прошёл успешно, сохранение в куки имени пользователя и его id
					$row = mysqli_fetch_array($data);
					$_SESSION['user_id'] = $row['ID'];
					$_SESSION['username'] = $row['Ник'];
					setcookie('user_id', $row['ID'], time() + (60*60*24*30));
					$home_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/PATTERN_VIEW_PROFILE.php' ;
					header('Location: ' .$home_url);
				} else {$error_msg = 'Неверные логин и/или пароль';}
			} else {$error_msg = 'Введите имя и пароль';}
			mysqli_close($dbc);
		}

		if (isset($_POST['submit_sign'])) {
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
			$username = mysqli_real_escape_string($dbc, trim($_POST['username_sign']));
			$password1 = mysqli_real_escape_string($dbc, trim($_POST['password1_sign']));
			$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2_sign']));

			if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
  		//Проверка того, что никто из уже зарегестрированных пользователей не пользуется таким же именем, как то, которое ввел новый пользователь
				$query = "SELECT * FROM `MISMATCH_USER` WHERE `Ник`='$username'";
				$data = mysqli_query($dbc, $query);

				if (mysqli_num_rows($data) == 0) {
  			//Имя введённое пользователем не используется, поэтому добавляем данные в базу
					$query = "INSERT INTO `MISMATCH_USER` (`ID`, `Ник`, `Пароль`, `Дата`, `Имя`, `Фамилия`, `Пол`, `День рождения`, `Город`, `Страна`, `Фото`) VALUES (0, '$username', SHA('$password1'), NOW(), '', '', '', NOW(), '', '', '')";
					mysqli_query($dbc, $query);

					$query__ = "SELECT * FROM `MISMATCH_USER` WHERE `Ник`='$username'";
					$data__ = mysqli_query($dbc, $query__);
					$row = mysqli_fetch_array($data__);
					$id_cont	= $row['ID'];
					
					$query2 = "INSERT INTO `mismatch_user_contacts` (`ID`, `Телефон`, `Телефон-2`, `email`, `Сайт`, `skype`, `vk`) VALUES ('$id_cont', '00000000', '00000000', '', '', '', '') ";
					mysqli_query($dbc, $query2);

  			//Вывод подтверждения пользователю
					mysqli_close($dbc);
  			// exit();
				} else {
					echo '<p class="error">Учётная запись с таким именем уже существует. Введите другое имя.</p>';
					$username = '';
				}
			} else {echo '<p class="error">Вы должны ввести все данные</p>';}
		}
	}
  // mysqli_close($dbc);
// var_dump($_SESSION['user_id']);
?>

<div id="first_page_wrape">
	<h2 class="fist_page_title">Cоциальная сеть для IT-специалистов</h2>
	<p class="first_page_text">Разместите на Mismatch своё портфолио, опишите технологии, которыми владеете и получайте заказы, соответствующие Вашим интересам.</p>
	<div id="first_page_buttons_wrape">
		<button id="login_btn" class="first_page_buttons">Вход</button>
		<button id="sign_btn" class="first_page_buttons">Регистрация</button>
	</div>
	<div id="modal_login" class="modal_first_wrape">
		<div class="modal_header">
			<b>Вход</b>
			<i class="fa fa-times" aria-hidden="true"></i>
		</div>
		<!-- FORM -->
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="first_page_form" >
			<label class="first_page_label" for="username">Ник:</label>
			<input type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>"><br>
			<label class="first_page_label" for="password">Пароль:</label>
			<input type="password" name="password"><br>
			<input type="submit" value="Войти" name="submit">	
		</form>

	</div>
	<div id="modal_signup" class="modal_first_wrape">
		<div class="modal_header">
			<b>Регистрация</b>
			<i class="fa fa-times" aria-hidden="true"></i>
		</div>

		<!-- FORM -->
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="first_page_form">
			<label class="first_page_label" for="username">Ник: </label>
			<input type="text" id="username" name="username_sign"><br>
			<label class="first_page_label" for="password1">Пароль: </label>
			<input type="password" id="password1" name="password1_sign"><br>
			<label class="first_page_label" for="password2">Повторите: </label>
			<input type="password" id="password2" name="password2_sign">
			<input type="submit" value="Зарегистрироваться" name="submit_sign">
		</form>
	</div>
</div>