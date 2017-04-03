<?php 
  require_once('sessionstart.php');

  require_once('defines.php');
  require_once('connect_defines.php');
 //Выаод заголовка страницы
  $page_title = 'Там, где противоположности сходятся';
  require_once('header.php');

  //Соединение с базой данных
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

  if (isset($_POST['submit'])) {
  	$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  	$password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
  	$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

  	if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
  		//Проверка того, что никто из уже зарегестрированных пользователей не пользуется таким же именем, как то, которое ввел новый пользователь
  		$query = "SELECT * FROM `mismatch_user` WHERE `Ник`='$username'";
  		$data = mysqli_query($dbc, $query);

  		if (mysqli_num_rows($data) == 0) {
  			//Имя введённое пользователем не используется, поэтому добавляем данные в базу
  			$query = "INSERT INTO `mismatch_user` (`ID`, `Ник`, `Пароль`, `Дата`, `Имя`, `Фамилия`, `Пол`, `День рождения`, `Город`, `Страна`, `Фото`) VALUES (0, '$username', SHA('$password1'), NOW(), '', '', '', NOW(), '', '', '')";
  			// $query_2 = "INSERT INTO `MISMATCH_USER_CONTACTS` (`ID`) VALUES (0)";
  			mysqli_query($dbc, $query);
  			// mysqli_query($dbc, $query_2);

  			//Вывод подтверждения пользователю
  			echo '<br><br><p>Ваша новая учётная запись создана. Вы можете войти в приложение и <a href="editprofile.php">и отредактировать свой профиль.</a>';
  			mysqli_close($dbc);
  			exit();
  		} else {
  			echo '<p class="error">Учётная запись с таким именем уже существует. Введите другое имя.</p>';
  			$usernme = '';
  		}
  	} else {echo '<p class="error">Вы должны ввести все данные</p>';}
  }
 
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="reg">
	<fieldset>
		<legend>Регистрация</legend>
		<label for="username">Имя пользователя: </label>
		<input type="text" id="username" name="username"><br>
		<label for="password1">Пароль: </label>
		<input type="password" id="password1" name="password1"><br>
		<label for="password2">Введите пароль ещё раз: </label>
		<input type="password" id="password2" name="password2">
	</fieldset>
	<input type="submit" value="Создать" name="submit">
</form>