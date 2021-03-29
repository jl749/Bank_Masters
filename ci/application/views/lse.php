<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');	//MIGHT NEED IT
$this->load->library('session');
?>
<!DOCTYPE html>
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

#stocks
{
   position: relative;
   left: 15%; 
   width: 1200px;
        
}

#msft
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
    
}
#csco
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
    
}
#intc
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
    
}
#aapl
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
    
}
#ibm
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
    
}
#nflx
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
    
}
#amzn
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
    
}
#nvda
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
    
}
#amd
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
}
#goog
{
    height: 100px;
    width: 100px;
    border: 1px solid black;
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
.lse{
    background-color: darkgray;
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
<title>NYSE</title>
<div class = "container">
<h1>New York Stock Exchange</h1>
<!--
MAKE IT BETTER
https://stackoverflow.com/questions/20094845/call-view-from-view-on-button-click
-->
<div id = "stocks">
    <input id= "msft" type="image" src="<?php echo base_url('images/msft.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/MSFT'"/>
    <input id= "aapl" type="image" src="<?php echo base_url('images/aapl.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/AAPL'"/>
    <input id= "csco" type="image" src="<?php echo base_url('images/csco.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/CSCO'"/>
    <input id= "intc" type="image" src="<?php echo base_url('images/intc.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/INTC'"/>
    <input id= "ibm" type="image" src="<?php echo base_url('images/ibm.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/IBM'"/>
    <input id= "nflx" type="image" src="<?php echo base_url('images/nflx.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/NFLX'"/>
    <input id= "amzn" type="image" src="<?php echo base_url('images/amzn.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/AMZN'"/>
    <input id= "nvda" type="image" src="<?php echo base_url('images/nvda.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/NVDA'"/>
    <input id= "amd" type="image" src="<?php echo base_url('images/amd.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/AMD'"/>
    <input id= "goog" type="image" src="<?php echo base_url('images/goog.png'); ?>"onclick="location.href='<?php echo base_url();?>index.php/main/stockP/GOOG'"/>

</div>
<br>
<div class= "info">
Click a picture to select what stock you would like to trade. Due to API limitaitions you may recieve an error and no chart
will appear. To fix this wait a few seconds and refresh the page (could take up to a minute).
</div>
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