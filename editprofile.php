<?php   
require_once('login.php');
require_once('sessionstart.php');

require_once('defines.php');
require_once('connect_defines.php');

 //Выаод заголовка страницы
$page_title = 'Там, где противоположности сходятся';
require_once('header.php');
echo 'h3 id="h3_edit">Редактировать профиль</h3>';

 
  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $first_name = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
    $last_name = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
    $gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
    $birthdate = mysqli_real_escape_string($dbc, trim($_POST['birthdate']));
    $city = mysqli_real_escape_string($dbc, trim($_POST['city']));
    $state = mysqli_real_escape_string($dbc, trim($_POST['state']));
    $old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));
    $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name']));
    $new_picture_type = $_FILES['new_picture']['type'];
    $new_picture_size = $_FILES['new_picture']['size']; 
    if (!empty($_FILES['new_picture']['tmp_name'])) list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
    $error = false;

    // //Контактная информация
    // $phone = mysqli_real_escape_string($dbc, trim($_POST['phone'])); #телефон
    // $phone_2 = mysqli_real_escape_string($dbc, trim($_POST['phone_2'])); #телефон 2
    // $email = mysqli_real_escape_string($dbc, trim($_POST['email'])); #email
    // $github = mysqli_real_escape_string($dbc, trim($_POST['github']));#gitub
    // $soсial_networks = mysqli_real_escape_string($dbc, trim($_POST['soсial_networks']));

    // //Профессиональные навыки
    // $languages = mysqli_real_escape_string($dbc, trim($_POST['languages']));#языки программирования
    // $web = mysqli_real_escape_string($dbc, trim($_POST['web']));#web-технологии
    // $frameworks = mysqli_real_escape_string($dbc, trim($_POST['frameworks']));#фреймворки
    // $exp = mysqli_real_escape_string($dbc, trim($_POST['exp']));#опыт разработки

    //Проекты


    // Validate and move the uploaded picture file, if necessary
    if (!empty($new_picture)) {
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) &&
        ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
        if ($_FILES['new_picture']['error'] == 0) {
          // Move the file to the target upload folder
          $target = MM_UPLOADPATH . basename($new_picture);
          if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
            // The new picture file move was successful, now make sure any old picture is deleted
            if (!empty($old_picture) && ($old_picture != $new_picture)) {
              @unlink(MM_UPLOADPATH . $old_picture); 
            }
          }
          else {
            // The new picture file move failed, so delete the temporary file and set the error flag
            @unlink($_FILES['new_picture']['tmp_name']);
            $error = true;
            echo '<p class="error">Sorry, there was a problem uploading your picture.</p>';
          }
        }
      }
      else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink($_FILES['new_picture']['tmp_name']);
        $error = true;
        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
      }
    }

    // Update the profile data in the database
    if (!$error) {
      if (!empty($first_name) && !empty($last_name) && !empty($gender) && !empty($birthdate) && !empty($city) && !empty($state)) {
        // Only set the picture column if there is a new picture
        if (!empty($new_picture)) {
          $query = "UPDATE `MISMATCH_USER` SET `Ник` = '$username', `Имя` = '$first_name', `Фамилия` = '$last_name', `Пол` = '$gender', `День рождения` = '$birthdate', `Город` = '$city', `Страна` = '$state',
           `Фото` = '$new_picture' WHERE `ID` = '".$_SESSION['user_id']."'";
        } else {
          $query = "UPDATE `MISMATCH_USER` SET `Ник` = '$username', `Имя` = '$first_name', `Фамилия` = '$last_name', `Пол` = '$gender',
           `День рождения` = '$birthdate', `Город` = '$city', `Страна` = '$state' WHERE `ID` = '".$_SESSION['user_id']."'";
        }
        mysqli_query($dbc, $query);

        // Confirm success with the user
        

   
       
      }
      else {
        echo '<p class="error">You must enter all of the profile data (the picture is optional).</p>';
      }
    }
  } // End of check for form submission


  else {
    // Grab the profile data from the database
    $query = "SELECT `Ник`, `Имя`, `Фамилия`, `Пол`, `День рождения`, `Город`, `Страна`, `Фото` FROM `MISMATCH_USER` WHERE `ID` = '".$_SESSION['user_id']."'";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);
    if ($row != NULL) {
      $username = $row['Ник'];
      $first_name = $row['Имя'];
      $last_name = $row['Фамилия'];
      $gender = $row['Пол'];
      $birthdate = $row['День рождения'];
      $city = $row['Город'];
      $state = $row['Страна'];
      $old_picture = $row['Фото'];
    }
    else {
      echo '<p class="error">There was a problem accessing your profile.</p>';
    }
  }



  if (isset($_POST['submit_2'])) {
    $phone = mysqli_real_escape_string($dbc, trim($_POST['phone'])); #телефон
    $phone_2 = mysqli_real_escape_string($dbc, trim($_POST['phone_2'])); #телефон 2
    $email = mysqli_real_escape_string($dbc, trim($_POST['email'])); #email
    $site = mysqli_real_escape_string($dbc, trim($_POST['site'])); #site
    $skype = mysqli_real_escape_string($dbc, trim($_POST['skype'])); #skype
    $vk = mysqli_real_escape_string($dbc, trim($_POST['vk'])); #vk

    if (isset($phone) &&  isset($phone_2) && isset($email) && isset($site) && isset($skype) && isset($vk)) {
      if (!preg_match('/^[1-9]\d{9}$/', $phone) && !preg_match('/^[1-9]\d{9}$/', $phone_2)) {
        echo '<p class="error">Некорректный номер телефона </p>';
        $phone = '';
      } else {
      $query = "UPDATE `MISMATCH_USER_contacts` SET `Телефон` = '$phone', `Телефон-2` = '$phone_2', `email` = '$email', `Сайт` = '$site', `skype` = '$skype', `vk` = '$vk', WHERE `ID` = '".$_SESSION['user_id']."'";    
      }
    }
  } else {
    $query = "";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);
  }

  // mysqli_close($dbc);
