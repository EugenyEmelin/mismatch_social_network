<?php
//Выаод заголовка страницы
  $page_title = 'Там, где противоположности сходятся';
  require_once('header.php');
  require_once('get_user_data.php'); 

  if ($user_found) {
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
						<?php 
							if (!empty($city || $country)) echo '<i class="fa fa-map-marker" aria-hidden="true"></i>';
							if (!empty($city)) echo "$city, "; 
							if (!empty($country)) echo $country; 
						?>
					</p>
						<?php 
						if (!empty($reg_date)) {
							echo "<p class=\"contact_row\"><i class=\"fa fa-calendar-check-o\" aria-hidden=\"true\"></i> Дата регистрации: $reg_date</p>";
						}
							
						?>
					<p class="contact_row">
						<?php 
						if (!empty($email)) echo '<i class="fa fa-envelope" aria-hidden="true"></i> '.$email;
						?>
					</p>
	
					<br>
					<div class="contact_row">
						<?php  
							if ((!empty($vk))) echo "<i class=\"fa fa-vk\" aria-hidden=\"true\"></i> <a target=\"_blank\" class=\"social_links\" href=\"$vk\">$vk</a>";
						?>
					</div>
					<div class="contact_row">
						<?php  
							if ((!empty($github))) echo "<i class=\"fa fa-github\" aria-hidden=\"true\"></i> <a target=\"_blank\" class=\"social_links\" href=\"$github\">$github</a>";
						?>
					</div>
					<div class="contact_row">
						<?php  
							if ((!empty($fb))) echo "<i class=\"fa fa-facebook\" aria-hidden=\"true\"></i> <a target=\"_blank\" class=\"social_links\" href=\"$fb\">$fb</a>";
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
} else {
	echo "<br><br><br>Пользователь не найден";
}
?>
</html>