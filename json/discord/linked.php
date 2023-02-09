<?php
	require('../../config.php');

	// The response JSON payload
	$response = array();
	$response["JSONAPI by @AustinGoodrich"] = ":)";

	// Get Key from URL
	if (isset($_GET['key'])) {
		// Set Key to a variable
		$key = $_GET['key'];
		// Load (keys.php)

		include('../../keys.php');
		// Verify Key exists in Defined Keys (keys.php)
		if(in_array($key, $keys)){
			// KEYS WERE SET AND VALID
			// Checks if the name parameter was set
			if (isset($_GET['discord'])) {
				// Check if parameters have been provided
				$params = array();
				$filter = array();
				if($_GET['discord'] !== 'allresidents'){
					// A discord id was provided so amend the query
					$filter[] = 'discord = :discord';
					$params[':discord'] = $_GET['discord'];
				}
				
				if(isset($_GET['uuid'])) {
					// A UUID was provided so amend the query
					$filter[] = 'uuid = :uuid';
					$params[':uuid'] = $_GET['uuid'];
				}

				// Build the query
				if(empty($filter)) {
					$filter = "";
				} else {
					$filter = "WHERE ".implode(" AND ", $filter);
				}
				$query = strtr($query_discord, array("%WHERE" => $filter));

				try {
					// Run the query
					$stmt = $pdo->prepare($query);
					$stmt->execute($params);

					// Get the resulting data
					$results = array();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						$results[strtolower("linked")] = $row;
					}
				} catch(PDOException $e) {
					die($e->getMessage());
				}

				// Set the response
				$response["status"] = "SUCCESS";
				$response["data"] = $results;
			} else {
				// User failed to provide name
				$response["status"] = "FAILURE";
				$response["error"] = array(
					"code" => "ERR_NO_NAME",
					"message" => $ERR_NO_NAME
				);
			}
		} else {
			// The user provided an invalid key
			$response["status"] = "FAILURE";
			$response["error"] = array(
				"code" => "ERR_BAD_KEY",
				"message" => $ERR_BAD_KEY
			);
		}
	} else {
		// The user failed to provide a key
		$response["status"] = "FAILURE";
		$response["error"] = array(
			"code" => "ERR_NO_KEY",
			"message" => $ERR_NO_KEY
		);
	}

	// Send the response
	http_response_code(200);
	header('Content-type: application/json');
	echo json_encode($response);
?>
