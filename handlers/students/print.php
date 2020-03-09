<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db();

$profile = $con->getData("SELECT *, CONCAT(firstname,' ',lastname,' ',middlename,' ',name_extension) fullname, DATE_FORMAT(date_of_birth,'%M %d, %Y') date_of_birth FROM profiles WHERE id = $_POST[id]");

$transaction_datas = $con->getData("SELECT transaction_datas.id, transaction_datas.amount, transaction_datas.transactions_id, transaction_datas.document_id, transaction_datas.purpose, DATE_FORMAT(transaction_datas.date_added, '%M %d, %Y %l:%i %p') date_added, documents.id, documents.description FROM transaction_datas LEFT JOIN documents ON transaction_datas.document_id = documents.id WHERE purpose = '1' AND profiles_id = ".$profile[0]['id']);

	foreach($transaction_datas as $key => $tran){
		
		$transactions = $con->getData("SELECT * FROM transactions WHERE id = ".$tran['transactions_id']);
		$transaction_datas[$key]['reason'] = $transactions[0]['reason'];
	};

$profile[0]['transaction_datas'] = $transaction_datas;


foreach ($profile[0] as $i => $p) {
	
	if ($p == null) $profile[0][$i] = ""; // pdf equals null
	
}

header("Content-Type: application/json");
echo json_encode($profile[0]);

?>