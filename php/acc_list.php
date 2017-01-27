<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Счета</title>
</head>
<table style="page-break-before: always;" width="650" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
		<td>			                    
            <pre style="text-align: left;"><a href="exit.php"><font face="Liberation Mono, monospace"><font size="2">Выход</font></font></a></pre>
		</td>
		<td>
			<pre style="text-align: left;"><a href="uch.php"><font face="Liberation Mono, monospace"><font size="2">Добавить счёт</font></font></a></pre>
		</td>
	</tr>
</table>
<?php
	include "oft_table.php";
	include "connect.php";
	oftTable::init('Счета');
	oftTable::header(array('ID','ТИП','ДАТА ОТКРЫТИЯ','ДАТА ЗАКРЫТИЯ'));
	$stmt = $db->prepare(
		"SELECT ofv_acc.id, ofv_acc_type.name
			, DATE_FORMAT( ofv_acc.creat_date, '%d-%m-%Y' ) AS creat_date
			, DATE_FORMAT( ofv_acc.clos_date, '%d-%m-%Y' ) AS clos_date
		 FROM ofv_acc, ofv_acc_type
		 WHERE ofv_acc.uch_id = ?
			AND ofv_acc.type_id = ofv_acc_type.id");
	$stmt->execute(array($_GET['uch_id']));
	while ($row = $stmt->fetch()) {
		oftTable::row(array($row['id'],$row['name'],$row['creat_date'],$row['clos_date']));
	}

	oftTable::end();
?> 
</body>
</html>
