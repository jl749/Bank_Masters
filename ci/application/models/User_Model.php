<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_Model extends CI_Model {
public function __construct() {$this->load->database();}

public function transactionHistory($username, $type, $date){
	if($type == 'Stock' && $date == '7Days')
	{
		$this->db->select('Amount, TradedAt, ActivityTime, CompanyName');
		$this->db->from('Transaction');
		$this->db->join('Company', 'Company.TickerID = Transaction.TickerID');
		$this->db->where('Username', $username);
		$where = 'ActivityTime>= DATE(NOW()) - INTERVAL 7 DAY';
		$this->db->where($where);
		$this->db->order_by('ActivityTime', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	elseif ($type == 'Stock' && $date == '30Days')
	{
			
		$this->db->select('Amount, TradedAt, ActivityTime, CompanyName');
		$this->db->from('Transaction');
		$this->db->join('Company', 'Company.TickerID = Transaction.TickerID');
		$this->db->where('Username', $username);
		$where = 'ActivityTime>= DATE(NOW()) - INTERVAL 30 DAY';
		$this->db->where($where);
		$this->db->order_by('ActivityTime', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}


	elseif ($type == 'Stock' && $date == 'AllTime')
	{
			
		$this->db->select('Amount, TradedAt, ActivityTime, CompanyName');
		$this->db->from('Transaction');
		$this->db->join('Company', 'Company.TickerID = Transaction.TickerID');
		$this->db->where('Username', $username);
		$this->db->order_by('ActivityTime', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	elseif($type == 'Balance' && $date == '7Days')
	{
			
		$this->db->select('PreviousBalance, transactionAmount, NewBalance, TransactionTime');
		$this->db->from('BalanceTransaction');
		$this->db->where('Username', $username);
		$where = 'TransactionTime>= DATE(NOW()) - INTERVAL 7 DAY';
		$this->db->where($where);
		$this->db->order_by('TransactionTime', 'DESC');
		$query = $this->db->get();
		return $query->result_array();

	}

	elseif($type == 'Balance' && $date == '30Days')
	{
		$this->db->select('PreviousBalance, transactionAmount, NewBalance, TransactionTime');
		$this->db->from('BalanceTransaction');
		$this->db->where('Username', $username);
		$where = 'TransactionTime>= DATE(NOW()) - INTERVAL 30 DAY';
		$this->db->where($where);
		$this->db->order_by('TransactionTime', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	elseif($type == 'Balance' && $date == 'AllTime')
	{
		$this->db->select('PreviousBalance, transactionAmount, NewBalance, TransactionTime');
		$this->db->from('BalanceTransaction');
		$this->db->where('Username', $username);
		$this->db->order_by('TransactionTime', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}			
}
public function getPortfolio($username)
{
	//SELECT SUM(Amount), CompanyName, Username FROM Transaction WHERE Username = 'bob'
	$sql='SELECT SUM(Amount) AS num,TickerID FROM Transaction WHERE Username=? GROUP BY TickerID HAVING SUM(Amount)>0';
	$query=$this->db->query($sql, array($username));
	return $query->result_array();
}
    
public function getBalance($username)
{
    $this->db->where('Username',$username);
    $query = $this->db->get("User");
    $balance = $query->row()->Balance;
    return $balance;
}

public function getStocks($username,$stockname,$amount)
{
	$sql = 'SELECT SUM(Amount) AS num FROM Transaction WHERE Username=? AND TickerID=? GROUP BY TickerID HAVING SUM(Amount)>= ?';
	$query=$this->db->query($sql, array($username,$stockname,$amount));
	return $query->result_array();
}

public function updateBalance($amount,$username)
{
	$this->db->trans_start();
	$this->db->query('INSERT INTO BalanceTransaction (Username, transactionAmount) VALUES ("'.$username.'", '.$amount.')');
	$this->db->query('UPDATE BalanceTransaction SET PreviousBalance = (SELECT Balance FROM User WHERE Username = "'.$username.'")WHERE PreviousBalance IS NULL');
	$this->db->query('UPDATE BalanceTransaction SET newBalance = previousBalance + transactionAmount WHERE newBalance IS NULL');
	$this->db->query('UPDATE User SET Balance = (SELECT newBalance FROM BalanceTransaction ORDER BY TransactionTime DESC LIMIT 1) WHERE Username = "'.$username.'"');
	$this->db->trans_complete();
}
 
public function sellShares($amount,$stockName,$stockPrice,$username)
{
	$tradedat = $stockPrice/$amount;
    $data = array(
        'Amount' => (0 - $amount),
        'TradedAt' => $tradedat,
        'Username' => $username,
        'TickerID' => $stockName
    );
    $this->db->insert('Transaction', $data);
	$this->updateBalance($stockPrice,$username);	
}
 
public function buyShares($amount,$stockName,$stockPrice,$username)
{
    $tradedat = $stockPrice/$amount;
    $data = array(
        'Amount' => $amount,
        'TradedAt' => $tradedat,
        'Username' => $username,
        'TickerID' => $stockName
    );
    $this->db->insert('Transaction', $data);
	$value = 0 - $stockPrice;
	$this->updateBalance($value,$username);
}

public function updateStockHistoryA()//$tid,$date,$value
{
	$query=$this->db->query('SELECT TickerID FROM Company');
	$list=$query->result_array();
	//$list=array("results"=>$query->result_array());
	for($i=0;$i<5;$i++){
		foreach($list[$i] as $id){
			$json_string='https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol='.$id.'&apikey=QEQYSVIAVPXJPDDS';
			$jsondata=file_get_contents($json_string);
			$obj=json_decode($jsondata,true);
			foreach($obj["Time Series (Daily)"] as $index=>$value){
				$data=array(
					'TickerId' => $id,
					'ValueDate' => $index,
					'Value' => $value["4. close"]
				);
				$this->db->replace('StockHistory',$data);
			}
		}
	}
}
public function updateStockHistoryB()//$tid,$date,$value
{
	$query=$this->db->query('SELECT TickerID FROM Company');
	$list=$query->result_array();
	//$list=array("results"=>$query->result_array());
	for($i=5;$i<10;$i++){
		foreach($list[$i] as $id){
			$json_string='https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol='.$id.'&apikey=XX0A0N0T9P6ABONW';
			$jsondata=file_get_contents($json_string);
			$obj=json_decode($jsondata,true);
			foreach($obj["Time Series (Daily)"] as $index=>$value){
				$data=array(
					'TickerId' => $id,
					'ValueDate' => $index,
					'Value' => $value["4. close"]
				);
				$this->db->replace('StockHistory',$data);
			}
		}
	}
}
public function getUserShareHistory($username)
{
	$sqlA='SELECT TickerID,MIN(ActivityTime) AS time,SUM(Amount) AS num FROM Transaction WHERE Username=? GROUP BY TickerID HAVING SUM(Amount)>= 0';
	$queryA=$this->db->query($sqlA,array($username));
	$finalarr=array();
	foreach($queryA->result_array() as $row){
		$sqlB='SELECT TickerID,ValueDate,Value*? as price FROM StockHistory WHERE TickerID=? AND ValueDate>?';
		$queryB=$this->db->query($sqlB,array($row['num'],$row['TickerID'],$row['time']));
		array_push($finalarr,$queryB->result_array());
	}
	return $finalarr;
}
public function getLeaderboard()
{
	$sql='SELECT Username,Balance FROM User ORDER BY Balance DESC';
	$query=$this->db->query($sql);
	return $query->result_array();
}

public function exportHistory($username)
{
		$this->db->select('*');
		$this->db->from('Transaction');
		$this->db->join('Company', 'Company.TickerID = Transaction.TickerID');
		$this->db->where('Username', $username);
		$this->db->order_by('ActivityTime', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
}

}