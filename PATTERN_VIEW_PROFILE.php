<?php
//Выаод заголовка страницы
  $page_title = 'Там, где противоположности сходятся';
  require_once('header.php'); 

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

  // Grab the profile data from the database
  if (!isset($_GET['id'])) {
    $query = "SELECT * FROM MISMATCH_USER WHERE ID = '" .$_SESSION['id']. "'"; 
  } else {
    $query = "SELECT * FROM MISMATCH_USER WHERE ID = '" .$_GET['id']. "'"; 
  }
  $data = mysqli_query($dbc, $query);

  if (mysqli_num_rows($data) == 1) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);
    (is_file(MM_UPLOADPATH . $row['Фото']) && filesize(MM_UPLOADPATH . $row['Фото']) > 0) ? 
        $photo = MM_UPLOADPATH . $row['Фото'] : 
        $photo = MM_UPLOADPATH . 'nopic.jpg';

 ?>

<body>
	<main>
		<div id="column_wrap_1">
			<div id="avatar_container">		
					<div id="avatar_wrape">
						<img class="avatar" src="<?php echo $photo; ?>" alt="">
					</div>
			</div>

			<!-- КОНТАКТЫ -->
			<div id="contacts_wrape">
				<p class="page_name"><?php echo $row['Имя'].' '.$row['Фамилия']; ?></p>
				<small>веб-разработчик PHP, JavaScript, node.js, React.js, Angular 2, jQuery</small> 
				<p class="contact_row">
					<i class="fa fa-map-marker" aria-hidden="true"></i>
					<?php 
						if (!empty($row['Город'])) echo $row['Город']; 
						if (!empty($row['Страна'])) echo ', '.$row['Страна']; 
					?>
				</p>
				<p class="contact_row">
					<i class="fa fa-calendar-check-o" aria-hidden="true"></i>
					<?php 
						if (!empty($row['Дата'])) echo 'Дата регистриции: '.$row['Дата'];
					?>
				</p>
				<p class="contact_row">
					<i class="fa fa-envelope" aria-hidden="true"></i>
					<?php 
						echo 'emelin.eug@ya.ru';
					?>
				</p>

				<br>
				<div class="contact_row">
					<i class="fa fa-vk" aria-hidden="true"></i>
					<?php  
						echo '<a class="social_links" href="https://vk.com/id17150827">vk.com/id17150827</a>';
					?>
				</div>
				<div class="contact_row">
					<i class="fa fa-github" aria-hidden="true"></i>
					<?php  
						echo '<a class="social_links" href="https://github.com/EugenyEmelin">github.com/EugenyEmelin</a>';
					?>
				</div>
				<div class="contact_row">
					<i class="fa fa-facebook" aria-hidden="true"></i>
					<?php  
						echo '<a class="social_links" href="https://www.facebook.com/">https://www.facebook.com/</a>';
					?>
				</div>
				<p class="contact_row"></p>
				<p class="contact_row"></p>
				<p class="contact_row"></p>
				<p class="contact_row"></p>
			</div>
			
		</div>
		<div id="column_wrap_2">
				
		</div>
	</main>
</body>
<?php 
} 
?>
</html>