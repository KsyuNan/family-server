<?php

include_once("FamilyTreeBuilder.php");

$servername = "jdwebart.mysql.tools";
$username   = "jdwebart_test";
$password   = "test";
$db         = "jdwebart_test";

try {

    $conn = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$sql = "SELECT `id`, `unqid`, `name`, `surname`, `gender`, `birth_date`, `death_date`, `pid`, `ppid`, `partner_id` FROM `FamilyTree` WHERE 1 ORDER BY`unqid` ASC";

$result   = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$response = [];
$lastId   = 1000;

foreach ($result as $v) {

    $add = [
        "id" => $v['unqid'],
        "img"  => $v['gender'] == 1
            ? "/images/woman.jpg"
            : "/images/man.jpg",
        "tags" => $v['gender'] == 1
            ? []
            : ["blue"],
    ];

    if ($v['partner_id'] === $lastId) {
        $add['pid'] = $lastId;
        array_push($add['tags'], 'partner');
    }
    $add['name'] = $v['name'].' '.$v['surname'].' '. $v['birth_date'];

    $response[] = array_merge($v, $add);
    $lastId     = $v['unqid'];
}
$render = json_encode($response, JSON_UNESCAPED_UNICODE);

include('view.php')

?>
