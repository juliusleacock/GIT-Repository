<?php
	/* processLogin.php */

	session_start();
	error_reporting(E_ALL ^ E_WARNING);  //ignore warnings from ldapbind
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/classes/Login.php");

	$message = "";
	$response_array = array();
     
    $data   =   $_POST["result"];
	$data   =    json_decode("$data", true);  

	$username = $data['username'];
    $password = $data['password'];
    $encrypted_password = sha1($password);

   // $username = 'kevin.cooper';
   // $password = 'July_365';

    $mis_access = false;

    if(($username == 'ADMIN' || $username == 'MIS') && $encrypted_password == sha1('Baker12')){
	   	$mis_access = true;
		$_SESSION['email_address'] = $username;
		$_SESSION['username'] = $username;
		$_SESSION['users_id'] =  GetMISUserId();
		$message = 'none';	
	}else{
		$login = new Login($username, $password);

		$ldap_email  = $login->getUsername();     // ldap rdn or dn 
		$ldap_password = $login->getPassword();  // associated password 
		  
		// connect to ldap server 
		$ldapconn = ldap_connect(LDAP_SERVER);
		  
		if ($ldapconn) { 
		    // binding to ldap server 
			 $user_id = getUsersIdByEmail($ldap_email);
				if(strlen(trim($ldap_password)) > 0){		   
					$ldapbind = ldap_bind($ldapconn, $ldap_email, $ldap_password);
					$boolean_access_status = checkIfUserHasAccessToAHSLModule($user_id);	  
					// verify binding 
					if($ldapbind && $boolean_access_status == 1){ 
						$_SESSION['email_address'] = $ldap_email;
						$_SESSION['username'] = $username;
						$_SESSION['users_id'] =  $user_id;
						$message = 'none';	       
					}else if($ldapbind == 0){
						$message = appendMessage2($message, "Incorrect Username and/or Password!");
					}else{ 
						$message = appendMessage2($message, "You cannot access this application. No Modules assigned!");
					} 
					
				}else{
					$message = appendMessage2($message, "Password field cannot by blank");
				}
		
		}else{
			$message = appendMessage2($message, "Cannot connect to AD server");
		}
	
	}

    $response_array['message'] = $message;
	$my_json_encoded_response = json_encode($response_array);
	echo $my_json_encoded_response;


?>