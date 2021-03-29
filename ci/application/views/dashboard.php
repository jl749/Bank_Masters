<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');	//MIGHT NEED IT
$this->load->library('session');
?><!DOCTYPE html>
<html>
<head>
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

h1{
    text-align: center;
    text-decoration: none;
    font-size: 40px;
    font-family: 'FreeMono', monospace;
	
}
h2,h3{
    text-align: center;
    text-decoration: underline;
    font-family: 'FreeMono', monospace;
}    

.left {
    float: left;
    width: 300px;
    margin-left: 100px;
    
}
.right{
    float: right;
    width: 900px;
}
    
.imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
    border: 1px solid black;
}

.container{
    border: 1px solid black; 
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
    min-height: 700px;
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
    box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.1);}

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

.dashboard{
    background-color: darkgray;
}
.message{
    font-family: 'FreeMono', monospace;

}
</style>
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
$(document).ready(function(){
	var arrayFromPHP = <?php echo json_encode($records); ?>;
	console.log(arrayFromPHP);
	var finalarr=[];
	for($i=0;$i<100;$i++){
		finalarr.push([null,0]);
	}
	$.each(arrayFromPHP,function(record){
		$($(this).get().reverse()).each(function(index,value){
			finalarr[index][0]=value.ValueDate;
			finalarr[index][1]=parseFloat(value.price)+parseFloat(finalarr[index][1]);
		});
	});
	console.log(finalarr);
	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
	function drawChart(){
		var data= new google.visualization.DataTable();
		data.addColumn('string','Time');
		data.addColumn('number','Price');
		data.addRows(finalarr.reverse());
		var options={
			//curveType: 'function',
			legend: 'none'
		};
		var chart = new google.visualization.LineChart(document.getElementById('chart'));
		chart.draw(data, options);
	}
	var val=null;
	function display_current(stock){
		$.ajax({url:'https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol='+stock+'&apikey=Z7RIROESK0B73148',async: false,
			success: function(data){
				console.log(data["Global Quote"]["05. price"]);
				val=data["Global Quote"]["05. price"];
            }
		});
	}
	var count=0;
	$('div table tr').each(function(){
		if(count==0){
			count++;
			return;
		}
		var stock=$(this).children().first().text();
		console.log(stock);
		display_current(stock);
        var x=parseFloat(val).toFixed(2);
		$(this).children().last().html(x);
		$(this).find(':nth-child(3)').html((parseFloat($(this).find(':nth-child(2)').html())*x).toFixed(2));
		count++;
	});
});
</script>
<div id ="wrapper">
<div class="imgcontainer">
<img src="<?php echo base_url('images/Banner.png'); ?>" alt="Banner" class="Banner">
</div>
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
<title>Dash Board</title>
<div class = "container">
<h1>Dash Board</h1>
    <div class= "left">
        <?php
		if($results==null)
		{
			echo "<h2>Shares Held</h2>";

			echo "<p class='message'>No shares owned. Click on NYSE to buy shares or Balance to add funds. Once you start trading your portfolio will update daily.</p>";
		}else{
			echo "<h2>Shares Held</h2>
        <br>
        <table class = sharesheld>
            <tr>
                <th>Name Of Company</th>
                <th>Shares Held</th>
                <th>Total Price($)</th>
				<th>Current Price($)</th>
            </tr>";
             foreach ($results as $row) {
			echo"<tr>";
				echo"<td>"; echo $row['TickerID']; echo"</td>";
				echo"<td>"; echo $row['num'];  echo"</td>";
				echo"<td>0</td>";
				echo"<td>0</td>
			</tr>";
		}} ?>
        </table>
    </div>
    <div class = "right">
        <h2>Portfolio</h2>
		<div id='chart' style="width: 800px; height: 500px "></div>
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