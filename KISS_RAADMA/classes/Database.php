<?php

	
	ini_set('memory_limit','400M');
	date_default_timezone_set('America/Puerto_Rico');
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");

	class Database{
		private $username;
		private $password;
		private $name;
		private $host;
		private $connection;
		private $errorMessage;
		
		
		static $errorCounter = 0;
		
		
		//Migration variables
		private $source;
		private $destination;
		private $data_id;
		private $max_id;
		
		protected $message;
		private $private_class;
		
		//sets the database connection 
		public $connectionType = null;
		
		//default constructor
		public function __construct($connectionType = BEST_LIVE_SERVER_CONNECTION){


	        $this->connectionType =  $connectionType;

			if($this->connectionType == LOCAL_SERVER_CONNECTION){
				$this->host = HOST_LOCAL;
				$this->username  = HOST_LOCAL_USERNAME;
				$this->password = HOST_LOCAL_PASSWORD;
			}else if($this->connectionType ==  HSL_GP_SERVER_CONNECTION){
				$this->host = HOST_HSL_GP;
				$this->username  = HOST_HSL_GP_USERNAME;
				$this->password = HOST_HSL_GP_PASSWORD;
			}else if($this->connectionType == BEST_LIVE_SERVER_CONNECTION){
				$this->host = HOST_BEST_LIVE;
				$this->username  = HOST_BEST_LIVE_USERNAME;
				$this->password = HOST_BEST_LIVE_PASSWORD;
			}
			$this->name = NULL;
			$this->connection = NULL;
			$this->errorMessage = NULL;
			$this->source = NULL;
			$this->destination = NULL;
			$this->data_id = NULL;
			$this->max_id = NULL;
			$this->message = "";
			$this->private_class = NULL;
			
		}
		
		public function __construct1($host, $username, $password){
			$this->connectionType = null;

			$this->host = $host;
			$this->username  = $username;
			$this->password = $password;
			$this->name = NULL;
			$this->connection = NULL;
			$this->errorMessage = NULL;
			$this->source = NULL;
			$this->destination = NULL;
			$this->data_id = NULL;
			$this->max_id = NULL;
			$this->private_class = NULL;
			$this->message = "";
			 
		}
		
		public function setSource($source){
			if(isset($source)){
				$this->source = $source;
			}else{
				$this->error("setSource Function, source variable is null");	
			}
		}
		
		public function getSource(){
			return $this->source;
		}
		
		public function setDestination($destination){
			if(isset($destination)){
				$this->destination = $destination;
			}else{
				$this->error("setDestination function , destination variable is null");
				
			}
		}
		
		public function getDestination(){
			return $this->destination;
		}
		
		
		public function setDataId($data_id){
			if(isset($data_id)){
				$this->data_id = $data_id;	
			}else{
				$this->error('setDataId function , variable data_id has null values');
			}
		}
		
		public function getDataId(){
			return $this->data_id;
		}
		
		

		public function getErrorMessage(){
			$this->message = appendMessage($this->message,$this->errorMessage);
			return $this->message;
			
		}
		
		public function addErrorCount(){
			self::$errorCounter++;
		}
		
		public function updateErrorChecker($conn, $id, $error_count){
			$message = '';
			if(isset($conn) && isset($id) && isset($error_count)){
				if($error_count > 0){
					$error_count = 1;
				}else{
					$error_count = 0;
				}
				
				$checker_query_string = str_replace('[value]', $error_count, UPDATE_CHECKER );
				$checker_query_string = str_replace('[id]', $id, $checker_query_string);
				
				$this->executeQuery_2($checker_query_string, $conn);
			}else{
				$message = $this->error("function updateErrorChecker, has null values as arguments");
			}
			
			return $message;
		}

		
		
		public function privateMethodCall(){
			$message = "This is a test";
			
			$this->test($message);
			
		}
		
		public function checkTableChecker($id, $db_name){
			$conn = $this->connect_to_database_2($db_name);
			$checker_id = null;
			if(isset($id)){
				$checker_query_string = str_replace('[id]', $id, SELECT_CHECKER);
				$checker_sql = $this->executeQuery_2($checker_query_string, $conn);
				$checker_array = mssql_fetch_array($checker_sql);
				$checker_id = $checker_array[0];
			}else{
				$checker_id = $this->error("function <strong>checkTableChecker</strong>, agruments have null values");
			}
			
			return $checker_id;
		}
		
		
		
		public function connect($database_name){
			if(isset($database_name)){
				$this->connection = mssql_connect($this->host, $this->username, $this->password);	
				if($this->connection){
					$isDatabaseSelected = mssql_select_db($database_name);
					if(!$isDatabaseSelected){
						$this->connection = $this->error("function <strong>connect</strong>, Cannot connect to the database ". $database_name. " on the host ". $this->host);
					}			
				}else{
					$this->connection = $this->error("function <strong>connect</strong>, Cannot connect to the database ".$this->name. " on the host ". $this->host);
				
				}
			}else{
				 $this->connection = $this->error("function <strong>connect</strong> has a null value.");
			}
			return $this->connection;
		}//end of function connect
		
		
				
		public function connect_to_database($database_name){
			if(!empty($database_name)){
				 $this->connection = mssql_connect($this->host, $this->username, $this->password, true);
				if($this->connection){
						$isDatabaseSelected = mssql_select_db($database_name);
					if(!$isDatabaseSelected){
						$this->connection = $this->error("Cannot connect to the database <strong>". $database_name. "</strong> on the host <strong>". $this->host."</strong>");
					}
				}else{
					$this->connection = $this->error("Connection could not be established. Host, username or password could possibly be incorrect");
					
				}
				
			}else{
				$this->error("function <strong>connect_to_database_2</strong>, has null/empty");
			}
				
				return $this->connection;
		}
		
	
	

		//Tests if the connection variable is established
		public function isConnected(){
			$result = false;
			if ($this->connection) {
			  $result = true;
			}
			return $result;
		 }
		

			public function executeQuery($query, $conn){
			$result = null;
			
			if($this->isConnected()){
					if(isset($query)){
						$result = mssql_query($query, $conn);
						if(!$result){
							$this->error("Query: <strong>" .$query . "</strong> could not be executed.");
						}
					}else{
						$this->error('function <strong>executeQuery_2</strong> has possible null values');
						
					}
			
			}else{
				$this->error('Cannot connect to database');
			
			}
			
			return $result;
		}
		
	
	
		
		/* public function createMessage($message){
			if(isset($message)){
				$this->message = appendMessage($this->message, $message);
			}else{
				$this->message = '';
			}
		} */
		
		public function createMessage($message = null, $type = null){
			if(empty($message)){
				$message = $this->error('function <strong>createMessage</strong> has null values');
			}else{
				if(isset($message) && empty($type)){
					$message = $this->message = appendMessage($this->message, $message);
				}else if(isset($message) && isset($type) && $type == 'warning'){
					$message =$this->message = appendMessage($this->message, "<span style='color:red'>".$message. "</span>");
				}else if(isset($message) && isset($type) && $type == 'success'){
					$message =$this->message = appendMessage($this->message, "<span style='color:green'>".$message. "</span>");
				}
			}
			
			return $message;

		}	
		
		public function setErrorDefaults($page_type, $db_name){
			if(isset($page_type) || isset($db_name)){
				if($db_name == 'HSL_COPY' || $db_name == 'BBC_COPY'){
					$conn = $this->connect_to_database_2($db_name);
					//set update checker for CustomerComplaintReports


					if($page_type == 'hsl_qc_home.php' || $page_type =='bbc_qc_home.php'){
					$set_defaults_customer_complaint_query_string = str_replace('[value]', 1, UPDATE_CHECKER);
					$set_defaults_customer_complaint_query_string  = str_replace('[id]' , 1 , $set_defaults_customer_complaint_query_string); 
					$this->executeQuery_2($set_defaults_customer_complaint_query_string  ,$conn);
					
					}else if($page_type == 'hsl_ops_home.php' || $page_type =='bbc_ops_home.php'){
					//set update checker for operations_scrap		
					$set_defaults_operations_scrap_query_string = str_replace('[value]' , 1, UPDATE_CHECKER);
					$set_defaults_operations_scrap_query_string = str_replace('[id]' , 2, $set_defaults_operations_scrap_query_string);
					$this->executeQuery_2($set_defaults_operations_scrap_query_string, $conn);

					}	
				}
			
			}else{
				$this->error("function setErrorDefaults, has agruments that have null values");
			}
		}
		
		
		//For Error Messages
		public function error($error_message){
			$message = '';
			if(isset($error_message)){
				$this->errorMessage = "Error: ".$error_message."";
				$message = $this->getErrorMessage();
			}else{
				$message = $this->errorMessage = NULL;
			}
			
			return $message;
		}
		
		public function getColumnCount($conn, $database_name , $table_name){
			$count = NULL;
			
			if(isset($conn) && isset($database_name) && isset($table_name)){
			
				$count_query_string = str_replace("database", $database_name, GET_NUMBER_OF_TABLE_COL);
				$count_query_string = str_replace("table_name", $table_name, $count_query_string);
				
				$count_sql = $this->executeQuery_2($count_query_string, $conn);
				
				$count_array = mssql_fetch_array($count_sql);
				
				$count = $count_array[0];
			}else{
				$count =  $this->error("function columnCount, agrument has null values");
			}
			
			return $count;
			
			
		}
		
		public function getColumnValue($col, $table, $number, $conn){
			$query_string = str_replace("[col]", $col, GET_COL);
			$query_string = str_replace("[table]" , $table , $query_string);
			$query_string = str_replace("[number]" , $number, $query_string );
			
			$sql = $this->executeQuery_2($query_string , $conn);
			
			$value_array =  mssql_fetch_array($sql);
			
			return $value_array[0];
			
			
		}
		
		public function getColumnNameDifference($conn, $source_db_name, $copy_db_name, $table_name){
			$col_name_diff = NULL;
			
			if(isset($conn) && isset($source_db_name) && isset($copy_db_name) && isset($table_name)){
				$col_name_query_string = str_replace("source_db_name", $source_db_name, GET_COL_NAME_DIFF );
				$col_name_query_string = str_replace("copy_db_name" , $copy_db_name, $col_name_query_string);
				$col_name_query_string = str_replace("table_name" , $table_name, $col_name_query_string);
				
				$col_name_diff_sql = $this->executeQuery_2($col_name_query_string, $conn);
				

				$num_rows = mssql_num_rows($col_name_diff_sql);
				
				$count_rows = 0;
				while($rows = mssql_fetch_array($col_name_diff_sql)){
					if($count_rows == 0){
						$col_name_diff = "'".$rows['COLUMN_NAME']."'";
					}else if($count_rows > 0){
						$col_name_diff = "'".$rows['COLUMN_NAME'] ."' ,".$col_name_diff;
					}
					
					$count_rows++;
				}
			}else{
				$col_name_diff = $this->error("function getColumnNameDifference, argument has null values");
			}

			return $col_name_diff;
		}
		
		

		public function TestDbConnection(){
			$db_name  = "BEST";

			$conn_best = $this->connect_to_database($db_name);

			$query_string = "SELECT name FROM Modules WHERE id = 40";

			$query_executed = $this->executeQuery($query_string, $conn_best);

			$query_array = mssql_fetch_array($query_executed);

			$name = $query_array[0];

			return $name;


		}


	}//end of Database class
	

	

?>