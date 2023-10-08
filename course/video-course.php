<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['course'] = null;

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
$coursePicture = $entry['coursePicture'];
$courseVideo = $entry['courseVideo'];
// echo $profilePicture;
// exit;
$imageName = uniqid();
$imageUrl = "";
$videoName = uniqid();
$videoUrl = "";

try {
    //ตัดเอา data : ออก
    $profilePictureNothavePrefix = substr($coursePicture, 5);

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
    $imagePath = "uploads/course/" . $imageName;

    file_put_contents($imagePath, base64_decode($imageData));

    $imageUrl = "http://localhost/koreanUp/" . $imagePath;

    // echo $profilePictureNothavePrefix;
    // exit;
} catch (\Throwable $th) {

}

//video
try {
    //ตัดเอา data : ออก
    $courseVideoNothavePrefix = substr($courseVideo, 5);

    //แยกเอาชื่อไฟล์กับเนื้อข้อมูลออกจากกัน
    $splited = explode(",", $courseVideoNothavePrefix);

    //สร้างตัวแปรเก็บนามสกุลรูป
    $mimeType = $splited[0];

    //สร้างตัวแปรเก็บข้อมูลรูป
    $videoData = $splited[1];

    $splitedMimeType = explode(";", $mimeType, 2);
    $videoMimeType = $splitedMimeType[0];
    $splitedMimeType = explode("/", $videoMimeType, 2);
    $fileExtension = $splitedMimeType[1];

    $videoName = $videoName . "." . $fileExtension;
    $videoPath = "uploads/video/" . $videoName;

    file_put_contents($videoPath, base64_decode($videoData));

    $videoUrl = "http://localhost/koreanUp/" . $videoPath;

    // echo $videoData;
    // exit;
} catch (\Throwable $th) {

}

// courseName = '" . $entry['name'] . "', 
// courseDetail = '" . $entry['detail'] . "', 
// courseType = '" . $entry['type'] . "',
// coursePicture = '" . $imageUrl . "',
$sql = "
UPDATE course SET 

courseVideo = '" . $videoUrl . "'
WHERE courseID = '" . $entry['id'] . "'
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