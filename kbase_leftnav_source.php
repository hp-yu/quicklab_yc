<?php 
include('./include/includes.php');
?>
<?php
$db_conn=db_connect();
if ($_REQUEST['root'] == "source") {
	$query = "SELECT * FROM kbase_cat WHERE pid=0";
	$result = $db_conn->query($query);
	if($result->num_rows>0) {
		echo "[";
		$i=0;
		while ($match=$result->fetch_array()) {
			$i++;
			$query = "SELECT * FROM kbase_cat WHERE pid={$match['id']}";
			if ($db_conn->query($query)->num_rows>0) {
				$expanded="true";
			} else {
				$expanded="false";
			}
			$query = "SELECT * FROM kbase WHERE `cat_id`={$match['id']}  ORDER BY name";
			$num_articles = $db_conn->query($query)->num_rows;
			if ($i==1) {
				echo "{\"text\":\"<a href='kbase_content.php?action=FORDERDETAILFORM&p={$match['id']}' target='basefrm'>{$match['name']}[$num_articles]</a>\",\"expanded\":$expanded";
			} else {
				echo ",{\"text\":\"<a href='kbase_content.php?action=FORDERDETAILFORM&p={$match['id']}' target='basefrm'>{$match['name']}[$num_articles]</a>\",\"expanded\":$expanded";
			}
			if ($expanded=="true") {
				echo ",\"children\":[";
				$query = "SELECT * FROM kbase_cat WHERE pid={$match['id']}";
				$result2 = $db_conn->query($query);
				$j=0;
				while ($match2=$result2->fetch_array()) {
					$j++;
					$query = "SELECT * FROM kbase_cat WHERE pid={$match2['id']}";
					if ($db_conn->query($query)->num_rows>0) {
						$hasChildren="true";
					} else {
						$hasChildren="false";
					}
					$query = "SELECT * FROM kbase WHERE `cat_id`={$match2['id']}  ORDER BY name";
					$num_articles = $db_conn->query($query)->num_rows;
					if ($j==1) {
						echo "{\"text\":\"<a href='kbase_content.php?action=FORDERDETAILFORM&p={$match2['id']}' target='basefrm'>{$match2['name']}[$num_articles]</a>\",\"id\":\"{$match2['id']}\",\"hasChildren\":$hasChildren}";
					} else {
						echo ",{\"text\":\"<a href='kbase_content.php?action=FORDERDETAILFORM&p={$match2['id']}' target='basefrm'>{$match2['name']}[$num_articles]</a>\",\"id\":\"{$match2['id']}\",\"hasChildren\":$hasChildren}";
					}
				}
				echo "]";
			}
			echo "}";
		}
		echo "]";
	}
} elseif (isset($_REQUEST['root'])&&$_REQUEST['root']<>"") {
	$query = "SELECT * FROM kbase_cat WHERE pid={$_REQUEST['root']}";
	$result = $db_conn->query($query);
	if($result->num_rows>0) {
		echo "[";
		$i=0;
		while ($match=$result->fetch_array()) {
			$i++;
			$query = "SELECT * FROM kbase_cat WHERE pid={$match['id']}";
			if ($db_conn->query($query)->num_rows>0) {
				$hasChildren="true";
			} else {
				$hasChildren="false";
			}
			$query = "SELECT * FROM kbase WHERE `cat_id`={$match['id']}  ORDER BY name";
			$num_articles = $db_conn->query($query)->num_rows;
			if ($i==1) {
				echo "{\"text\":\"<a href='kbase_content.php?action=FORDERDETAILFORM&p={$match['id']}' target='basefrm'>{$match['name']}[$num_articles]</a>\",\"id\":\"{$match['id']}\",\"hasChildren\":$hasChildren}";
			} else {
				echo ",{\"text\":\"<a href='kbase_content.php?action=FORDERDETAILFORM&p={$match['id']}' target='basefrm'>{$match['name']}[$num_articles]</a>\",\"id\":\"{$match['id']}\",\"hasChildren\":$hasChildren}";
			}	
		}
		echo "]";
	}
}

?>
