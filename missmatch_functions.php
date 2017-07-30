<?php 

function build_query($user_search) {
	$search_query = "SELECT * FROM MISMATCH_USER";
	//Извлечение критериев поиска в массив
	$clean_search = str_replace(',', ' ', $user_search); //заменяем запятые на пробелы
	$search_words = explode(' ', $clean_search); //создадим массив из поисковых слов, разделённых пробелами
	$final_search_words = array();
	if (count($search_words) > 0) {
		foreach($search_words as $word) {
			if (!empty($word)) {
				$final_search_words[] = $word;
			}
		}
	}
	//Создание условного выражения WHERE с использованием всех критериев поиска
	$where_list = array();
	if (count($final_search_words) > 0) {
		foreach($final_search_words as $word) {
			$where_list[] = "`Имя` LIKE '%$word%' OR `Фамилия` LIKE '%$word%' OR `Ник` LIKE '%$word%'"; //
		}
	}
	$where_clause = implode(' OR ', $where_list); //создать строку из массива $where_list, вставив между элементами оператор'OR'
	//Добавление условного выражения WHERE к поисковому запросу
	if (!empty($where_clause)) {
		$search_query .= " WHERE $where_clause"; 
	}
	return $search_query;
}

function generate_page_links($user_search, $cur_page, $num_pages) {
	$page_links = '';
	if ($cur_page > 1) {
		$page_links .= '<a href="' .$_SERVER['PHP_SELF']. '?usersearch=' .$user_search. '&page=' .($cur_page - 1). '"><-</a>';
	} else {
		$page_links .= '<-';
	}
	//Прохождение в цикле всех страниц и создание гиперссылок, указывающих на конкретные страницы
	for ($i=1; $i <= $num_pages; $i++) {
		if ($cur_page == $i) {
			$page_links .= ' ' .$i;
		} else {
			$page_links .= ' <a href="' .$_SERVER['PHP_SELF']. '?usersearch=' .$user_search. '&page=' .$i. '">' .$i. '</a>'; 
		}
	}
	//Если это не последняя страница - создание гиперссылки "следующая страница" (>>)
	if ($cur_page < $num_pages) {
		$page_links .= ' <a href="' .$_SERVER['PHP_SELF']. '?usersearch=' .$user_search. '&page=' .($cur_page + 1). '">-></a>';
	} else {
		$page_links .= ' ->';
	}
	return $page_links;
}
// функция обезвреживания вводимых пользователем данных
function disinfect($var) {
	global $dbc;
	$var = trim($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return mysqli_real_escape_string($dbc, $var);
}
//
function sign_up($user, $password) {
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME); //Соединение с базой данных
	if (!empty($user) && !empty($password)) { //если имя и пароль введены пользователем
		//Если через форму отправляются непустые значения, поиск имени пользователя и его пароля в БД
		$query = "SELECT `ID`, `Ник`, `Пароль` FROM `MISMATCH_USER` WHERE `Ник`='$user' AND `Пароль`= SHA('$password')";
		$data = mysqli_query($dbc, $query);
		//Если в таблице БД нашлась запрашиваемая строка...
		if (mysqli_num_rows($data) == 1) { //если в результате нашлась 1 строка в БД 
			//Вход в приложение прошёл успешно, сохранение в куки имени пользователя и его id
			$row = mysqli_fetch_array($data); //добавить данные из найденной строки в массив и присвоить переменной $row
			$_SESSION['user_id'] = $row['ID']; //записать в текущую сессию id пользователя из массива $row
			$_SESSION['username'] = $row['Ник'];//записать в текущую сессию ник пользователя из массива $row
			setcookie('user_id', $row['ID'], time() + (60*60*24*30)); //создать куки для id
			$home_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/PATTERN_VIEW_PROFILE.php?id='.$_SESSION['user_id'];//url-адрес текущей страницы
			header('Location: ' .$home_url); //заголовок
		} else {$error_msg = 'Неверные логин и/или пароль';}
	} else {$error_msg = 'Введите имя и пароль';}
	mysqli_close($dbc);
}
//Функция поиска запроса в друзья
function find_request_to_friend($id) {
	global $dbc;
	$find_request_to_friend;
	$my_id = $_SESSION['user_id'];
	$query = "SELECT `user_from_id`, `user_to_id` FROM `mismatch_friends_request` WHERE (`user_from_id` = $my_id AND `user_to_id` = $id) OR (`user_from_id` = $id AND `user_to_id` = $my_id)";
	$data = mysqli_query($dbc, $query);
	if (mysqli_num_rows($data) == 0) {
		$find_request_to_friend = false;
	} else {
		$find_request_to_friend = true;
	}
	return $find_request_to_friend;
}

//
function find_friend() {

}

//
function find_sub() {
	global $dbc, $id;
	$find_sub;
	$my_id = $_SESSION['user_id'];
	$query = "SELECT `subscriber`, `followed` FROM `mismatch_subscribers` WHERE `subscriber` = $my_id AND `followed` = $id";
	$data = mysqli_query($dbc, $query);
	if (mysqli_num_rows($data) == 0) {
		$find_sub = false;
	} else {
		$find_sub = true;
	}
	return $find_sub;
}


?>