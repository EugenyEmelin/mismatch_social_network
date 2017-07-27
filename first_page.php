<?php
	 //Выаод заголовка страницы
	$page_title = 'Там, где противоположности сходятся';
	require_once('header.php');
	require_once('missmatch_functions.php');
	//Обнуление сообщения об ошибке
	$error_msg = "";

	//Если пользователь ещё не вошёл в приложение, попытка войти
	if (!isset($_SESSION['user_id'])) {
		if (isset($_POST['submit'])) { //если пользователь нажал на кнопку входа
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME); //Соединение с базой данных
			//Получение введеных пользователем данныхх
			$user_username = disinfect($_POST['username']); //обезвредим введённое имя пользователя
			$user_password = disinfect($_POST['password']); //обезвредим пароль
			sign_up($user_username, $user_password);
			// if (!empty($user_username) && !empty($user_password)) { //если имя и пароль введены пользователем
			// 	//Если через форму отправляются непустые значения, поиск имени пользователя и его пароля в БД
			// 	$query = "SELECT `ID`, `Ник`, `Пароль` FROM `MISMATCH_USER` WHERE `Ник`='$user_username' AND `Пароль`= SHA('$user_password')";
			// 	$data = mysqli_query($dbc, $query);
			// 	//Если в таблице БД нашлась запрашиваемая строка...
			// 	if (mysqli_num_rows($data) == 1) { //если в результате нашлась 1 строка в БД 
			// 		//Вход в приложение прошёл успешно, сохранение в куки имени пользователя и его id
			// 		$row = mysqli_fetch_array($data); //добавить данные из найденной строки в массив и присвоить переменной $row
			// 		$_SESSION['user_id'] = $row['ID']; //записать в текущую сессию id пользователя из массива $row
			// 		$_SESSION['username'] = $row['Ник'];//записать в текущую сессию ник пользователя из массива $row
			// 		setcookie('user_id', $row['ID'], time() + (60*60*24*30)); //создать куки для id
			// 		$home_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/PATTERN_VIEW_PROFILE.php?id='.$_SESSION['user_id'];//url-адрес текущей страницы
			// 		header('Location: ' .$home_url); //заголовок
			// 	} else {$error_msg = 'Неверные логин и/или пароль';}
			// } else {$error_msg = 'Введите имя и пароль';}
			// mysqli_close($dbc);
		}
		if (isset($_POST['submit_sign'])) { //если пользователь нажал на кнопку регистрации
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME); //Соединение с базой данных
			$username = disinfect($_POST['username_sign']);
			$password1 = disinfect($_POST['password1_sign']);
			$password2 = disinfect($_POST['password2_sign']);
			$f_name = disinfect($_POST['f_name']);
			$l_name = disinfect($_POST['l_name']);
			if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2) && !empty($f_name) && !empty($l_name)) { //если все поля не пустые и пароль1 = пароль2
  				//Проверка того, что никто из уже зарегестрированных пользователей не пользуется таким же именем, как то, которое ввел новый пользователь
				$query = "SELECT * FROM `MISMATCH_USER` WHERE `Ник`='$username'"; //выбрать всех пользователей из БД с таким ником
				$data = mysqli_query($dbc, $query); 
				if (mysqli_num_rows($data) == 0) { //если пользователей с таким ником не нашлось добавляем данные в базу
					$date = date('d.m.Y');
					$query_insert = "INSERT INTO `MISMATCH_USER` (`ID`, `Ник`, `Пароль`, `Дата`, `Имя`, `Фамилия`, `Пол`, `День рождения`, `Город`, `Страна`, `Фото`) VALUES (0, '$username', SHA('$password1'), NOW(), '$f_name', '$l_name', '', '$date', '', '', '')";
					mysqli_query($dbc, $query_insert); //добавить данные в таблицу MISMATCH_USER

					$query_select = "SELECT * FROM `MISMATCH_USER` WHERE `Ник`='$username'";
					$data_select = mysqli_query($dbc, $query_select); //выбран пользователь с ником $username
					$row = mysqli_fetch_array($data_select); //поместить данные пользователя в массив $row
					$id_cont = $row['ID']; //присвоить id пользователя переменной $id_cont		
					$query_insert_2 = "INSERT INTO `mismatch_user_contacts` (`ID`) VALUES ('$id_cont') ";
					mysqli_query($dbc, $query_insert_2); //добавить в таблицу mismatch_user_contacts id 

					sign_up($username, $password1);
				} else {
					$error_msg = "Пользователь с таким ником уже существует";
					$username = '';
				}
			} else $error_msg = "Вы должны ввести все данные";
		}
	}
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
			<!-- <label class="first_page_label" for="username">Ник:</label> -->
			<input type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username;  ?>" class="first_page_input" placeholder="Ник"><br>
			<!-- <label class="first_page_label" for="password">Пароль:</label> -->
			<input type="password" name="password" class="first_page_input" placeholder="Пароль"><br>
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
			<input type="text" id="username" name="username_sign" class="first_page_input" placeholder="Ник"><br>
			<input type="text" name="f_name" id="f_name" placeholder="Имя" class="first_page_input"><input type="text" name="l_name" placeholder="Фамилия" class="first_page_input">
			<input type="password" id="password1" name="password1_sign" class="first_page_input" placeholder="Пароль"><br>

			<input type="password" id="password2" name="password2_sign" class="first_page_input" placeholder="Повторите пароль"><br>

			<input type="text" id="verify" name="verify" class="first_page_input" placeholder="Введите надпись с картинки"><br>
			<img src="captcha.php" alt="Проверка идентификационной фразы" class="captcha">
			<input type="submit" value="Зарегистрироваться" name="submit_sign">
	
		</form>

	</div>
</div>
<script src="js/modal.js"></script>