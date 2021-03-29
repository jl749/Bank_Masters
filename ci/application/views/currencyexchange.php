<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');	//MIGHT NEED IT
$this->load->library('session');
?><!DOCTYPE html>
<style>

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
    }

img.Banner {
    width: 35%;
}

.buttonpanel{
    border: 1px solid black;
    width: 10%;
    height: 75vh;
}

table {
    border-collapse: collapse;
    margin: 0 auto;
    width: 500px;
    height: 50px;
    border: 1px solid black;
    
}
th {
    text-align: center;
    height: auto;
    border: 1px solid black;
}
td {
    text-align: center;
    height: auto;
    border: 1px solid black;
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

.ce{
    background-color: darkgray;
}


</style>
<html>
<div class="imgcontainer">
<img src="<?php echo base_url('images/Banner.png'); ?>" alt="Banner" class="Banner">
</div>
<body>
<script>
</script>
<title>Currency Exchange</title>
<div class = "container">
<h1>Currency Exchange</h1>
<div id = "chart"></div>
</div>   

<div class = "buttonpanel"> 
<button class = "balance" >Balance: <?php echo "&pound;" .$_SESSION['balance']; ?></button>
<br>
<button class = "dashboard" onclick="location.href='<?php echo base_url();?>index.php/main/dashboard'">Dash Board</button>
<br>
<button class = "lse" onclick="location.href='<?php echo base_url();?>index.php/main/lse'">NYSE</button>
<br>
<button class = "ce" onclick="location.href='<?php echo base_url();?>index.php/main/currencyex'">Currency Exchange</button>
<br>
<button class = "lb" onclick="location.href='<?php echo base_url();?>index.php/main/leaderboard'">Leader Board</button>
<br>
<button class = "s" onclick="location.href='<?php echo base_url();?>index.php/main/settings'">Settings</button>
<br>
<button  onclick="location.href='<?php echo base_url();?>index.php/main/logout'">Log Out</button>    
</div>

</body>
</html>