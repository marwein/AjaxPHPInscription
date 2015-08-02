<?php
try {
	//On se connecte a la base
	include 'db.inc.php';

	$where		= " 1=1 ";
	$order_by	= "id";
	$rows		= 25;
	$current	= 1;
	$limit_l	= ($current * $rows) - ($rows);
	$limit_h	= $limit_lower + $rows ;

	//Handles Sort querystring sent from Bootgrid
	if (isset($_REQUEST['sort']) && is_array($_REQUEST['sort']) )
	{
		$order_by = "";
		foreach($_REQUEST['sort'] as $key => $value)
			$order_by .= " $key $value";
	}

	//Handles search querystring sent from Bootgrid
	if (isset($_REQUEST['searchPhrase']) )
	{
		$search = trim($_REQUEST['searchPhrase']);
		$where .= " AND ( firstname LIKE '".$search."%' OR lastname LIKE '".$search."%' OR category LIKE '".$search."%' OR pseudo LIKE '".$search."%' OR phone LIKE '".$search."%' OR email LIKE '".$search."%' ) ";
	}

	//Handles determines where in the paging count this result set falls in
	if (isset($_REQUEST['rowCount']) )
		$rows = $_REQUEST['rowCount'];

	//calculate the low and high limits for the SQL LIMIT x,y clause
	if (isset($_REQUEST['current']) )
	{
		$current = $_REQUEST['current'];
		$limit_l = ($current * $rows) - ($rows);
		$limit_h = $rows ;
	}

	if ($rows == -1)
		$limit = ""; //no limit
	else
		$limit = " LIMIT $limit_l,$limit_h ";

	//NOTE: No security here please beef this up using a prepared statement - as is this is prone to SQL injection.
	$req="SELECT * FROM inscription WHERE $where ORDER BY $order_by $limit";

	$stmt	= $connexion->prepare($req);
	$stmt->execute();
	$results	=	$stmt->fetchAll(PDO::FETCH_ASSOC);
	$json	= json_encode($results);

	/* specific search then how many match */
	$sth = $connexion->prepare("SELECT count(*) FROM inscription WHERE $where;");
	$sth->execute();
	$nRows=$sth->fetchColumn();

	header('Content-Type: application/json'); //tell the broswer JSON is coming
	if (isset($_REQUEST['rowCount']) ) //Means we're using bootgrid library
		echo "{ \"current\":  $current, \"rowCount\":$rows,  \"rows\": ".$json.", \"total\": $nRows }";
	else
		echo $json; //Just plain vanillat JSON output
	exit;
}
catch(PDOException $e) {
	echo 'SQL PDO ERROR: ' . $e->getMessage();
}
?>