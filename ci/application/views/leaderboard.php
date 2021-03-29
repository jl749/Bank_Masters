<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');	//MIGHT NEED IT
$this->load->library('session');
?><!DOCTYPE html>
<style>
#wrapper {
    margin-left:auto;
    margin-right:auto;
    width:1900px;
}

.modal {
  display: none; 
  position: fixed; 
  z-index: 1; 
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4); 
}
.modal-content {
  background-color: #fefefe;
  margin: 20% auto; 
  padding: 20px;
  border: 1px solid #888;
  width: 250px;
  height: 150px;
}

#textbox {
margin-top: 10px;
}
	
.close {
  color: #aaa;0
  margin-right: 500px;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}


h1,h2,h3{
    text-align: center;
    text-decoration: underline;
}    
    
.left{
    float: left;
    width: 80vh;
}
.right{
    float: right;
    width: 80vh;
}
    
.imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
    border: 1px solid black;
}

.container{
    border: 1px solid black; 
    height: 75vh;
    width: 89%;
    float: right;
    min-height: 700px;
    }

img.Banner {
    width: 35%;
}

.buttonpanel{
    border: 1px solid black;
    width: 10%;
    height: 75vh;
    min-height: 700px;
}
h1{
    text-align: center;
    text-decoration: none;
    font-size: 40px;
    font-family: 'FreeMono', monospace;
	
}

table {
    font-family: 'FreeMono', monospace;
    border-collapse: collapse;
    margin: 0 auto;
    text: center;
    width: 500px;
    height: 50px;
    border: 0.5px solid #a69d9e;
    
}
th {
    background-color: whitesmoke;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    border: 0.5px solid #a69d9e
}
td {
    background-color: #ffffff;
    box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.1);
    text: center;
}

button {
    background-color: whitesmoke;
    color: black;
    padding: 14px 20px;
    margin: 8px 8px;
    border: 1px solid black;
    cursor: pointer;
    width: 90%;
}

button:hover {
    opacity: 0.8;
}  

.balance{
    background-color: lightslategray;   
}

.lb{
    background-color: darkgray
}
.info{
    text-align: center;
    text-decoration: none;
    font-size: 20px;
    font-family: 'FreeMono', monospace;

}

</style>
<html>
<div id ="wrapper">
<div class="imgcontainer">
<img src="<?php echo base_url('images/Banner.png'); ?>"  alt="Banner" class="Banner">
</div>

<body>
<div id="myModal" class="modal">
<div class="modal-content">
<span class="close">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &times;</span>
<?php echo form_open('Main/addFunds', 'id="balanceform"');
echo "<p>Please input the amount you'd like to top up your balance</p>";
echo form_input('amount', '1','id = "textbox"');
echo form_submit('submit','Add Funds','id = "fundsbutton"'); 
echo form_close();
?>
</div>
</div>

<script>
</script>
<title>LB</title>
<div class = "container">
<h1>Leader Board</h1>
<div class= "info">
Here you can see the wealthiest users registered.
</div>
<br>

        <table>
            <tr>
                <th>Username</th>
                <th>Balance($)</th>
            </tr>
            <?php foreach ($results as $row) {
			echo"<tr>";
				echo"<td>"; echo $row['Username']; echo"</td>";
				echo"<td>"; echo $row['Balance'];  echo"</td>";
			echo"</tr>";
		} ?>
        </table>
</div>   
<div class = "buttonpanel"> 
<button class = "balance" id ="myBtn">Balance: <?php echo "&pound;" .$_SESSION['balance']; ?></button>
<br>
<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
  modal.style.display = "block";
}
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<button class = "dashboard" onclick="location.href='<?php echo base_url();?>index.php/main/dashboard'">Dash Board</button>
<br>
<button class = "lse" onclick="location.href='<?php echo base_url();?>index.php/main/lse'">NYSE</button>
<br>
<button class = "lb" onclick="location.href='<?php echo base_url();?>index.php/main/leaderboard'">Leader Board</button>
<br>
<button class = "s" onclick="location.href='<?php echo base_url();?>index.php/main/settings'">Settings</button>
<br>
<button  onclick="location.href='<?php echo base_url();?>index.php/main/logout'">Log Out</button>    
</div>
</div>
</body>
</html>