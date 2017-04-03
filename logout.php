<?php
	session_start();
	// //Если пользователь вошёл в приложение, удаление куки, приводящее к выходу его из приложения
	// if (isset($_COOKIE['user_id'])) {
	// 	//Установка момента истечения срока действия для куки, содержащих идентификатор пользователя и его имя.
	// 	setcookie('user_id', '', time() - 3600);
	// 	setcookie('username', '', time() - 3600);
	// }

	if (isset($_SESSION['user_id'])) {
		//Удаление переменных сессии путём обнуления суперглобального массива $_SESSION
		$_SESSION = array();
		//Удаление куки, содержащего id сессии
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time() - 3600);
		}
	}
	session_destroy();
	//Преадресация на главную страницу
	$home_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/first_page.php' ;
	header('Location: '.$home_url);
?>
