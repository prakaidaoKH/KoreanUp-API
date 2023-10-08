<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['member'] = null;

$conn = mysqli_connect("localhost", "root", "", "korean_up");
mysqli_query($conn, 'set names utf8');

if (!$conn) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "can not connect database";
    // echo "can not connect database";
    echo json_encode($apiResponse);
    exit;
}

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
$profilePicture = $entry['profilePicture'];
// echo $profilePicture;
// exit;
$imageName = uniqid();
$imageUrl = "";

try {
    //ตัดเอา data : ออก
    $profilePictureNothavePrefix = substr($profilePicture, 5);

    //แยกเอาชื่อไฟล์กับเนื้อข้อมูลออกจากกัน
    $splited = explode(",", $profilePictureNothavePrefix);

    //สร้างตัวแปรเก็บนามสกุลรูป
    $mimeType = $splited[0];

    //สร้างตัวแปรเก็บข้อมูลรูป
    $imageData = $splited[1];

    $splitedMimeType = explode(";", $mimeType, 2);
    $imageMimeType = $splitedMimeType[0];
    $splitedMimeType = explode("/", $imageMimeType, 2);
    $fileExtension = $splitedMimeType[1];

    $imageName = $imageName . "." . $fileExtension;
    $imagePath = "uploads/profile/" . $imageName;

    file_put_contents($imagePath, base64_decode($imageData));

    $imageUrl = "http://localhost/koreanUp/" . $imagePath;

    // echo $imageUrl;
    // exit;
} catch (\Throwable $th) {

}

$sql = "
UPDATE member SET 
Username = '" . $entry['Username'] . "', 
Password = '" . $entry['Password'] . "', 
Email = '" . $entry['Email'] . "',
profilePicture = '" . $imageUrl . "'
WHERE MemberID = '" . $entry['MemberID'] . "'
";
// echo $sql;

if ($conn->query($sql) === false) { //ถ้า run ไม่สำเร็จ
    $conn->close();
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "wrong sql command";
    echo json_encode($apiResponse);
    // echo "wrong sql command";
    exit;
}
echo json_encode($apiResponse);
exit;
?>