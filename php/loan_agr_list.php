<?php
	$uch_id = $_GET['uch_id'];
?>
<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Договоры займа</title>
</head>
<table style="page-break-before: always;" width="400" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="loan_agr_add.php?uch_id=<?=$uch_id?>">Добавить договор займа</a>
	<td align="left"><a href="index.php">Участники</a>
 </table>
        
<?php
	include "oft_table.php";
	include "connect.php";
		$stmt = $db->prepare(
		"SELECT name
		 FROM uch
		 WHERE id = ?");
	$stmt->execute(array($uch_id));	
	oftTable::init('Займы (<a href="uch.php?id='.$uch_id.'">'.$stmt->fetchColumn().'</a>)');
	oftTable::header(array('ID','ДАТА ОТКРЫТИЯ','ДАТА ЗАКРЫТИЯ','СУММА'
                              ,'СТАВКА ПО ОД','ФУФЛЫЖНАЯ СТАВКА', 'ФУФЛЫЖНЫЙ СЧЁТ'
			      , 'СЧЁТ ПРОЦЕНТОВ', 'ПРИМЕЧАНИЕ', ''));
	$stmt = $db->prepare(
           "SELECT  acc.id 
		  , to_char( acc.creat_date, 'dd-mm-yyyy' ) AS creat_date
		  , to_char( acc.clos_date, 'dd-mm-yyyy' ) AS clos_date
                  , loan_agr.sum, loan_agr.base_rate, loan_agr.fuflo_rate
                  , loan_agr.fuflo_debt_acc, loan_agr.int_acc, acc.remark 
            FROM acc
            	JOIN loan_agr ON loan_agr.base_debt_acc = acc.id
            WHERE acc.uch_id = ?");
	$stmt->execute(array($_GET['uch_id']));
	while ($row = $stmt->fetch()) {
		oftTable::row(array('<a href=loan_agr_add.php?acc_id='.$row['id'].'>'.$row['id'].'</a>'
			, $row['creat_date'], $row['clos_date'], $row['sum'], $row['base_rate'], $row ['fuflo_rate'] 
			, $row ['fuflo_debt_acc'], $row ['int_acc'], $row ['remark']
			, '<a href=ent_add.php?cr_acc='.$row['id'].'&acc_id='.$row['id'].'>Добавить приход</a>'.
			  '<br><a href=ent_add.php?deb_acc='.$row['id'].'&acc_id='.$row['id'].'>Добавить расход</a>'.
			  '<br><a href=ent_list.php?acc_id='.$row['id'].'>Движение</a>'));
	}
	oftTable::end();           
?>
</body>
</html>
