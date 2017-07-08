<?php 
require_once('missmatch_functions.php'); 
require_once('sessionstart.php');
require_once('defines.php');
require_once('connect_defines.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8 />
  <title><?php echo $page_title; ?></title>
  <style>
    @font-face {
      font-family: 'Proxima Nova Thin';
      src: url(fonts/Proxima-Nova-Thin.otf);
    }
    @font-face {
      font-family: 'Proxima Nova Regular';
      src: url(fonts/Proxima-Nova-Regular.otf);
    }
    @font-face {
      font-family: 'SegoeUIRegular';
      src:url(fonts/SegoeUIRegular.ttf);
    }
  </style>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link rel="stylesheet" type="text/css" href="css/first_page_style.css" />
  <script src="https://use.fontawesome.com/16dc031fb6.js"></script>
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="js/modal.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script>
    $(function() {
      $('#tabs').tabs();
    });
  </script>
  
</head>
<body>
  <header>
  <div id="header_960">
  <h2 class="logo"><a href="<?php echo isset($_SESSION['username'])? 'PATTERN_VIEW_PROFILE.php?id='.$_SESSION['user_id'].'': 'first_page.php' ?>" class="index"><i class="fa fa-universal-access" aria-hidden="true"></i> Mismatch</a></h2>
  <div id="search-div">
    <!-- <i class="fa fa-search" aria-hidden="true"></i> -->
    <form action="index.php" method="get">
    <input type="search" id="search-input" name="usersearch" placeholder="Поиск" required>
    <button id="search_button"><i class="fa fa-search" aria-hidden="true"></i></button>
    <!-- <a href="index.php"><i class="fa fa-heart" aria-hidden="true"></i></a><i class="fa fa-comments" aria-hidden="true"></i> -->
    </form>
  </div>

  <?php 
  if (isset($_SESSION['username'])) { ?>
    <div id="user"><?php echo $_SESSION['username'] ?>
    <i class="fa fa-caret-down" aria-hidden="true"></i></i></a></i>
    <div id="profile_menu">
      <a href="<?php echo 'PATTERN_VIEW_PROFILE.php?id='.$_SESSION['user_id'] ?>"><i class="fa fa-home" aria-hidden="true"></i> Моя страница</a>
      <a href="editprofile.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Редактировать</a>
      <a href=""><i class="fa fa-cog" aria-hidden="true"></i> Настройки</a>
      <a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Выход</a>
    </div>
  </div>
  <?php } ?>    
  


  </div>
  </header>