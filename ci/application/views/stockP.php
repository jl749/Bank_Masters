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

h1{
    text-align: center;
    text-decoration: none;
    font-size: 40px;
    font-family: 'FreeMono', monospace;
	
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
    height: 100vh;
    width: 89%;
    float: right;
    min-height: 900px;
    }

img.Banner {
    width: 35%;
}

.buttonpanel{
    border: 1px solid black;
    width: 10%;
    height: 100vh;
    min-height: 900px;
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

label [type="radio"]:first-child {
	margin-left: 550px;
}
.form1{
    margin-left:20px;
}
.fbutton{
    width: 80px;	
}
</style>
<html>
<title><?php echo $result;?></title>
<div id="wrapper">
<div class="imgcontainer">
<img src="<?php echo base_url('images/Banner.png'); ?>"  alt="Banner" class="Banner">
</div>
<body>
<script>var stock="<?php echo $result;?>"</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script><!--ajax API-->
<script>
$(document).ready(function(){
	<?php if ( $valid == 2 ) : ?>
		alert("Successfully bought stock");
	<?php elseif ($valid == 1): ?>
		alert("Not enough money");
	<?php elseif ($valid == 3): ?>
		alert("You do not own this stock or you do not own enough shares ");
	<?php elseif ($valid == 4): ?>
		alert("Successfully sold shares");
	<?php endif; ?>		
	$('#buy:nth-child(2)').val(stock);
    console.log(stock);
    $(".option input:first-child").prop("checked",true);//default
	var duration=$("input:radio[name='duration']:checked").val();//default
	console.log(duration);
	$(".option input").click(function(){
		duration=$("input:radio[name='duration']:checked").val();
		update(duration);
		console.log(duration);
	});
		var current_price = 0;
	var myfuncA=function calculatePriceA(){
        $priceb = $('#buy input').val()*current_price;
		$('#total_priceA').text($priceb.toFixed(2));
        $('#buy').find('input[type=hidden]').first().val(stock);
        $('#buy').find('input[type=hidden]').last().val(document.getElementById("quantityb").value * current_price);
        console.log($('#buy').find('input[type=hidden]').first().val(),$('#buy').find('input[type=hidden]').last().val());
	}
	var myfuncB=function calculatePriceB(){
        $prices = $('#sell input').val()*current_price;
		$('#total_priceB').text($prices.toFixed(2));
        $('#sell').find('input[type=hidden]').first().val(stock);
        $('#sell').find('input[type=hidden]').last().val(document.getElementById("quantitys").value * current_price);
        console.log($('#sell').find('input[type=hidden]').first().val(),$('#sell').find('input[type=hidden]').last().val());
	}
	console.log(stock);
    $.get('https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol='+stock+'&apikey=Z7RIROESK0B73148',{},
			function(data,status){
				//change label
				current_price=data["Global Quote"]["05. price"];
				$("#current_val").text("current Price (in USD): "+data["Global Quote"]["05. price"]+" ("+data["Global Quote"]["09. change"]+")   "+data["Global Quote"]["10. change percent"]);
				if(data["Global Quote"]["09. change"]>=0){
					$("#current_val").css('color','green');
				}else{$("#current_val").css('color','red');}
				$('#buy input').click(myfuncA);
				$('#buy input').keyup(myfuncA);
				$('#sell input').click(myfuncB);
				$('#sell input').keyup(myfuncB);
            }
    );
	function update(duration){
		if(duration=="TIME_SERIES_INTRADAY"){
			$.get('https://www.alphavantage.co/query?function='+duration+'&symbol='+stock+'&interval=5min&apikey=QEQYSVIAVPXJPDDS',{},
				function(data,status){
					console.log(data);
					var list=[];
					var volume=[];
					$.each(data["Time Series (5min)"],function(index,value){//json result to array
						list.push([/\d+:\d+/.exec(index)[0],parseFloat(value["3. low"]),parseFloat(value["1. open"]),parseFloat(value["4. close"]),parseFloat(value["2. high"])]);
						if(parseFloat(value["1. open"])<parseFloat(value["4. close"])){
							volume.push([/\d+:\d+/.exec(index)[0],parseFloat(value["5. volume"]),'#0f9d58']);
						}else if(parseFloat(value["1. open"])>parseFloat(value["4. close"])){
							volume.push([/\d+:\d+/.exec(index)[0],parseFloat(value["5. volume"]),'#a52714']);
						}else{
							volume.push([/\d+:\d+/.exec(index)[0],parseFloat(value["5. volume"]),'#000000']);
						}
					});
					volume.push(['Time', 'Volume', { role: 'style' }]);
					google.charts.load('current', {'packages':['corechart']});//load visualization API/corechart package
					google.charts.setOnLoadCallback(drawChart);//when API is loaded
					google.charts.setOnLoadCallback(drawVolume);
					function drawChart() {
						var data = google.visualization.arrayToDataTable(list.reverse(), true);
						var options={
							hAxis: { textPosition: 'out' },
							title:stock,
							legend:'none',
							width:$(window).width()*0.88,
							height:500,
                            candlestick: {
								//hollowIsRising:true,
                                fallingColor: { strokeWidth: 0.4, fill: '#a52714' }, // red
                                risingColor: { strokeWidth: 0.4, fill: '#0f9d58' }   // green
                            },
							colors: ['black']
						};
						var chart = new google.visualization.CandlestickChart(document.getElementById('chart'));
						chart.draw(data, options);
					}
					function drawVolume(){
						var data = google.visualization.arrayToDataTable(
							volume.reverse()
						);
						var options={
							legend:'none',
							width:$(window).width()*0.85,
							seriesType: 'bars'
						};
						var chart = new google.visualization.ComboChart(document.getElementById('vChart'));
						chart.draw(data, options);
					}
				}
			);
		}else if(duration=="TIME_SERIES_DAILY"){
			$.get('https://www.alphavantage.co/query?function='+duration+'&symbol='+stock+'&apikey=XX0A0N0T9P6ABONW',{},
				function(data,status){
					console.log(data);
					var list=[];
					$.each(data["Time Series (Daily)"],function(index,value){//json result to array
						list.push([index.substr(index.indexOf("-")+1),parseFloat(value["3. low"]),parseFloat(value["1. open"]),parseFloat(value["4. close"]),parseFloat(value["2. high"])]);
					});
					google.charts.load('current', {'packages':['corechart']});//load visualization API/corechart package
					google.charts.setOnLoadCallback(drawChart);//when API is loaded
					function drawChart() {
						var data = google.visualization.arrayToDataTable(list.reverse(), true);
						var options={
							hAxis: { textPosition: 'out' },
							title:stock,
							legend:'none',
							width:$(window).width()*0.88,
							height:500,
                            candlestick: {
                                fallingColor: { strokeWidth: 0.4, fill: '#a52714' }, // red
                                risingColor: { strokeWidth: 0.4, fill: '#0f9d58' }   // green
                            },
							colors: ['black']
						};
						var chart = new google.visualization.CandlestickChart(document.getElementById('chart'));
						chart.draw(data, options);
					}
				}
			);
		}else if(duration=="TIME_SERIES_WEEKLY"){
			$.get('https://www.alphavantage.co/query?function='+duration+'&symbol='+stock+'&apikey=Z7RIROESK0B73148',{},
				function(data,status){
					console.log(data);
					var list=[];
					var count=0;
					$.each(data["Weekly Time Series"],function(index,value){//json result to array
						list.push([index.substr(index.indexOf("-")+1),parseFloat(value["3. low"]),parseFloat(value["1. open"]),parseFloat(value["4. close"]),parseFloat(value["2. high"])]);
						count++;
						if(count==99){return false;}
					});
				
					google.charts.load('current', {'packages':['corechart']});//load visualization API/corechart package
					google.charts.setOnLoadCallback(drawChart);//when API is loaded
					function drawChart() {
						var data = google.visualization.arrayToDataTable(list.reverse(), true);
						var options={
							hAxis: { textPosition: 'out' },
							title:stock,
							legend:'none',
							width:$(window).width()*0.88,
							height:500,
                            candlestick: {
                                fallingColor: { strokeWidth: 0.4, fill: '#a52714' }, // red
                                risingColor: { strokeWidth: 0.4, fill: '#0f9d58' }   // green
                            },
							colors: ['black']
						};
						var chart = new google.visualization.CandlestickChart(document.getElementById('chart'));
						chart.draw(data, options);
					}
				}
			);
		}else if(duration=="TIME_SERIES_MONTHLY"){
			$.get('https://www.alphavantage.co/query?function='+duration+'&symbol='+stock+'&apikey=QEQYSVIAVPXJPDDS',{},
				function(data,status){
					console.log(data);
					var list=[];
					var count=0;
					$.each(data["Monthly Time Series"],function(index,value){//json result to array
						list.push([index.substr(index.indexOf("-")+1),parseFloat(value["3. low"]),parseFloat(value["1. open"]),parseFloat(value["4. close"]),parseFloat(value["2. high"])]);
						count++;
						if(count==99){return false;}
					});
				
					google.charts.load('current', {'packages':['corechart']});//load visualization API/corechart package
					google.charts.setOnLoadCallback(drawChart);//when API is loaded
					function drawChart() {
						var data = google.visualization.arrayToDataTable(list.reverse(), true);
						var options={
							hAxis: { textPosition: 'out' },
							title:stock,
							legend:'none',
							width:$(window).width()*0.88,
							height:500,
                            candlestick: {
                                fallingColor: { strokeWidth: 0.4, fill: '#a52714' }, // red
                                risingColor: { strokeWidth: 0.4, fill: '#0f9d58' }   // green
                            },
							colors: ['black']
						};
						var chart = new google.visualization.CandlestickChart(document.getElementById('chart'));
						chart.draw(data, options);
					}
				}
			);
		}
	}
	update(duration);
	if(duration=='TIME_SERIES_INTRADAY'){
		setInterval(update,60000,duration);//update every minute
	}
});
</script>
<div class = "container">
<h1><?php echo $result;?></h1>
<div id = "chart"></div>
<div id = "vChart"></div>
<label class="option">
<input type="radio" name="duration" value="TIME_SERIES_INTRADAY"> intraday
<input type="radio" name="duration" value="TIME_SERIES_DAILY"> daily
<input type="radio" name="duration" value="TIME_SERIES_WEEKLY"> weekly
<input type="radio" name="duration" value="TIME_SERIES_MONTHLY"> monthly
</label><br>
<div class="form1">
<label id="current_val"></label>
<form id='buy' method = "post" action="<?php echo base_url(); ?>index.php/Main/buyShare"><!--CALL CONTROLLER METHOD-->
    <input id = "quantityb" type="number" min = "1" name="quantityb" value= "0" >
    <input type= "hidden" name = "stockname" value =null ><!-- -stockName-->
    <input type= "hidden" name = "priceb" value =null ><!-- totalprice-->
    <label id="total_priceA">0</label><label> USD</label>
    <input type="submit" value="BUY" class = "fbutton">
</form>

    
<form id='sell' method = "post" action="<?php echo base_url(); ?>index.php/Main/sellShare"><!--CALL CONTROLLER METHOD-->
    <input id = "quantitys" type="number" min = "1" name="quantitys" value="0" >
    <input type= "hidden" name = "stockname" value =null ><!-- -stockName-->
    <input type= "hidden" name = "prices" value =null ><!-- totalprice-->
    <label id="total_priceB">0</label><label> USD</label>
    <input type="submit" value="SELL" class = "fbutton">
</form>
</div>
</div>
<div class = "buttonpanel"> 
<button class = "balance" >Balance: <?php echo "&pound;" .$_SESSION['balance']; ?></button>
<br>
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