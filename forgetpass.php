<?php

session_start();
	
function test_input($data) 
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
	
?>

<!DOCTYPE html>
<html>
	<head>
	    <title>ConnectClub | Forgot password</title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" async>
		<link rel="stylesheet" href="CSS/kel.css">
		<link href="https://fonts.googleapis.com/css2?family=Literata&display=swap" rel="stylesheet"> 
		
		<style>
		
		</style>
	</head>
	<body class="w3-content w3-blue" style="max-width:700px">

<!-- First Grid: Logo & About -->
	
	<div class="w3-padding">
	    <div class="w3-center w3-xxlarge w3-padding-32">ConnectClub | forgot password</div>
	    <form id="forget" class="w3-margin w3-light-gray w3-padding-32 w3-card-4">
	        <center>
            <div class="loader" id="loader" style="display:none"></div>
            <div class="w3-text-red" id="error"></div>
            </center>
	        <div class="w3-padding">
	            Enter the backup email in the below email address. You will receive an email regarding the password recovery.
	        </div>
	        <div class="w3-section w3-padding">
	            <input type="email" class="w3-input w3-border" name="email" id="email" placeholder="email...">
	        </div>
	        <div class="w3-section w3-margin-left">
	            <button type="button" class="w3-button w3-green kel-hover-2" onclick="send()">Send</button>
	        </div>
	    </form>
	</div>
<script src="Js/check.js"></script>
<script>

let send = () => {
    
    let email = document.getElementById('email').value;
    let check = new Check();
    let error = document.getElementById('error');
    
    if(email == ""){
        
        alert("Please fill the data");
        return;
        
    }
    
    if(check.check(email)){
        alert("Please enter valid email");
		error.innerHTML = "Please enter valid email";
        return false;
    }
    
    if(!check.emailCheck(email)){
        alert("Please enter valid email");
		error.innerHTML = "Please enter valid email";
        return false;
    }
    
    let str = "email="+email;
    let xhttp = new XMLHttpRequest();
    let loader = document.getElementById('loader');
    
    xhttp.onreadystatechange = function() {
    	loader.style.display = "block";
    	if(this.readyState == 4 && this.status == 200){
    		error.innerHTML = this.responseText;
    		loader.style.display = "none";
    		if(this.responseText == ""){
    		    alert("Email sent");
    		    location.replace("index"); 
     		}
    	}
    }
    xhttp.open("POST", "sendrecovery", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(str);
    
}

</script>

	</body>
</html>
