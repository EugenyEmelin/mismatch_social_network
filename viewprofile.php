<?php  
  require_once('login.php');
  require_once('sessionstart.php');
  
  require_once('defines.php');
  require_once('connect_defines.php');

//Выаод заголовка страницы
  $page_title = 'Там, где противоположности сходятся';
  require_once('header.php');
  ?>
<main>

  
<?php
  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

  // Grab the profile data from the database
  if (!isset($_GET['user_id'])) {
    $query = "SELECT `Ник`, `Имя`, `Фамилия`, `Пол`, `День рождения`, `Город`, `Регион`, `Фото` FROM MISMATCH_USER WHERE ID = '".$_SESSION['user_id']."'";
  }
  else {
    $query = "SELECT `Ник`, `Имя`, `Фамилия`, `Пол`, `День рождения`, `Город`, `Регион`, `Фото` FROM MISMATCH_USER WHERE ID = '" . $_GET['user_id'] . "'";
  }
  $data = mysqli_query($dbc, $query);

  if (mysqli_num_rows($data) == 1) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);
    echo '<table>';
    if (!empty($row['Ник'])) {
      echo '<tr><td class="label">Ник:</td><td>' . $row['Ник'] . '</td></tr>';
    }
    if (!empty($row['Имя'])) {
      echo '<tr><td class="label">Имя:</td><td>' . $row['Имя'] . '</td></tr>';
    }
    if (!empty($row['Фамилия'])) {
      echo '<tr><td class="label">Фамилия:</td><td>' . $row['Фамилия'] . '</td></tr>';
    }
    if (!empty($row['Пол'])) {
      echo '<tr><td class="label">Пол:</td><td>';
      if ($row['Пол'] == 'M') {
        echo 'Мужской';
      }
      else if ($row['Пол'] == 'F') {
        echo 'Женский';
      }
      else {
        echo '?';
      }
      echo '</td></tr>';
    }
    if (!empty($row['День рождения'])) {
      if (!isset($_GET['user_id']) || ($user_id == $_GET['user_id'])) {
        // Show the user their own birthdate
        echo '<tr><td class="label">День рождения:</td><td>' . $row['День рождения'] . '</td></tr>';
      }
      else {
        // Show only the birth year for everyone else
        list($year, $month, $day) = explode('-', $row['День рождения']);
        echo '<tr><td class="label">Year born:</td><td>' . $year . '</td></tr>';
      }
    }
    if (!empty($row['Город']) || !empty($row['Регион'])) {
      echo '<tr><td class="label">Место жительства:</td><td>' . $row['Город'] . ', ' . $row['Регион'] . '</td></tr>';
    }
    if (!empty($row['Фото'])) {
      echo '<tr><td class="label">Picture:</td><td><img src="' . MM_UPLOADPATH . $row['Фото'] .
        '" alt="Profile Picture" /></td></tr>';
    }
    echo '</table>';
    if (!isset($_GET['user_id']) || ($user_id == $_GET['user_id'])) {
      echo '<a href="editprofile.php">Редактировать профиль</a></p>';
    }
  } // End of check for a single row of user results
  else {
    echo '<p class="error">There was a problem accessing your profile.</p>';
  }

  mysqli_close($dbc);
?>
</main>
</body> 
</html>
