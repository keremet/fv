<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Участники</title>
</head>
<table style="page-break-before: always;" width="650" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="uch.php">Добавить участника</a>
	<td align="left"><a href="test.php">Для тестирования</a>
</table>
<br/>
<?php
	include "oft_table.php";
	include "connect.php";
	oftTable::init('Участники');
	oftTable::header(array('ID','ФИО','АДРЕС'
			,'ДАТА РОЖДЕНИЯ','ПАСПОРТ: СЕРИЯ','НОМЕР','ДАТА ВЫДАЧИ'
			,'КЕМ ВЫДАН','ОПЕРАЦИИ'));
	foreach($db->query(
			"SELECT id, name, address, DATE_FORMAT(birthday, '%d-%m-%Y') as birthday
				, LPAD(pasp_ser, 4, '0') as pasp_ser, LPAD(pasp_num, 6, '0') as pasp_num
				, DATE_FORMAT(pasp_date, '%d-%m-%Y') as pasp_date, pasp_who 
			 FROM ofv_uch
			 ORDER BY id"
			) as $row){
	oftTable::row(array($row['id'],$row['name'],$row['address']
		,$row['birthday'],$row['pasp_ser'],$row['pasp_num'],$row['pasp_date']
		,$row['pasp_who'],'<a href=uch.php?id='.$row['id'].'>Изменить</a>'));
	}

	oftTable::end();
?> 
</body>
</html>