?>
<div id="tabs">
<ul>
 <li><a href="#tabs-1">Основная</a></li>
 <li><a href="#tabs-2">Контакты</a></li>
 <li><a href="#tabs-3">Профнавыки</a></li>
 <li><a href="#tabs-4">Проекты</a></li>
</ul>
 <div id="tabs-1">
  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form">
   <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
   <label for="username">Ник:</label>
   <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>" /><br />
   <label for="firstname">Имя:</label>
   <input type="text" id="firstname" name="firstname" value="<?php if (!empty($first_name)) echo $first_name; ?>" /><br />
   <label for="lastname">Фамилия:</label>
   <input type="text" id="lastname" name="lastname" value="<?php if (!empty($last_name)) echo $last_name; ?>" /><br />
   <label for="gender">Пол:</label>
   <select id="gender" name="gender">
    <option value="M" <?php if (!empty($gender) && $gender == 'M') echo 'selected = "selected"'; ?>>Мужской</option>
    <option value="F" <?php if (!empty($gender) && $gender == 'F') echo 'selected = "selected"'; ?>>Женский</option>
   </select><br />
   <label for="birthdate">День рождения:</label>
   <input type="text" id="birthdate" name="birthdate" value="<?php if (!empty($birthdate)) echo $birthdate; else echo 'YYYY-MM-DD'; ?>" /><br />
   <label for="city">Город:</label>
   <input type="text" id="city" name="city" value="<?php if (!empty($city)) echo $city; ?>" /><br />
   <label for="state">Страна:</label>
   <input type="text" id="state" name="state" value="<?php if (!empty($state)) echo $state; ?>" /><br />
   <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />

   <div class="photo">
    <label for="new_picture" id="photo_label">Выбрать фото</label>
    <input type="file" id="new_picture" name="new_picture" />
   </div>

   <?php if (!empty($old_picture)) {
    echo '<div class="profile" id="edit_photo_change"><img src="' . MM_UPLOADPATH . $old_picture . '" alt="Profile Picture" /></div>';
   } ?>
   <input type="submit" value="Сохранить" name="submit" />
  </form>
 </div>

 <div id="tabs-2">
   <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form">
   <label for="phone">Моб. телефон:<span class="plus_seven">+7</span></label>
   <input type="number" id="phone" name="phone" value="<?php if (!empty($phone)) echo $phone; ?>" /><br>
   <label for="phone_2">Доп. телефон:<span class="plus_seven">+7</span></label>
   <input type="number" id="phone_2" name="phone_2" value="<?php if (!empty($phone_2)) echo $phone_2; ?>" /><br><br>
   <label for="email">Email:</label>
   <input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" /><br>
   <label for="site">Сайт:</label>
   <input type="text" id="site" name="site" value="<?php if (!empty($site)) echo $site; ?>" /><br>
   <label for="Skype">Skype:</label>
   <input type="text" id="Skype" name="skype" value="<?php if (!empty($skype)) echo $skype; ?>" /><br />
   <label for="Vk">Vk:</label>
   <input type="text" id="vk" name="vk" value="<?php if (!empty($vk)) echo $vk; else echo ''; ?>" /><br />
   <input type="submit" value="Сохранить" name="submit_2" />
  </form>
 </div>
 <div id="tabs-3"></div>
 <div id="tabs-4"></div>
</div>
</body> 
</html>
