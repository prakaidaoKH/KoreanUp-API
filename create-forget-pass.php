<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['memberForgetPass'] = null;

$conn = mysqli_connect("localhost", "root", "", "korean_up");
mysqli_query($conn, 'set names utf8');


if (!$conn) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "can not connect database";
    // echo "can not connect database";
    echo json_encode($apiResponse);
    exit;
}
//รับค่าจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
// echo "<pre>";
// print_r($entry);

//คำสั่ง SQL insert ข้อมูล
$sql = "
INSERT INTO forget_password(
    memberID,
    email,
    username
    )
    VALUES(
        '" . $entry['memberID'] . "',
        '" . $entry['email'] . "',
        '" . $entry['username'] . "'
        )
";
// echo $sql;

//run sql command
if ($conn->query($sql) === false) { //ถ้า run ไม่สำเร็จ
    $conn->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = 'wrong sql command';
    echo json_encode($apiResponse);
    exit;
}

echo json_encode($apiResponse);
exit;
?>