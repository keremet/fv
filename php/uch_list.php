<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Участники</title>
</head>
<table style="page-break-before: always;" width="650" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="index.php">На главную</a>
	<td align="left"><a href="uch.php">Добавить участника</a>
</table>
<br/>
<?php
	include "oft_table.php";
	include "connect.php";
	oftTable::init('Участники');
	oftTable::header(array('ФИО','АДРЕС','ДАТА РОЖДЕНИЯ', ''));
	foreach($db->query(
			"SELECT id, name, address, to_char(birthday, 'dd-mm-yyyy') as birthday
			 FROM uch
			 ORDER BY name"
			) as $row){
	oftTable::row(array('<a href=uch.php?id='.$row['id'].'>'.$row['name'].'</a>',$row['address']
		,$row['birthday'], '<a href=acc_list.php?uch_id='.$row['id'].'>Счета</a>'));
	}

	oftTable::end();
?> 
</body>
</html>
