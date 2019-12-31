<?php 
	//- Open an SSH connection with command line interface.
	//- Inside the folder of the composer.phar file, execute this command line to create the vendor directories :
	//- -----------------------------------------
	//- $ php composer.phar install
	//- -----------------------------------------

	/* GOOGLE CLOUD TRANSLATE AND SPEECH RECOGNITION (optional and default to french) */
	//- Add your google cloud project file to (replace the current file - keep the same name): demo/google/credentials
	//- Add your google cloud project ID to demo/google/speech/api.php
	//- Add your google cloud project ID to demo/google/translate/api.php
	//- Add your google cloud project ID to demo/google/natural_language/api.php
	
	/* INSTALLATION */
	//- Import the lexique.sql and synonyme.sql files (contains big tables)
	//- Import the import-sql.sql file
	//- Add your database credentials below
	
	/* Cron task */
	//- Build up 30 row minimums of memory for the AI
	//- Add the cron task: * * * * * cd /path/to/project/root && PHP cron-task.php 1>> /dev/null 2>&1
	
	$al_host = '';
	$al_db_name = ''; 
	$al_password = '';
	$al_user = '';
	$url = "http://www.example.ca/"; 	//- Replace with your URL
	$google_translate = 0;				//- Set to 1 to enable Google Translate
	$google_natural_language = 0;		//- Set to 1 to enable Google Natural Language
	$server_ip = '255.255.225.255';		//- Set to your server IP for cron captcha
	$google_project_id = '';			//- Set to your Google Project ID
?>