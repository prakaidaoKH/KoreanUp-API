<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;


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

// echo "connect database";
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

$sql = "
SELECT 
    pa.statusP,
    pm.premiumID,
    pm.start_date,
    pm.end_date,
    pa.notic
FROM premium_apply AS pa
LEFT JOIN premium_member AS pm ON pa.memberID = pm.memberID
WHERE pa.memberID = '" . $entry['memberID'] . "'";

$result = $conn->query($sql);

if (!$result) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "wrong sql command";
    echo json_encode($apiResponse);
    // echo "wrong sql command";
    exit;
}
$statusP = $result->fetch_assoc();
$apiResponse['statusP'] = $statusP['statusP'];
$apiResponse['premiumID'] = $statusP['premiumID'];
$apiResponse['start_date'] = $statusP['start_date'];
$apiResponse['end_date'] = $statusP['end_date'];
$apiResponse['notic'] = $statusP['notic'];
echo json_encode($apiResponse);
exit;

?>