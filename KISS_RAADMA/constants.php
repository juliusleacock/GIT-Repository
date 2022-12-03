<?php 
	/*constants.php*/

	/*** define connection information ***/
	define("HOST_LOCAL", "BG-MISLAP-01-02");
	define("HOST_LOCAL_USERNAME" , "sa");
	define("HOST_LOCAL_PASSWORD", "May1989");

	define("HOST_HSL_GP", "10.1.10.21\BESTSQL");
	define("HOST_HSL_GP_USERNAME", "sa");
	define("HOST_HSL_GP_PASSWORD", "dbhack3r!");

	define("HOST_BEST_LIVE", "10.1.10.21\BESTSQL");
	define("HOST_BEST_LIVE_USERNAME", "sa");
	define("HOST_BEST_LIVE_PASSWORD", "dbhack3r!");

	/** define connection type **/
	define("LOCAL_SERVER_CONNECTION" , "LOCAL_SERVER_CONNECTION");
	define("HSL_GP_SERVER_CONNECTION", "HSL_GP_SERVER_CONNECTION");
	define("BEST_LIVE_SERVER_CONNECTION", "BEST_LIVE_SERVER_CONNECTION");

	/***Company Names ***/
	define("BBC_COMPANY", "BBC");
	define("HSL_COMPANY", "HSL");
	define("KBC_COMPANY", "KBCL");
	define("BGS_COMPANY", "BGS");
	/*end of company names*/
	
	//LDAP Server
	define("LDAP_SERVER", "bgl.local");

	//domain in use
	define("DOMAIN_IN_USE" , KBC_COMPANY);


	/***define domain constant***/
	define("DOMAIN", '@kissbaking.com'); //need to be changed for other companies
	 // define("DOMAIN", '@bermudezbiscuit.com');
	


	/***define database names***/
	define("HSL_DB", "KBCL_COPY");
		  /*define tables and views */
		  define("HSL_WEEKLY_PAID_EMP_VIEW" , "KBCLWeeklyEmployees");
		  define("HSL_SHIFT_NUMS_TBL", "SHIFT_NUMBERS");
		  define("HSL_DEPARTMENT_TBL" , "UPR40300");
		  define("HSL_EMPLOYEE_TBL", "UPR00100");
		  		/*Main dept for late mins */
		  		/*Plant*/
			/*	define("SNACK_PROCESSING_WRKS_1", "OPSPDI"); //SNK-PROCESSING(Dir.)
				define("SNACK_PROCESSING_WRKS_2", "OPSMDI"); //SNK-MAC.OPER(Direct)
				define("PRIMARY_AND_SECONDARY_PACK_WRKS", "OPSGDI"); //SNK-G/WKRS (Dir.)
				define("NUTS_WRKS_1", "OPNGDI"); //NUTS-G/WRKS-(Dir.)
				define("NUTS_WRKS_2", "OPNMDI"); //NUTS-MAC.OPERATORS     
				define("NUTS_WRKS_3", "OPNPID"); //NUTS-PROCESSING
				define("POPCORN_WRKS_1", "OPPGDI"); //POPCORN-G/WRKS (Dir)
				define("SANITATION_AND_MAINTENANCE_WRKS_1", "OPMTID"); //OPS-MAINT./ENGINEERING
				define("SANITATION_AND_MAINTENANCE_WRKS_2", "OPSANI"); //OPS-SANITATION
				define("SANITATION_AND_MAINTENANCE_WRKS_3", "OPSGID");
				//Supply Chain
				define("FINISHED_GOODS_WRKS_1", "TOEFGS"); //T/OPT-EXPORT F/GOODS
				define("FINISHED_GOODS_WRKS_2" , "TOLFGS"); //T/OPT-LOCAL F/GOODS
				define("FINISHED_GOODS_WRKS_3", "TOPFPK"); //T/OPT - FUN PACK
				define("RAW_MATERIALS_WRKS", "SCRMWH"); //S/CHAIN-R/M WHOUSE
		  /*end of tables and views */
	define("RAADMA_DB", "KBC_RAADMA_LIVE"); //KISS_RAADMA_LIVE vs RAADMA_TEST
		/*define tables and views */
		define("RAADMA_LIVE_USER_ACCOUNTS_TBL", "USER_ACCOUNTS");
		define("RAADMA_LIVE_USER_MODULES_TBL", "USER_MODULES");
		define("RAADMA_LIVE_ACCESS_RIGHTS_TBL", "ACCESS_RIGHTS");
		define("RAADMA_LIVE_MODULES_TBL", "MODULES");
		define("RAADMA_LIVE_COMPANY_TBL", "COMPANY");
		define("RAADMA_LIVE_YES_NO_TBL", "YES_NO");
		define("RAADMA_LIVE_SHIFT_NUMS_TBL" , "SHIFT_NUMBERS");
		define("RAADMA_LIVE_LEAVE_TYPES_TBL", "LEAVE_TYPES");
		define("RAADMA_LIVE_HSL_HR_EMPLOYEE_LEAVE_LOGS_TBL" , "EMPLOYEE_LEAVE_LOGS");


		/*end of tables and views*/


	/***Company db IDs***/
	define("BBC_COMPANY_ID", 1);
	define("HSL_COMPANY_ID", 2);
	define("KBCL_COMPANY_ID", 3);

	define("COMPANY_ID_IN_USE", KBCL_COMPANY_ID);
	/*End of company db ids*/
	/***************/


	/*** Main Module Names For menu***/
	define ("HR_MODULE", serialize (array ("HR_LATE_MINS_WEEKLY_PAID_EMPLOYEES")));
	define ("ADMIN_MODULE", serialize (array ("CONTROL_PANEL_MOD")));
	/*End of main module names for menu*/

	/*************/


	/*** Define Access Rights ***/
	define("ADMIN", "ADMIN");
	define("SUPER_EDITOR", "SUPER EDITOR");
	define("EDITOR", "EDITOR");
	define("GUEST", "GUEST");



?>