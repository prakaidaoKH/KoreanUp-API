<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['memberLogin'] = null;

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

$sql = "
SELECT 
m.MemberID,
m.Username,
m.Password,
m.Email,
m.Start_date,
m.profilePicture,
m.type,
pm.status
FROM member AS m
LEFT JOIN premium_member AS pm ON pm.memberID = m.MemberID
WHERE Email = '" . $entry['Email'] . "' AND Password = '" . $entry['Password'] . "'";


$result = $conn->query($sql);

if (!$result) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "wrong sql command";
    echo json_encode($apiResponse);
    exit;
}
$memberLogin = $result->fetch_assoc();
if ($memberLogin == null) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "data not found";
    echo json_encode($apiResponse);
    exit;
}
$apiResponse['memberLogin'] = $memberLogin;



echo json_encode($apiResponse);
exit;
?>