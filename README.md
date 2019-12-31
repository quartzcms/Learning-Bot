# Learning-Bot

<p>To install it follow the instructions of the package in the config file:</p>

<p>Open an SSH connection with command line interface.<br>
Inside the folder of the composer.phar file, execute this command line to create the vendor directories :</p>

<pre>$ php composer.phar install</pre>

<h4>Google Cloud Translate and Speech Recognition (optional and default to french)</h4>

<p>Add your Google Cloud project file to (replace the current file - keep the same name): demo/google/credentials<br>
Add your Google Cloud project ID to demo/google/speech/api.php<br>
Add your Google Cloud project ID to demo/google/translate/api.php<br>
Add your Google Cloud project ID to demo/google/natural_language/api.php</p>

<h4>Installation</h4>

<p>Import the lexique.sql and synonyme.sql files (contains big tables)<br>
Import the import-sql.sql file<br>
Add your database credentials below</p>

<h4>Cron task</h4>

<p>Build up 30 row minimums of memory for the AI<br>
Add the cron task:</p>

<pre>* * * * * cd /path/to/project/root &amp;&amp; PHP cron-task.php 1&gt;&gt; /dev/null 2&gt;&amp;1</pre>

<p>-----------------------------------------</p>

<pre>$al_host = '';
$al_db_name = '';
$al_password = '';
$al_user = '';
$url = "http://www.example.ca/";&nbsp;&nbsp;&nbsp;&nbsp; //- Replace with your URL
$google_translate = 0;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; //- Set to 1 to enable Google Translate
$google_natural_language = 0;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; //- Set to 1 to enable Google Natural Language
$server_ip = '255.255.225.255';&nbsp;&nbsp; &nbsp;&nbsp; //- Set to your server IP for cron captcha
$google_project_id = '';&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; //- Set to your Google Project ID</pre>
