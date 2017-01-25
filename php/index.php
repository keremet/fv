<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Участники</title>
</head><body dir="LTR" lang="ru-RU" link="#000080" vlink="#800000">
<table style="page-break-before: always;" width="650" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
		<td>			                    
                        <pre style="text-align: left;"><a href="exit.php"><font face="Liberation Mono, monospace"><font size="2">Выход</font></font></a></pre>
		</td>
		<td>
			<pre style="text-align: left;"><a href="uch.php"><font face="Liberation Mono, monospace"><font size="2">Добавить участника</font></font></a></pre>
		</td>
		<td>
			<pre style="text-align: left;"><a href="test.php"><font face="Liberation Mono, monospace"><font size="2">Для тестирования</font></font></a></pre>
		</td>
	</tr>
</table>
<?php
	include "oft_table.php";
	include "connect.php";
	oftTable::init('Участники');
	oftTable::header(array('ID','ФИО','ТИП','АДРЕС','ДАТА РОЖДЕНИЯ','ПАСПОРТ: СЕРИЯ','НОМЕР','ДАТА ВЫДАЧИ','КЕМ ВЫДАН','ОПЕРАЦИИ'));
	foreach($db->query(
			"SELECT ofv_uch.id, ofv_uch.name, ofv_uch_type.name as type_name, ofv_uch.address, DATE_FORMAT(ofv_uch.birthday, '%d-%m-%Y') as birthday, ofv_uch.pasp_ser, ofv_uch.pasp_num, DATE_FORMAT(ofv_uch.pasp_date, '%d-%m-%Y') as pasp_date, ofv_uch.pasp_who FROM ofv_uch, ofv_uch_type
			 WHERE ofv_uch.type_id = ofv_uch_type.id
			 ORDER BY ofv_uch.id"
			 ) as $row){
		oftTable::row(array($row['id'],$row['name'],$row['type_name'],$row['address'],$row['birthday'],$row['pasp_ser'],$row['pasp_num'],$row['pasp_date'],$row['pasp_who'],'<a href=uch.php?id='.$row['id'].'>Изменить</a>'));
	}

	oftTable::end();
?> 
</p>
</body></html>
