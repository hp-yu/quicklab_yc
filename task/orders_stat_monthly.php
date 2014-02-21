<?php
include('..\include\includes.php');
$db_conn=db_connect();
$subject="Monthly orders statistics report, from Quicklab";
//group by month this year
$message="<table><tr><td>Group by month this year:</td></tr></table>
<table border='1' cellspacing='1' cellpadding='1' >
<tr>
<td>Year</td>
<td>Month</td>
<td>Sum(price)</td>
</tr>";
$query="SELECT sum( price ) AS sum_price
FROM orders
WHERE YEAR(create_date)=YEAR(CURDATE()) AND MONTH(create_date)<>MONTH(CURDATE()) AND cancel=0";
$rs=$db_conn->query($query);
$match=$rs->fetch_assoc();
$sum_price_this_year=$match['sum_price'];
$query="SELECT YEAR( create_date ) AS year , MONTH( create_date ) AS month, sum( price ) AS sum_price
FROM orders
WHERE YEAR(create_date)=YEAR(CURDATE()) AND MONTH(create_date)<>MONTH(CURDATE()) AND cancel=0
GROUP BY YEAR( create_date ) , MONTH( create_date )";
$rs=$db_conn->query($query);
while($match=$rs->fetch_assoc()){
	$message.="<tr>";
	$message.="<td>{$match['year']}&nbsp;</td>";
	$message.="<td>{$match['month']}&nbsp;</td>";
	$message.="<td>".number_format($match['sum_price'],0)."&nbsp;</td>";
	$message.="</tr>";
}
$message.="<tr><td>&nbsp;</td><td>&nbsp;</td><td>".number_format($sum_price_this_year,0)."</td></tr>";
$message.="</table>";
//group by materials last month
$message.="<table width='100%'><tr><td>Group by materials last month:</td></tr></table>";
$message.="<table border='1' cellspacing='1' cellpadding='1' >
<tr>
<td>Module name</td>
<td>Sum(price)</td>
</tr>";
$query="SELECT sum(price) AS sum_price from orders
WHERE EXTRACT( YEAR_MONTH FROM create_date) = EXTRACT(YEAR_MONTH FROM DATE_ADD(curdate(),INTERVAL -1 MONTH)) AND cancel=0";
$rs=$db_conn->query($query);
$match=$rs->fetch_assoc();
$sum_price_last_month=$match['sum_price'];
$query="SELECT a.name AS module_name,sum(b.price) AS sum_price from modules a,orders b
WHERE a.id=b.module_id AND EXTRACT( YEAR_MONTH FROM b.create_date) = EXTRACT(YEAR_MONTH FROM DATE_ADD(curdate(),INTERVAL -1 MONTH)) AND b.cancel=0
GROUP BY b.module_id
ORDER BY sum_price DESC";
$rs=$db_conn->query($query);
while($match=$rs->fetch_assoc()){
	$message.="<tr>";
	$message.="<td>{$match['module_name']}&nbsp;</td>";
	$message.="<td>".number_format($match['sum_price'],0)."&nbsp;</td>";
	$message.="</tr>";
}
$message.="<tr><td>&nbsp;</td><td>".number_format($sum_price_last_month,0)."</td></tr>";
$message.="</table>";
//orders details
$query="SELECT COUNT(*) AS num_rows from orders
WHERE EXTRACT( YEAR_MONTH FROM create_date) = EXTRACT(YEAR_MONTH FROM DATE_ADD(curdate(),INTERVAL -1 MONTH)) AND cancel=0";
$rs=$db_conn->query($query);
$match=$rs->fetch_assoc();
$num_items_last_month=$match['num_rows'];
$message.="<table width='100%'><tr><td>Orders items last month, totally $num_items_last_month items:</td></tr></table>";
$message.="<table border='1' cellspacing='1' cellpadding='1' >
<tr>
<td>ID</td>
<td>Module name</td>
<td>Trade name</td>
<td>Price</td>
<td>Create by</td>
<td>Create date</td>
<td>Note</td>
</tr>";
$query="SELECT a.id,b.name AS module_name,a.trade_name,a.price,c.name AS created_by,a.create_date,a.note FROM orders a,modules b,people c
WHERE a.module_id=b.id AND a.created_by=c.id AND a.cancel=0 AND EXTRACT( YEAR_MONTH FROM a.create_date) = EXTRACT(YEAR_MONTH FROM DATE_ADD(curdate(),INTERVAL -1 MONTH))
ORDER BY a.price DESC";
$rs=$db_conn->query($query);
$n=1;
while($match=$rs->fetch_assoc()){
	$message.="<tr>";
	$message.="<td>$n</td>";
	$message.="<td>{$match['module_name']}&nbsp;</td>";
	$message.="<td>{$match['trade_name']}&nbsp;</td>";
	$message.="<td>".number_format($match['price'],0)."&nbsp;</td>";
	$message.="<td>{$match['created_by']}&nbsp;</td>";
	$message.="<td>".date("Y-m-d",strtotime($match['create_date']))."&nbsp;</td>";
	$message.="<td>{$match['note']}&nbsp;</td>";
	$message.="</tr>";
	$n++;
}
$message.="</table>";
//send mail
$mail= new Mailer();
$mail->basicSetting();
$mail->Subject =$subject;
$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
$mail->MsgHTML($css.$message);
$mail->AddAddress("buddyfred@126.com");
$mail->AddAddress("jli@mail.shcnc.ac.cn");
$mail->AddAddress("jyli@mail.shcnc.ac.cn");
@$mail->Send();
?>