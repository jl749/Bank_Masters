<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');	//MIGHT NEED IT
$this->load->library('session');
$this->load->library('table');
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

#tblresults {
text-align: center;
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
    overflow: auto;
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
.s{
    background-color: darkgray;
}
}


#historybutton
{
	position: relative;
	left: 42%;
	margin-bottom: 20px;
}

#exportbutton
{
	position: relative;
	left: 45%;
	margin-bottom: 20px;
}


#ddowntype
{
	position: relative;
	left: 40%;
}

#ddowndate
{
	position: relative;
	left: 50%;
}

#dbbuttons
{
    position: relative;
    left: 45%;
    width: 800px;
}
.info{
    text-align: center;
    text-decoration: none;
    font-size: 15px;
    font-family: 'FreeMono', monospace;

}
   
</style>
<html>
<div id="wrapper">
<div class="imgcontainer">
<img src= "<?php echo base_url('images/Banner.png'); ?>"  alt="Banner" class="Banner">
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

<title>Settings</title>
<div class = "container">
<h1>Settings</h1>
<h3>Update Database</h3>
<div class = "info">Due to API Limitations it is important to update the database once everyday.
Please click the two buttons below to update the database and wait for the page to load.</div>
<br>
  <div id = "dbbuttons">
        <input type="submit"  value="update DB A" onclick="window.location='../main/updateDBA';" /> 
		<input type="submit" value="update DB B" onclick="window.location='../main/updateDBB';" />
    </div>
<h3>Transaction History</h3>
<?php
$prefix = "null";

echo form_open('Main/getHistory');


$optionsT = array(
	'Stock' =>'Stock',
	'Balance' =>'Balance',
	 );
echo "<br>" .form_dropdown('TransactionType', $optionsT, 'All', 'id= "ddowntype"');

$optionsD = array(
	'7Days' => '7 Days',
	'30Days'=> '30 Days',
	'AllTime'=> 'All Transactions',
);


echo form_dropdown('TransactionDate', $optionsD, 'All', 'id= "ddowndate"');

echo "<br> <br>";


if ( isset( $_POST['history']  ) )
 {
	if($results != null && $_POST['TransactionType'] == 'Stock')
	{
	$this->table->set_heading('Transaction Type','Shares Traded', 'Price <br>per share', 'Date', 'Company');
	
	$prefix = "Stock";
	}
	elseif ($results != null && $_POST['TransactionType'] == 'Balance')
	{
	$this->table->set_heading('Transaction Type', 'Previous Balance', 'Transaction Amount', 'New Balance', 'Date');
	
	$prefix = "Balance";
	}
	else
	{
	$prefix = null;
	echo "<p id = 'tblresults'>No results found!</p>";
	}
	$template = array(
        'table_open'            => '<table border="0" cellpadding="4" cellspacing="0">',

        'thead_open'            => '<thead>',
        'thead_close'           => '</thead>',

        'heading_row_start'     => '<tr>',
        'heading_row_end'       => '</tr>',
        'heading_cell_start'    => '<th>',
        'heading_cell_end'      => '</th>',

        'tbody_open'            => '<tbody>',
        'tbody_close'           => '</tbody>',

        'row_start'             => '<tr><td>' .$prefix. '</td>',
        'row_end'               => '</tr>',
        'cell_start'            => '<td>',
        'cell_end'              => '</td>',

        'row_alt_start'         => '<tr><td>' .$prefix. '</td>',
        'row_alt_end'           => '</tr>',
        'cell_alt_start'        => '<td>',
        'cell_alt_end'          => '</td>',

        'table_close'           => '</table>'
);
	if($prefix != null)
	{	
	$this->table->set_template($template);
	echo $this->table->generate($results);
	}
 }


echo "<br> <br>" .form_submit('history', 'Return Results', 'id= "historybutton"');
echo form_submit('ehistory', 'Export Results', 'id= "exportbutton"');

echo form_close();
?>

<div id = "chart"></div>
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

</body>
</html>