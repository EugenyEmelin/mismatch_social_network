<?php 
  session_start(); 
  //Если переменные сессий не имеют значений ()
  if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) { #Если в куки записаны id и ник пользователя
      $_SESSION['user_id'] = $_COOKIE['user_id']; #присваиваем переменной сессии аналогичное значение куки
      $_SESSION['username'] = $_COOKIE['username'];
    }
  }
?>