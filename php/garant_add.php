<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Поручители</title>
</head>
<body>
<form id="main_form" action="garant_ins.php" method="post">
    Выберите поручителя<select name="ofv_uch_name" id="ofv_uch_name">
<?php
include "connect.php";
    foreach($db->query(
    	"SELECT  id, name 
         FROM uch
         ORDER BY name"
    ) as $row){
         echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }             
?>
<br><input value="Добавить поручителя" type="submit">
<input type="hidden" id="base_debt_acc" name="base_debt_acc" value="<?=$_GET['acc_id']?>">
</form>
</body>
</html>
