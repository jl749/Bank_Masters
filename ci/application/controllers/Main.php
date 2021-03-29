<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function index(){	//http://raptor.kent.ac.uk/~jl749/ci/index.php/main
		redirect('/main/login');
	}
    
    public function buyShare()
    {
        $quantity = $this->input->post('quantityb');
        $price = $this->input->post('priceb');
        $stockname = $this->input->post('stockname');
		$fprice = floatval($price);
        $this->load->model('User_Model');
        $balance = $this->User_Model->getBalance($_SESSION['username']);
		if($balance >= $fprice)
        {
			$this->load->model('User_Model');
			$this->User_Model->buyShares($quantity,$stockname,$fprice,$_SESSION["username"]);
			$viewData=array('result'=>$stockname,'valid'=>2);
            $_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
			$this->load->view('stockP',$viewData);
        }
        else
        {
			
			$viewData=array('result'=>$stockname,'valid'=>1);
			$this->load->view('stockP',$viewData);
        }
        
    }
	
	public function sellShare()
	{
		$quantity = $this->input->post('quantitys');
        $price = $this->input->post('prices');
        $stockname = $this->input->post('stockname');
		$fprice = floatval($price);
		$this->load->model('User_Model');
		$stockowned = $this->User_Model->getStocks($_SESSION['username'],$stockname,$quantity);
		if(empty($stockowned))
		{
			$viewData=array('result'=>$stockname,'valid'=>3);
			$this->load->view('stockP',$viewData);
		}
		else
		{
			$this->load->model('User_Model');
			$this->User_Model->sellShares($quantity,$stockname,$fprice,$_SESSION["username"]);
			$viewData=array('result'=>$stockname,'valid'=>4);
			$_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
			$this->load->view('stockP',$viewData);
		}
	}
     
    public function registration()
	{
		$this->load->model('User_Model');
		$_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
        $email = $this->input->post('email');
	    $uName = $this->input->post('Uname');
	    $fName = $this->input->post('Fname');
	    $sName = $this->input->post('Sname');
	    $password = $this->input->post('psw');
        
        $this->load->model('Main_Model');
        if($this->Main_Model->checkUsername($uName) == true)
        {
            $invalid = array("valid" => 1);
            $this->load->view('registration',$invalid);
        }
        else
        {
            $this->load->model('Main_Model');
            $this->Main_Model->register($email, $uName, $fName, $sName, $password);
            redirect('/Main/Login');
        }
    }

	public function login(){
        $invalid1 = array("valid" => 0);
        $this->load->view('login',$invalid1);
	}
	public function doLogin(){
		//Model to check database
		$username=$this->input->post('uname');//pass POST values from view
        $password=$this->input->post('psw');
        $this->load->model('Main_Model');
			if($this->Main_Model->checkLogin($username, $password) == true):
                $session_data = array(
                    'username' => $username);	
                $this->session->set_userdata($session_data);//start session
				redirect('main/dashboard');
                //$this->load->view('dashboard');
			else:
				// Incorrect login data
				$this->session->set_flashdata('error', 'Invalid login credentials');
				$invalid1 = array("valid" => 1);
                $this->load->view('login',$invalid1);
			endif;
	}
	public function logout(){
        session_destroy();//close session
        redirect('/main/login');
    }
	public function dashboard(){
        //session must be true
		//call the dashboard with(username) parameter
		//need a module in order to get data from database
        if($this->session->userdata('username')){
			$this->load->model('User_Model');
			$dataA=$this->User_Model->getPortfolio($_SESSION['username']);
			$_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
			$dataB=$this->User_Model->getUserShareHistory($_SESSION['username']);
			$viewData=array("results"=>$dataA,"records"=>$dataB);
            $this->load->view('dashboard',$viewData);
        }else{
            redirect('main/login');
        }
	}
    public function lse(){
        //session must be true
		$this->load->model('User_Model');
		$dataA=$this->User_Model->getPortfolio($_SESSION['username']);
        $_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
		$this->load->view('lse');
        //load lse page
    }

    public function stockP($name){
        //session must be true
		if($this->session->userdata('username')){
			$this->load->model('User_Model');
            $_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
			$dataA=$this->User_Model->getPortfolio($_SESSION['username']);
			$viewData=array('result'=>$name,'valid'=>0);
			$this->load->view('stockP',$viewData);
		}else{
			redirect('main/login');
		}
    }
	
	public function currencyex(){
		$this->load->model('User_Model');
        $_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
        $this->load->view('currencyexchange');
    }
    
    public function leaderboard(){
		$this->load->model('User_Model');
        $_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
		$data=$this->User_Model->getLeaderboard();
		$viewData=array("results"=>$data);
        $this->load->view('leaderboard',$viewData);
    }
    
    public function settings(){
	$this->load->model('User_Model');
	$_SESSION['balance'] = $this->User_Model->getBalance($_SESSION['username']);
        $this->load->view('settings');
    }
	public function loadReg(){
		$this->load->view('registration');
	}

	public function getHistory()
	{
			$historyType = $this->input->post('TransactionType');
			$historyDate = $this->input->post('TransactionDate');
		if($this->input->post('history') == 'Return Results')
		{
			$this->load->model('User_Model');
			$data = $this->User_Model->transactionHistory($_SESSION['username'], $historyType, $historyDate);
			$viewData=array("results"=>$data);
			$this->load->view('settings', $viewData);
		}
		elseif($this->input->post('ehistory') == 'Export Results')
		{
			$this->load->model('User_Model');
			$fname = 'history_'.date('Ymd').'.csv'; 
   			header("Content-Description: File Transfer"); 
   			header("Content-Disposition: attachment; filename=$fname"); 
   			header("Content-Type: application/csv; ");
			$data = $this->User_Model->transactionHistory($_SESSION['username'], $historyType, $historyDate);
			
			$file = fopen('php://output', 'w');
			if($historyType == 'Stock')
			{
			$header = array("Amount","TradedAt","ActivityTime", "Company Name");
			} 
			elseif($historyType == 'Balance')
			{
			$header = array("Previous Balance","Transaction Amount", "New Balance", "TransactionTime");	
			}
   			fputcsv($file, $header);
   			foreach ($data as $key=>$line){ 
     			fputcsv($file,$line); 
			}
   			fclose($file); 
   			exit;
		}
		
	} 


	public function addFunds()
	{
		$this->load->library('form_validation');
    		$this->form_validation->set_rules('amount', 'AddFunds', 'required|is_natural_no_zero');
		if($this->form_validation->run())
		{
			$funds = $this->input->post('amount');
			$this->load->model('User_Model');
			$this->User_Model->updateBalance($funds, $_SESSION['username']);
			redirect('main/dashboard');
		}
		else
		{
			$this->session->set_flashdata('error', 'Please insert an appropriate value');
			redirect('main/dashboard');
		}	
	}
	public function updateDBA(){
		$this->load->model('User_Model');
		$dataB=$this->User_Model->updateStockHistoryA();
		redirect('main/dashboard');
		//$this->dashboard($_SESSION['username']);
	}
	public function updateDBB(){
		$this->load->model('User_Model');
		$dataB=$this->User_Model->updateStockHistoryB();
		redirect('main/dashboard');
	}	

}
?>