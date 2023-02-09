<?php
// CONFIGURATION FILE (Written by @AustinGoodrich)
//==========================================================================================================
// Please ensure that you set these configurations up following the installation guide.
// Misconfiguration can lead to SQL Injection vulnerabilities that may compromise your 
// MYSQL's databse. Only setup this API with a READ-ONLY User Account.
//======================================================================================

// Determine if API Keys are enable.
// True = Requires key to submit a query.
// False = No keys required to submit a query. (Not recommended)
// Keys can be found in "keys.php" and are set to default keys.
$apiKeys = true;

// Are you using TheNewEconomy with Towny?
// (Requires you give MySQL Read-only user access to TNE Database)
$tne = true;


// CONNECTION SETTINGS
$host = "localhost";
$port = 3306;
$username = "jsonapi_readonly";
$password = "supercalifragilisticexpialidocious";
//Read the Installation Guide on how to create a READ-ONLY user account for MYSQL Towny!

//Do not edit unless you need to add additional flags.
$dsn = "mysql:host=${host};port=${port}";

//Establishes PDO Connection to MySQL Database
$pdo = new PDO($dsn, $username, $password);

// For Windows based MySQL servers, typically all database are lower case.
// For UNIX based MySQL servers, typically all databases are capitalized unless specified to not be.
// You will need to capitalize these if it fails to find your database.
$db_towny = "towny";
$db_tne = "tne";

$table_towny_towns = "TOWNY_TOWNS";
$table_towny_townblocks = "TOWNY_TOWNBLOCKS";
$table_towny_nations = "TOWNY_NATIONS";
$table_towny_residents = "TOWNY_RESIDENTS";
$table_tne_ecoids = "TNE_ECOIDS";
$table_tne_balances = "TNE_BALANCES";

// If you modify the querys below, use https://github.com/TownyAdvanced/Towny/blob/master/src/com/palmergames/bukkit/towny/db/SQL_Schema.java for reference
// Make sure you don't put any WHERE statements in the querys as that will break stuff

// Query for getting town data
 $query_towns = '';

if($tne){
    $query_towns = "
        SELECT T.name, T.mayor, T.nation, T.assistants, T.townBoard, T.tag, T.open, T.public, T.spawn, T.outpostSpawns, T.outlaws, T.registered, COUNT(".$db_towny.".".$table_towny_townblocks.".town) AS claimCount, ".$db_tne.".".$table_tne_balances.".balance
        FROM ".$db_towny.".".$table_towny_towns." AS T
        LEFT JOIN ".$db_towny.".".$table_towny_townblocks."
        ON T.name = ".$db_towny.".".$table_towny_townblocks.".town
        INNER JOIN ".$db_tne.".".$table_tne_ecoids."
        ON ".$db_tne.".".$table_tne_ecoids.".username COLLATE utf8mb4_general_ci = CONCAT('town-', T.name)
        INNER JOIN ".$db_tne.".".$table_tne_balances."
        ON ".$db_tne.".".$table_tne_ecoids.".uuid = ".$db_tne.".".$table_tne_balances.".uuid
        %WHERE
        GROUP BY T.name
    ";
} else {
	$query_towns = "
        SELECT T.name, T.mayor, T.nation, T.assistants, T.townBoard, T.tag, T.open, T.public, T.spawn, T.outpostSpawns, T.outlaws, T.registered, COUNT(".$table_towny_townblocks.".town) AS claimCount
        FROM ".$db_towny.".".$table_towny_towns." AS T
        LEFT JOIN ".$db_towny.".".$table_towny_townblocks."
        ON T.name = ".$db_towny.".".$table_towny_townblocks.".town
        %WHERE
        GROUP BY T.name
        ";
}


// Query for getting nation data
$query_nations = "
    SELECT name, capital, tag, allies, enemies, registered, nationBoard, mapColorHexCode, nationSpawn, isPublic, isOpen
    FROM ".$db_towny.".".$table_towny_nations."
    %WHERE
";

// Query for getting resident data
$query_residents = "
    SELECT name, town, 'town-rank', 'nation-ranks', lastOnline, registered, title, surname, friends, uuid
    FROM ".$db_towny.".".$table_towny_residents."
    %WHERE
";

// READ-ONLY USER MYSQL COMMAND LINE
// GRANT SELECT, SHOW VIEW ON towny.* TO 'towny_readonly'@'localhost' IDENTIFIED BY 'supercalifragilisticexpialidocious';

//Error Responses
$ERR_NO_KEY = "Verify you are using an API Key in your request URL";
$ERR_BAD_KEY = "Please use a valid API Key provided to you by the System Administrator";
$ERR_NO_NAME = "Verify you are using name= in your request URL.";
?>
