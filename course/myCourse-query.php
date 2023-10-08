<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;

$conn = mysqli_connect("localhost", "root", "", "korean_up");
try {
  mysqli_query($conn, 'set names utf8');

  if (!$conn) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = 'can not connection database';
    echo json_encode($apiResponse);
    exit;
  }
} catch (\Throwable $th) {
  $apiResponse['status'] = "error";
  $apiResponse['message'] = "can not connect database";
  echo json_encode($apiResponse);
  exit;
}

$rawData
  = file_get_contents("php://input");
$entry = json_decode($rawData, true);

$sql = "
SELECT
  member.Username,
  member.Email,
  course.courseName,
  course.courseType,
  course.coursePicture,
  course.courseID
FROM mycourse
JOIN `member`
  ON member.MemberID = mycourse.memberID
JOIN `course`
  ON course.courseID = mycourse.courseID
WHERE mycourse.memberID = '" . $entry['MemberID'] . "'
";

$result = $conn->query($sql);

if (!$result) {
  $apiResponse['status'] = "error";
  $apiResponse['message'] = "wrong sql command";
  echo json_encode($apiResponse);
  exit;
}

$myCourses = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['myCourses'] = $myCourses;

echo json_encode($apiResponse);
exit;
?>