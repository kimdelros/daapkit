<?php
require $_SERVER['DOCUMENT_ROOT'].'/daap/vendor/sendmail.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/daap/resource/php/class/core/init.php';
$user = new user();
isRegistrar($user->data()->groups);

require 'config.php';

$db = new config();
$con = $db->con();

$transID = $_GET['id'];
$holdBy = $user->data()->id;
$reasonHold = $_GET['reason'];
$dateHold = date('Y-m-d H:i:s', time());

$sql = "UPDATE `applications` SET `isHold` = '1', `dateHold` = '$dateHold', `reasonHold` = '$reasonHold', `holdBy` = '$holdBy' WHERE `transID` = '$transID'";
$data= $con->prepare($sql);
$data->execute();

$sql = "SELECT `studentName`, `studentEmail` FROM `applications` WHERE `transID` = '$transID'";
$data= $con->prepare($sql);
$data->execute();
$result = $data->fetchAll(PDO::FETCH_ASSOC);

sendHoldUpdate($result[0]['studentName'], $result[0]['studentEmail'], $reasonHold." Document/s", $transID);

$strArray = explode('-',$transID);
switch($strArray[0]){
  case "ALUM":
  header('Location: ../../../pendingAlumni.php');
  break;
  case "SIBL":
  header('Location: ../../../pendingSibling.php');
  break;
  case "CEIS":
  header('Location: ../../../pendingCeis.php');
  break;
  default:break;
}
 ?>
