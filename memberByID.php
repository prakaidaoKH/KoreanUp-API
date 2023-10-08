<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['member'] = null;

//1.เชื่อมต่อ database
$conn = mysqli_connect("localhost", "root", "", "korean_up");

try {
    mysqli_query($conn, 'set names utf8');

    if (!$conn) {
        $apiResponse['status'] = "error";
        $apiResponse['message'] = "can not connect database";
        // echo "can not connect database";
        echo json_encode($apiResponse);
        exit;
    }
} catch (\Throwable $th) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "can not connect database";
    echo json_encode($apiResponse);
    exit;
}
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
// echo "connect database";

$sql = "SELECT MemberID,Username,Password,Email,Start_date,profilePicture   FROM `member` WHERE MemberID = '" . $entry['MemberID'] . "'";

$result = $conn->query($sql);

if (!$result) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "wrong sql command";
    echo json_encode($apiResponse);
    // echo "wrong sql command";
    exit;
}
// echo "run sql command success";

//6.ดึงข้อมูลออกมาแสดง
//fetch_assoc แสดงแถวเดียว
$member = $result->fetch_assoc();
$apiResponse['member'] = $member;

echo json_encode($apiResponse);
exit;
// echo "<pre>";
// print_r($member);
// foreach ($members as $member) {
//     echo "<pre>";
//     print_r($member);
// }
?>