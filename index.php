<?php 
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
    $cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $result_per_page = 5; //количество объявлений на странице
    $skip = (($cur_page - 1) * $result_per_page); //второй аргумент в выражении LIMIT (кол-во записей которые нужно пропустить)

  // Connect to the database 
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME); 

  // Retrieve the user data from MySQL 
    $query = $result_search_query;

    $data = mysqli_query($dbc, $query); //выполняем запрос для получения всех записей из БД. Результат запроса сохраняем в переменной $data
    $total = mysqli_num_rows($data); //сохраняем общее количество записей в переменной total
    $num_pages = ceil($total / $result_per_page); //количество страниц. С помощью функции ceil() округляем в большую сторону до целого числа
    $query .= "LIMIT $skip, $result_per_page"; //

    $data = mysqli_query($dbc, $query); //опять выполняем запрос, но уже с ограниченным количеством записей

  // Loop through the array of user data, formatting it as HTML
    echo 
    '<div id="column_wrap_1">
    
    </div>';
    echo '<div id="column_wrap_2">';
    echo "<div class=\"user-list-title\">ЛЮДИ <span>$total</span></div>";
    // echo '<table>';
    while ($row = mysqli_fetch_array($data)) { //пока не закончатся строки таблицы, удовлетворяющие запросу...
      (is_file(MM_UPLOADPATH . $row['Фото']) && filesize(MM_UPLOADPATH . $row['Фото']) > 0) ? 
        $photo = MM_UPLOADPATH . $row['Фото'] : 
        $photo = MM_UPLOADPATH . 'nopic.jpg';
      $fname = $row['Имя'];
      $lname = $row['Фамилия'];
      $id = $row['ID'];

      echo 
      "<div class=\"show-user-wrap\">
        <div class=\"view-profile-avatar-wrape\">      
          <img src=\"$photo\" alt=\"$fname\">
        </div>
        <div class=\"view-profile-links\">
          <a href=\"".VIEW_PROFILE."?id=$id\">$fname $lname</a>
        </div>";
        if (isset($_SESSION['username']) && $_SESSION['user_id'] != $id) {
          ?>
            <div class="flw-and-subs-links">
            <form action="" method="POST">

              <button class="add_friend" name="<?php echo $id ?>"><?php echo find_request_to_friend($id) ? 'Заявка отправлена' : 'Добавить в друзья'?></button>

              <button class="following" name="<?php echo $id ?>"><?php echo find_sub() ? 'Вы подписаны' : 'Подписаться' ?></button>

            </form>
          </div>
    <?php
          }
      echo "</div>";
    }
    // echo '</table>';
    //Если все информация не помещается на одной странице - создание навигационных гиперссылок
    if ($num_pages > 1) {
      echo generate_page_links($user_search, $cur_page, $num_pages); //Используем написанную функцию (см. в _functions.php), которая разбивает результат поиска на несколько страниц (в будущем переделать на подгрузку аяксом!)
    }
    mysqli_close($dbc);
    } else {
      $query = "SELECT * FROM `MISMATCH_USER`";
      echo '<h4>ЛЮДИ</h4>';
    }
    echo '</div>';
    ?>
  </aside> 
</main>
</body> 
</html>
