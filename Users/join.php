<?php
    
session_start();

function test_input($data) 
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if(isset($_SESSION['login_user_connect_club']) && isset($_REQUEST['val'])){
    
    require_once("../../../Database/dbconnect_chat.php");
    $dts = explode("&",base64_decode($_SESSION['login_user_connect_club']));
    $id = $dts[0];
    $name = $dts[2];
    $vkey = $_REQUEST['val'];
    
    $theme_color = "blue";
    
    $qry = "SELECT theme_color FROM((users INNER JOIN theme_user ON users.u_id = theme_user.u_id) INNER JOIN web_theme ON theme_user.theme_id = web_theme.theme_id) WHERE users.u_id = $id";
    if($data = $conn->query($qry)){
        
        $result = $data->fetch_assoc();
        $theme_color = $result['theme_color'];
        
    }
    
    $q = "SELECT verified FROM users WHERE u_id = $id";
    
    if($d = $conn->query($q)){
        
        $rslt = $d->fetch_assoc();
        
        if($rslt['verified'] == '1'){
            define("TITLE", "JoinClub | ConnectClub");
            include("../Commen/header.php");
            
            $query = "SELECT club_id, club_name, club_password, admin_id, club_type FROM chat_clubs WHERE vkey = '$vkey'";
            if($data = $conn->query($query)){
                
                $result = $data->fetch_assoc();

?>
<div class="w3-modal" id="editun">
    <div class="w3-modal-content w3-animate-zoom w3-card-4" style="max-width:500px">
      <header class="w3-container w3-<?php echo $theme_color ?>"> 
        <span onclick="document.getElementById('editun').style.display='none'" 
        class="w3-button w3-display-topright w3-xlarge">&times;</span>
        <h2>My Account</h2>
      </header>
      <form class="w3-container">
<?php
    $query1 = "SELECT u_name FROM users WHERE u_id = $id";
    
    if($data1 = $conn->query($query1)){
        
        $resultx = $data1->fetch_assoc();
?>
        <div class="w3-container w3-margin w3-padding w3-large">
            <center>
        		<div class="w3-text-red" id="color-error"></div>
        		<div class="loader" id="color-loader" style="display:none"></div>
            </center>
            <b>Choose Theme:</b> <span class="w3-badge w3-blue w3-text-blue w3-margin-left kel-hover-2" onclick="updatetheme('blue')">a</span>
            <span class="w3-badge w3-green kel-hover-2 w3-text-green" onclick="updatetheme('green')">a</span>
            <span class="w3-badge w3-red kel-hover-2 w3-text-red" onclick="updatetheme('red')">a</span>
            <span class="w3-badge w3-pink kel-hover-2 w3-text-pink" onclick="updatetheme('pink')">a</span>
            <span class="w3-badge w3-indigo kel-hover-2 w3-text-indigo" onclick="updatetheme('indigo')">a</span>
        </div>
        <hr>
        <center>
        	<div class="w3-text-red" id="up-error"></div>
        	<div class="loader" id="up-loader" style="display:none"></div>
        </center>
        <table class="w3-table w3-margin-top">
            <tr>
                <td class="w3-large" style="text-align:right;vertical-align: middle;">Username: </td>
                <td><input class="w3-border w3-input" id="u_name" value="<?php echo $resultx['u_name'] ?>"></td>
            </tr>
        
             <tr style="margin-top:30px;">
                <td class="w3-large" style="text-align:right;vertical-align: middle;">Current Pass: </td>
                <td><input class="w3-border w3-input" id="pass" type="password" placeholder="Current Password"></td>
            </tr>
            <tr>
                <td class="w3-large" style="text-align:right;vertical-align: middle;">New Pass: </td>
                <td><input class="w3-border w3-input" id="newPas" type="password" placeholder="New Password"></td>
            </tr>
            <tr>
                <td class="w3-large" style="text-align:right;vertical-align: middle;">Again new Pass: </td>
                <td><input class="w3-border w3-input" id="newAga" type="password" placeholder="New Password Again"></td>
            </tr> 
            
        </table>
<?php
    }
    else{
        echo "something went wrong";
    }
?>
        <hr>
        <button type="button" class="w3-button w3-right w3-margin-bottom w3-margin-left w3-border w3-round w3-<?php echo $theme_color ?>" onclick="updateProfile()"><i class="fa fa-pencil-square-o"></i> Update</button>
        <button type="button" onclick="document.getElementById('editun').style.display='none'" class="w3-button w3-right w3-margin-bottom w3-border w3-round"><i class="fa fa-times"></i> Cancel</button>
        
      </form>
      
    </div>
</div>
<?php
if($result['club_type'] == "private"){
?>
<center>
<div class="w3-light-gray w3-padding w3-margin w3-content">
<div class="w3-center w3-margin-top">Club '<?php echo $result['club_name'] ?>' welcomes you here. Just fill the password and join the club.</div>
<form id="join" method="post">
    <center>
    	<div class="w3-text-red" id="error"></div>
    	<div class="loader" id="loader" style="display:none"></div>
    </center>
<div class="w3-section">
    <input class="w3-input w3-border w3-center" id="club_pass" placeholder="Club password" type="password" required>
</div>
</form>
<div class="w3-section">
    <button class="w3-button w3-<?php echo $theme_color ?> kel-hover-2" onclick="joinPass()">Join Club</button>
</div>
</div>
</center>
<script src="Js/varified.js"></script>
<script>
    let joinPass = () => {
        
        let u_id = <?php echo $id ?>;
        let club_id = <?php echo $result['club_id'] ?>;
        let pass = document.getElementById('club_pass').value;
        
        if(pass == ""){
            alert("Please enter password");
            return;
        }
        
        let str = "u_id="+u_id+"&club_id="+club_id+"&pass="+pass;
        let xhttp = new XMLHttpRequest();
        let loader = document.getElementById('loader');
        let error = document.getElementById('error');
        
        xhttp.onreadystatechange = function() {
        	loader.style.display = "block";
        	if(this.readyState == 4 && this.status == 200){
        		error.innerHTML = this.responseText;
        		loader.style.display = "none";
        		if(this.responseText == ""){
        		    alert("Welcome to the <?php echo $result['club_name'] ?> Club");
        		    let x = document.getElementById('join');
<?php
$club_id = $result['club_id'];
$club_name = $result['club_name'];
$admin_id = $result['admin_id'];
$url = base64_encode($id."&".$club_id."&".$club_name."&".$admin_id);
?>
        		    location.replace('chat_club?name=<?php echo $url ?>')
         		}
        	}
        }
        xhttp.open("POST", "joinChatClub", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(str);
        
    }
</script>
<?php
}
else if($result['club_type'] == "public"){
    
    $club_id = $result['club_id'];
    $club_name = $result['club_name'];
    $admin_id = $result['admin_id'];
    $url = base64_encode($id."&".$club_id."&".$club_name."&".$admin_id);

?>
<script>
let joinPublic = () => {
    
    let u_id = <?php echo $id ?>;
    let club_id = <?php echo $result['club_id'] ?>;
    
    let str = "u_id="+u_id+"&club_id="+club_id;
    let xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function() {
    	
    	if(this.readyState == 4 && this.status == 200){
    		
    		if(this.responseText == ""){
    		    alert("Welcome to the <?php echo $result['club_name'] ?> Club");
    		    let x = document.getElementById('join');
     		    location.replace('chat_club?name=<?php echo $url ?>')
     		}
     		else{
     		    alert(this.responseText);
     		}
    	}
    }
    xhttp.open("POST", "joinPublic", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(str);
    
}
joinPublic();
</script>
<?php
    
}
else{
    echo "<div class='w3-center w3-large w3-margin w3-padding'>Something went wrong</div>";
}
?>
</body>
</html>
<?php
                
            }
            else{
                die("Something went wrong");
            }
        
        }
        else{
            header("Location:../logout.php");
        }
    
    }
}
else if(isset($_SESSION['login_user_connect_club'])){
    echo "Please, login first";
}
else{
    header("Location:../logout");   
}
    
?>
