<?php 
	session_start();

	include 'database.php';
	include 'readjson.php';

	if (!isset($_SESSION["active_login"]) | get_user_role($pdo, $_SESSION["active_login"]) < 1) {
		echo "Отказано в доступе";
		die();
	}

	$arg = array( $_SESSION["active_login"], $data["event_id"] );
	$sql = "SELECT 1 FROM user_on_event WHERE user_id = ? AND event_id = ?";

	$prep = $pdo->prepare($sql);
	$ok = $prep->execute($arg);

	if ($ok & $prep->rowCount() == 0) {
		echo "Вы не являетесь участником этой заявки";
		die();
	}

	$arg = array( $data["event_id"] );
	$sql = "SELECT id, Time, Name, user_id FROM file WHERE event_id = ?";

	$prep = $pdo->prepare($sql);
	$ok = $prep->execute($arg);

	$data = $prep->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($data);
?>