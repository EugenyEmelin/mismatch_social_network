<?php 
  //Открытие сессии
  require_once('sessionstart.php');
  require_once('defines.php');
  require_once('connect_defines.php');

  //Выаод заголовка страницы
  $page_title = 'Там, где противоположности сходятся';
  require_once('header.php');
?>
  <main> 
  <aside>
  
    <?php
    if (isset($_GET['usersearch'])) {
      $user_search = $_GET['usersearch'];
      $result_search_query = build_query($user_search);
    }
    $cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $result_per_page = 5; //количество объявлений на странице
    $skip = (($cur_page - 1) * $result_per_page); //второй аргумент в выражении LIMIT (кол-во записей которые нужно пропустить)

  // Generate the navigation menu
    // if (isset($_SESSION['username'])) {
    //   echo '&#10084; <a href="viewprofile.php">Просмотр профиля</a><br />';
    //   echo '&#10084; <a href="editprofile.php">Редактировать профиль</a><br />';
    //   echo '&#10084; <a href="logout.php">Выход из приложения ('.$_SESSION['username'].') </a>';
    //   echo '&#10084; <a href="PATTERN_VIEW_PROFILE.php">aaaaaaa</a>';
    // } else {
    //   echo '&#10084; <a href="login.php">Вход в приложение</a><br>';
    //   echo '&#10084; <a href="signup.php">Создать учётную запись</a>';   
    // }

  // Connect to the database 
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME); 

  // Retrieve the user data from MySQL
    if (isset($user_search)) {
      $query = $result_search_query;
    } else {
      $query = "SELECT `ID`, `Имя`, `Фото` FROM `MISMATCH_USER` WHERE `Имя` IS NOT NULL ORDER BY `Дата` DESC";
    }

    $data = mysqli_query($dbc, $query); //выполняем запрос для вывода всех записей из БД
    $total = mysqli_num_rows($data); //сохраняем общее количество записей в переменной total
    $num_pages = ceil($total / $result_per_page); //количество страниц
    $query .= "LIMIT $skip, $result_per_page";

    $data = mysqli_query($dbc, $query); //опять выполняем запрос, но уже с ограниченным количеством записей


  // Loop through the array of user data, formatting it as HTML
    echo '<h4>Результаты поиска:</h4>';
    echo '<table>';
    while ($row = mysqli_fetch_array($data)) { //пока не закончатся строки таблицы, удовлетворяющие запросу...
      if (is_file(MM_UPLOADPATH . $row['Фото']) && filesize(MM_UPLOADPATH . $row['Фото']) > 0) {
        echo '<tr><td><img src="' . MM_UPLOADPATH . $row['Фото'] . '" alt="' . $row['Имя'] . '" /></td>';
      }
      else {
        echo '<tr><td><img src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['Имя'] . '" /></td>';
      }
      echo '<td>' . $row['Имя'] .' '. $row['Фамилия'] . '</td></tr>';
    }
    echo '</table>';

    //Если все информация не помещается на одной странице - создание навигационных гиперссылок
    if ($num_pages > 1) {
      echo generate_page_links($user_search, $cur_page, $num_pages);
    }

    mysqli_close($dbc);
    // var_dump($_GET['search-input']);
    // var_dump($result_search_query);
    ?>
  </aside> 
</main>
</body> 
</html>
