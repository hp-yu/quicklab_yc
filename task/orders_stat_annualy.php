<?php
include('..\include\includes.php');
$db_conn=db_connect();
$query="SELECT * FROM `orders_mails` WHERE `key`='is_stat_annually_to_administrator'";
$result=$db_conn->query($query);
$match_orders_mails_annually=$result->fetch_assoc();
if ($match_orders_mails_annually['value']==1) {
	$subject="Annualy orders statistics report, from Quicklab";
	//group by month last year
	$message="<table><tr><td>Group by month last year:</td></tr></table>
	<table border='1' cellspacing='1' cellpadding='1' >
	<tr>
	<td>Year</td>
	<td>Month</td>
	<td>Sum(price)</td>
	</tr>";
	$query="SELECT sum( price ) AS sum_price
	FROM orders
	WHERE YEAR(create_date)=YEAR(CURDATE())-1 AND cancel=0";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	$sum_price_last_year=$match['sum_price'];
	$query="SELECT YEAR( create_date ) AS year , MONTH( create_date ) AS month, sum( price ) AS sum_price
	FROM orders
	WHERE YEAR(create_date)=YEAR(CURDATE())-1 AND cancel=0
	GROUP BY YEAR(create_date) , MONTH(create_date)";
	$rs=$db_conn->query($query);
	while($match=$rs->fetch_assoc()){
		$message.="<tr>";
		$message.="<td>{$match['year']}&nbsp;</td>";
		$message.="<td>{$match['month']}&nbsp;</td>";
		$message.="<td>".number_format($match['sum_price'],0)."&nbsp;</td>";
		$message.="</tr>";
	}
	$message.="<tr><td>&nbsp;</td><td>&nbsp;</td><td>".number_format($sum_price_last_year,0)."</td></tr>";
	$message.="</table>";
	//group by materials last year
	$message.="<table width='100%'><tr><td>Group by materials last year:</td></tr></table>";
	$message.="<table border='1' cellspacing='1' cellpadding='1' >
	<tr>
	<td>Module name</td>
	<td>Sum(price)</td>
	</tr>";
	$query="SELECT a.name AS module_name,sum(b.price) AS sum_price from modules a,orders b
	WHERE a.id=b.module_id AND YEAR(b.create_date)=YEAR(CURDATE())-1 AND b.cancel=0
	GROUP BY b.module_id
	ORDER BY sum_price DESC";
	$rs=$db_conn->query($query);
	while($match=$rs->fetch_assoc()){
		$message.="<tr>";
		$message.="<td>{$match['module_name']}&nbsp;</td>";
		$message.="<td>".number_format($match['sum_price'],0)."&nbsp;</td>";
		$message.="</tr>";
	}
	$message.="<tr><td>&nbsp;</td><td>".number_format($sum_price_last_year,0)."</td></tr>";
	$message.="</table>";
	//send mail
	$mail= new Mailer();
	$mail->basicSetting();
	$mail->Subject =$subject;
	$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
	$mail->MsgHTML($css.$message);
	//add address
	$query="SELECT * FROM orders_admin";
	$result=$db_conn->query($query);
	while ($match_orders_admin=$result->fetch_assoc()) {
		$match_orders_admin_people=get_record_from_id('people',$match_orders_admin['people_id']);
		$mail->AddAddress($match_orders_admin_people['email']);
	}
	@$mail->Send();
}
?>