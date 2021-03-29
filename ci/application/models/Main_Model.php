<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main_Model extends CI_Model	{
public function __construct()	{ $this->load->database(); }

public function checkLogin($username, $pass)
{
	$hash=sha1($pass);
	$this->db->where('username', $username);
	$this->db->where('password', $hash);
	$query = $this->db->get('User');
	if($query->num_rows() == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

public function checkUsername($username)
{
	$this->db->where('username', $username);
	$query = $this->db->get('User');
	if($query->num_rows() == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

public function register($email, $uName, $fName, $sName, $password)
{
	$password =sha1($password);
	$data = array(
	'Email' => $email,
	'Username' => $uName,
	'FirstName' =>$fName,
	'Surname' => $sName,
	'Password' => $password);
	$this->db->insert('User', $data);
}


// Portfolio Function
// Sum of portfolio (All stocks owned)

// Transaction List Function
// List of all transctions with all appropriate details (Amount of shares traded, price paid/received, time & date)

// Leaderboard Function

// Top profit today weekly monthly
// Best performing stocks daily, weekly, monthly
// 

// P&L Breakdown Function

// Calculates the profit and loss due to currency fluctuations and stock performance
}