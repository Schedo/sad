<!--##########################
#      Script by: Schedo     #
#            2012            #
##########################!-->
<!DOCTYPE HTML>
<html>
<head>
  <title>APACHE</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<link rel="Stylesheet" href="css/style.css" type="text/css">
</head>
<body>
<div id="line_top"></div>
<div id="contener">
<div id="content">
	<div class="menu">
		<a href="#"><p class="logo">Lightsite</p></a> <font style="margin-bottom:3px; font-size:10px;">  &ensp; | Panel administratora</font>
		<ul>
			<li><a href="czcionki.html">Serwis</a></li>
			<li><a href="czcionki.html">Podstrony</a></li>
			<li><a href="czcionki.html">Ustawienia</a></li>
			<li><a href="czcionki.html">Wyloguj</a></li>
		</ul>
		<div style="clear:both"></div>
	</div>
	<!-- Script -->
<div class="subject">
<?php
if(file_exists("config.php")){
	echo 'System został już zainstalowany, usuń plik instalacja.php. <br> Jeśli tego nie zrobiś może to zagrozić bezpieczeństwu systemu. <br><a href=\db/index.php>Przejdź na stronę główną.</a>';
} else {
	if(isset($_POST['instaluj']) && $_POST['instaluj'] == 'OK'){
	
	$host = $_POST['host'];
	$user = $_POST['user'];
	$pw = $_POST['pw'];
	$db = $_POST['db'];
	$login = $_POST['login'];
	$password = md5($_POST['password']);
	$mysql_connect = @mysql_connect($host,$user,$pw);
	$mysql_select_db = @mysql_select_db($db, $mysql_connect);
	
if(!$mysql_connect || !$mysql_select_db || empty($login) || empty($password)){
	if(!empty($login) || !empty($password)){
	echo "Nie podałeś danych administratora.<br>"; 
	} else {
	echo "Nie udało nawiązać się połaczenia z bazą danych.<br>". mysql_error() ."</b>.<br>";
	}
	echo "<a href=\db/instalacja.php>Przejdź na stronę główną.</a>";
	exit();
} else {
	@ $config = fopen("$DOCUMENT_ROOT/../Apache/htdocs/db/config.php", 'w');
	flock($config, LOCK_EX);

	if($config){
		$out = 
		'<?php' . "\n".
		'$connection'. " = @mysql_connect('$host', '$user', '$pw')". "\n"
		."or die('Brak połączenia z serwerem MySQL.<br />Błąd: '.mysql_error());". "\n"
		."\n". 
		'$ms' . " = @mysql_select_db('$db', ". '$connection)'. "\n"
		."or die('Nie mogę połączyć się z bazą danych.<br />Błąd: '.mysql_error());". "\n"
		."mysql_close(" . '$connection);'."\n". '?>'
		;
		
		fwrite($config, $out);
		flock($config, LOCK_UN);
		
		$connection = @mysql_connect($host, $user, $pw)
		or die('Brak połączenia z serwerem MySQL.<br />Błąd: '.mysql_error());
		$db = @mysql_select_db($db, $connection)
		or die('Nie mogę połączyć się z bazą danych.<br />Błąd: '.mysql_error());
		
		// Zapytanie
		
		$zapytanie = 'CREATE TABLE config ( 
		admin_login TEXT NOT NULL, 
		admin_haslo TEXT NOT NULL
		)';
		mysql_query($zapytanie);
		$zapytanie = "INSERT INTO config 
		(admin_login,admin_haslo) VALUES ('$login','$password')";
		mysql_query($zapytanie);
		
		echo "Baza danych została skonfigurowana<br>";
		echo "<a href=\"instalacja.php\">Następny krok >></a>";
	} else {
		echo "Nie można skonfigurować bazy danych.";
		exit();
	}
}
} else {
?>
<form action="install.php" method="post">
<table>
<tr>
	<td>Host: </td><td><input type="text" name="host"></td>
</tr><tr>
	<td>Użytkownik: </td><td><input type="text" name="user"></td>
</tr><tr>
	<td>Hasło: </td><td><input type="password" name="pw"></td>
</tr><tr>
	<td>Baza danych: </td><td><input type="text" name="db"></td>
</tr><tr>
	<td><center>Ustawianie twojego konta</center></td>
</tr><tr>
	<td>Login: </td><td><input type="text" name="login"></td>
</tr><tr>
	<td>Hasło: </td><td><input type="password" name="password"></td>
</tr><tr>
	<td><input type="submit" name="instaluj" value="OK"></td>
</tr>
</table>
</form>
<?php }} ?>
</div>
</div>
</div>
<!-- Footer -->
<div id="footer">
<p class="foo">&copy;  
<?php
$date = date('Y');
if($date == '2013'){
echo '2013';
} else {
echo '2013 - '. $date;
}
?> - Michał Matysek</p>
</div>
</body>
</html>
