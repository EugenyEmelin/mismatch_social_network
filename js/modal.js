window.onload = function() {
	var btn_login = document.getElementById("login_btn");
	var btn_sign = document.getElementById("sign_btn");
	var modal_login = document.getElementById("modal_login");
	var modal_signup = document.getElementById("modal_signup");
	var close_1 = document.getElementsByClassName("fa-times")[0];
	var close_2 = document.getElementsByClassName("fa-times")[1];
	var first_page_wrape = document.getElementById("first_page_wrape");
	
	btn_login.onclick = function() {
		modal_login.style.display = "block";
		modal_signup.style.display = "none";
	}
	btn_sign.onclick = function() {
		modal_signup.style.display = "block";
		modal_login.style.display = "none";
		// first_page_wrape.style.filter = "blur(10px)";
	}
	close_1.onclick = function() {
		modal_login.style.display = "none";
	}
	close_2.onclick = function() {
		modal_signup.style.display = "none";
	}	
	window.onclick = function(event) {
		if (event.target == modal_login) {
			modal_login.style.display = "none";
		}
	}
}

