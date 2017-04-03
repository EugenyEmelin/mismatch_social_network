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
	$where_clause = implode(' OR ', $where_list); //создать строку из массива $where_list вставив между элементами оператор 'OR'

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

?>