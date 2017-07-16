<?php
//session_start();
include 'connect.php';
$plan_acc_id = $_GET['plan_acc_id'];
$plan_donation_acc_type_id = $_GET['plan_donation_acc_type_id'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Добавить план на дату</title>
</head>
<body>
<script src="dates.js"></script>	
<script>

function saveEnt(){
	var arr_err = [];
	
	document.getElementById("exec_date_cor").value = 
		form_and_check_dat(document.getElementById("exec_date").value, arr_err);
    if(arr_err.length>0){
		alert("Ошибка в дате: "+arr_err[0]);
		document.getElementById("exec_date").focus();
		document.getElementById("exec_date").select();
		return false;
    }
    return true;
}

</script>	
<table style="page-break-before: always;" width="1000" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="uch_list.php">Участники</a>
	<td align="left"><a href="ent_list.php?acc_id=<?=$plan_acc_id?>">Движения по счету</a>
</table>
<br/>
<form id="main_form" action="fund_plan_save.php" method="post">
<table border="0" cellpadding="0" cellspacing="2">
            
<tr><td>Дата<td><input id="exec_date"  name="exec_date" size="8" type="text" 
 maxlength="8" 
onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
        
            

            
<?php
	$stmt = $db->prepare(
		"SELECT acc.id, uch.name
		FROM acc
		  JOIN uch ON acc.uch_id = uch.id
		WHERE acc.type_id = ?
		ORDER BY uch.name"	
	);
	$stmt->execute(array($plan_donation_acc_type_id));
	while($row = $stmt->fetch()){
         echo '<tr><td>'.$row['name'].'<td><input id="acc'.$row['id'].'"  name="acc'.$row['id'].'" size="30" type="text">';
    }             
?>
      
<tr><td>Назначение<td><input id="purpose"  name="purpose" size="30" type="text">  
      	           
</table>

<br><input value="Сохранить" type="submit"  onclick="return saveEnt();">

<input type="hidden" id="cr_acc_id" name="cr_acc_id" value="<?=$plan_acc_id?>">
<input type="hidden" id="plan_donation_acc_type_id" name="plan_donation_acc_type_id" value="<?=$plan_donation_acc_type_id?>">
<input type="hidden" id="exec_date_cor" name="exec_date_cor">

</form>
</body>
</html> 
