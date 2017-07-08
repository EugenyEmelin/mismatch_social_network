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
				$home_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/viewprofile.php' ;
				header('Location: ' .$home_url);
			} else {$error_msg = 'Неверные логин и/или пароль';}
		} else {$error_msg = 'Введите имя и пароль';}
	}
}

	//Если куки не содержит данных, выводится сообщение об ошибке и форма входа в приложение; в противном случае подтверждение входа
if (empty($_SESSION['user_id'])) {
	echo '<p class="error">'.$error_msg.'</p>';
?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset>
			<legend>Вход в приложение</legend>
			<label for="username">Имя пользователя:</label>
			<input type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>"><br>
			<label for="password">Пароль:</label>
			<input type="password" name="password"><br>
		</fieldset>
		<input type="submit" value="Войти" name="submit">	
	</form>
<?php 
} 
?>
</body>
</html>
