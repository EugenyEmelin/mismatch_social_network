
<?php   
 //Выаод заголовка страницы
$page_title = 'Там, где противоположности сходятся';
require_once('header.php');
 
  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

//если пользователь нажал на кнопку сохранить (name='submit')
  if (isset($_POST['submit'])) { 
    // Grab the profile data from the POST
    // $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $first_name = disinfect($_POST['firstname']);
    $last_name = disinfect($_POST['lastname']);
    $gender = disinfect($_POST['gender']);
    $birthdate = disinfect($_POST['birthdate']);
    $city = disinfect($_POST['city']);
    $country = disinfect($_POST['country']);
    $old_picture = disinfect($_POST['old_picture']); //текущий аватар профиля
    $new_picture = disinfect($_FILES['new_picture']['name']); //загружаемый новый аватар
    $new_picture_type = $_FILES['new_picture']['type']; //расширение загружаемого Фото
    $new_picture_size = $_FILES['new_picture']['size']; //размер загружаемого изображения
    if (!empty($_FILES['new_picture']['tmp_name'])) //если файл изображения загружен
      list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']); //присвоить двум переменным значения высоты и ширины фото 
    
    $error = false;

    //_____________________________________________________________________________________________________________________________________picture____
    if (!empty($new_picture)) {
      $saveto = "$first_name$last_name.jpg";
      $typeok = true;

      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) &&
        ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {

        if ($_FILES['new_picture']['error'] == 0) {
          $target = MM_UPLOADPATH . basename($new_picture); //наш новый относительный путь файла изображения
          if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) { //если файл перемещён в новое место успешно...
            if (!empty($old_picture) && ($old_picture != $new_picture)) {
              @unlink(MM_UPLOADPATH . $old_picture); 
            }
          } else {
            @unlink($_FILES['new_picture']['tmp_name']);
            $error = true;
            $img_error = 'Sorry, there was a problem uploading your picture';
          }
        }
      } else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink($_FILES['new_picture']['tmp_name']);
        $error = true;
        $img_error =  'Фото должно быть в формате jpeg, gif или png, максимальный размер фото ' . (MM_MAXFILESIZE / 1024) .
          ' KB, разрешение ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT;
      }
    } //___________________________________________________________________________________________________________________________________picture____

    // if (isset($new_picture)) {
    //   $typeok = true; //приемлимое расширение загружаемого фото
    //   $saveto = MM_UPLOADPATH.basename($new_picture); //ссылка на новое фото, которое мы будем генерировать

    //   switch ($new_picture_type) {
    //     case "image/gif":
    //       $src = imagecreatefromgif($saveto); //создаём пустое gif изображение  
    //     break;
    //     case "image/jpeg": 
    //       $src = imagecreatefromjpeg($saveto); //создаём пустое jpeg изображение
    //     break;
    //     case "image/jpg": 
    //       $src = imagecreatefromjpeg($saveto); //создаём пустое jpeg изображение
    //     break;
    //     case "image/pjpeg": 
    //       $src = imagecreatefromjpeg($saveto); //создаём пустое jpeg изображение
    //     break;
    //     case "image/png":
    //       $src = imagecreatefrompng($saveto); //создаём пустое png изображение
    //     break;
    //     default:
    //       $typeok = false;
    //     break;
    //   }

    //     if ($typeok) {
    //     list($w, $h) = getimagesize($saveto); //получаем массив размеров нового фото и присваиваваем высоту и ширину переменным

    //     $max = 200;
    //     $tw = $w;
    //     $th = $h;

    //     if ($w > $h && $w > $max) { //если ширина больше высоты и больше допустимого значения.. 
    //       $th = $max/$w*$h;
    //       $h = $max;
    //     } else if ($h > $w && $h > $max) { //если высота больше ширины и больше допустимого значения..
    //       $tw = $max/$h*$w;
    //       $th = $max;
    //     } else if ($w > $max) {
    //       $tw = $th = $max;
    //     } else if ($w > $h && $w > $max && $h > $max) {
    //       $tw = $max/$w*$h;
    //       $th = $max;
    //     }
    //     $tmp = imagecreatetruecolor($tw, $th);
    //     imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
    //     imageconvolution($tmp, [[–1, –1, –1], [–1, 16, –1], [–1, –1, –1]], 8, 0);
    //     imagejpeg($tmp, $saveto);
    //     imagedestroy($tmp);
    //     imagedestroy($src);
    //   }
    // }



    // Update the profile data in the database
    if (!$error) {
        if (!empty($new_picture)) {
          $query = "UPDATE `MISMATCH_USER` SET `Ник` = '".$_SESSION['username']."', `Имя` = '$first_name', `Фамилия` = '$last_name', `Пол` = '$gender', `День рождения` = '$birthdate', `Город` = '$city', `Страна` = '$country',
           `Фото` = '$new_picture' WHERE `ID` = '".$_SESSION['user_id']."'";
        } else {
          $query = "UPDATE `MISMATCH_USER` SET `Ник` = '".$_SESSION['username']."', `Имя` = '$first_name', `Фамилия` = '$last_name', `Пол` = '$gender',
           `День рождения` = '$birthdate', `Город` = '$city', `Страна` = '$country' WHERE `ID` = '".$_SESSION['user_id']."'";
        }
        mysqli_query($dbc, $query);
    }
  } else {
    // Grab the profile data from the database
    $query = "SELECT `Ник`, `Имя`, `Фамилия`, `Пол`, `День рождения`, `Город`, `Страна`, `Фото` FROM `MISMATCH_USER` WHERE `ID` = '".$_SESSION['user_id']."'";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);
    if ($row != NULL) {
      // $username = $row['Ник'];
      $first_name = $row['Имя'];
      $last_name = $row['Фамилия'];
      $gender = $row['Пол'];
      $birthdate = $row['День рождения'];
      $city = $row['Город'];
      $country = $row['Страна'];
      $old_picture = $row['Фото'];
    } else {
      $img_error = 'There was a problem accessing your profile';
    }
  }

  if (isset($_POST['submit_2'])) { //если пользователь нажал на кнопку сохранить (name='submit_2')
    $error_select_from_DB = "";
    $phone = disinfect($_POST['phone']); #телефон
    $phone_2 = disinfect($_POST['phone_2']); #телефон 2
    $email = disinfect($_POST['email']); #email
    $site = disinfect($_POST['site']); #site
    $skype = disinfect($_POST['skype']); #skype
    $vk = disinfect($_POST['vk']); #vk
    $fb = disinfect($_POST['fb']); #fb
    $github = disinfect($_POST['github']); #github
    $twitter = disinfect($_POST['twitter']); #twitter
    $instagram = disinfect($_POST['instagram']); #instagram

    // if (!empty($phone) &&  !empty($phone_2) && !empty($email) /*&& !empty($site) && !empty($skype) && !empty($vk)*/) {
      if (!preg_match('/^[1-9]\d+$/', $phone) && !empty($phone)) {
        $error_phone_1 = "Некорректный номер моб. телефона"; ##ERROR_PHONE
        $phone = '';
      } else if (!preg_match('/^[1-9]\d+$/', $phone_2) && !empty($phone_2)) {
        $error_phone_2 = "Некорректный номер доп. телефона"; ##ERROR_PHONE
        $phone_2 = '';
      } else if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._]*@/', $email) && !empty($email)) {
        $error_email = "Некорректный адрес электронной почты"; ##ERROR_EMAIL
        $email = '';
      } else {
        $query = "UPDATE `mismatch_user_contacts` SET `Телефон`='$phone', `Телефон-2`='$phone_2', `email`='$email', `Сайт`='$site', `skype`='$skype', `vk`='$vk', `fb`='$fb', `github`='$github', `twitter`='$twitter', `instagram`='$instagram' WHERE `mismatch_user_contacts`.`ID` = '".$_SESSION['user_id']."'";
      }
      mysqli_query($dbc, $query);
    // }

  } else { //если массив $_POST пустой, то берём данные из базы данных
    $query = "SELECT `Телефон`, `Телефон-2`, `email`, `Сайт`, `skype`, `vk`, `fb`, `github`, `twitter`, `instagram` FROM `mismatch_user_contacts` WHERE `ID` = '".$_SESSION['user_id']."'";
    $data = mysqli_query($dbc, $query); //выбираем из БД пользователя id которого равен id позьзователя текущей сессии ( ID = $_SESSION['user_id'] )
    $row = mysqli_fetch_array($data); //преобразуем строку из БД в массив и присваиваем переменной $row
    if ($row != NULL) { //если в массиве $row содержатся данные
      $phone =  $row['Телефон'];
      $phone_2 = $row['Телефон-2'];
      $email = $row['email'];
      $site = $row['Сайт'];
      $skype = $row['skype'];
      $vk =  $row['vk'];
      $fb = $row['fb'];
      $github = $row['github'];
      $twitter = $row['twitter'];
      $instagram = $row['instagram'];
    } else {
      $error_select_from_DB = "Ошибка соединения. Повторите попытку позже.";
    }
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
   <!-- <label for="username">Ник:</label> -->
   <!-- <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>" /><br /> -->
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
   <label for="country">Страна:</label>
   <input type="text" id="country" name="country" value="<?php if (!empty($country)) echo $country; ?>" /><br />
   <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />


   <?php if (!empty($old_picture)) {
    echo '<div class="profile" id="edit_photo_change"><img src="' . MM_UPLOADPATH . $old_picture . '" alt="Profile Picture" /></div>';
   } ?>
   <div class="photo">
    <label for="new_picture" id="photo_label">Выбрать фото</label>
    <input type="file" id="new_picture" name="new_picture" />
   </div>
   <input type="submit" value="Сохранить" name="submit" />
   <p class="error"><?php if (isset($img_error)) echo $img_error; ?></p>
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
     <input type="text" id="Skype" name="skype" value="<?php if (!empty($skype)) echo $skype; ?>" /><br><br>
     <label for="Vk">Вконтакте:</label>
     <input type="text" id="vk" name="vk" value="<?php if (!empty($vk)) echo $vk; else echo ''; ?>" /><br>
     <label for="fb">Facebook:</label>
     <input type="text" id="fb" name="fb" value="<?php if (!empty($fb)) echo $fb; else echo ''; ?>" /><br>
     <label for="github">github:</label>
     <input type="text" id="github" name="github" value="<?php if (!empty($github)) echo $github; else echo ''; ?>" /><br>
     <label for="twitter">twitter:</label>
     <input type="text" id="twitter" name="twitter" value="<?php if (!empty($twitter)) echo $twitter; else echo ''; ?>" /><br>
     <label for="instagram">instagram:</label>
     <input type="text" id="instagram" name="instagram" value="<?php if (!empty($instagram)) echo $instagram; else echo ''; ?>" /><br>
     <input type="submit" value="Сохранить" name="submit_2" />
   </form>
 </div>
 <div id="tabs-3"></div>
 <div id="tabs-4"></div>
</div>
</body> 
</html>
