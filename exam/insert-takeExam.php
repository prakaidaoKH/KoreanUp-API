<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;

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
INSERT INTO take_exam(
    courseID,
    memberID,
    examID_1,
    selected_choice1,
    correct_choice1,
    examID_2,
    selected_choice2,
    correct_choice2,
    examID_3,
    selected_choice3,
    correct_choice3,
    examID_4,
    selected_choice4,
    correct_choice4,
    examID_5,
    selected_choice5,
    correct_choice5,
    examID_6,
    selected_choice6,
    correct_choice6,
    examID_7,
    selected_choice7,
    correct_choice7,
    examID_8,
    selected_choice8,
    correct_choice8,
    examID_9,
    selected_choice9,
    correct_choice9,
    examID_10,
    selected_choice10,
    correct_choice10,
    date,
    score
    )
    VALUES(
        '" . $entry['courseID'] . "',
        '" . $entry['memberID'] . "',
        '" . $entry['examID_1'] . "',
        '" . $entry['selected_choice1'] . "',
        '" . $entry['correct_choice1'] . "',
        '" . $entry['examID_2'] . "',
        '" . $entry['selected_choice2'] . "',
        '" . $entry['correct_choice2'] . "',
        '" . $entry['examID_3'] . "',
        '" . $entry['selected_choice3'] . "',
        '" . $entry['correct_choice3'] . "',
        '" . $entry['examID_4'] . "',
        '" . $entry['selected_choice4'] . "',
        '" . $entry['correct_choice4'] . "',
        '" . $entry['examID_5'] . "',
        '" . $entry['selected_choice5'] . "',
        '" . $entry['correct_choice5'] . "',
        '" . $entry['examID_6'] . "',
        '" . $entry['selected_choice6'] . "',
        '" . $entry['correct_choice6'] . "',
        '" . $entry['examID_7'] . "',
        '" . $entry['selected_choice7'] . "',
        '" . $entry['correct_choice7'] . "',
        '" . $entry['examID_8'] . "',
        '" . $entry['selected_choice8'] . "',
        '" . $entry['correct_choice8'] . "',
        '" . $entry['examID_9'] . "',
        '" . $entry['selected_choice9'] . "',
        '" . $entry['correct_choice9'] . "',
        '" . $entry['examID_10'] . "',
        '" . $entry['selected_choice10'] . "',
        '" . $entry['correct_choice10'] . "',
        NOW(),
        '" . $entry['score'] . "'
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