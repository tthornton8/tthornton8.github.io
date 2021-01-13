<?php
$target_dir = "../uploads/";
$fn = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($fn,PATHINFO_EXTENSION));
$target_file = $target_dir . md5($id . basename($_FILES["fileToUpload"]["name"])) . "." . $imageFileType;
$fileAlert = "";

if ($fn) {
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      $uploadOk = 1;
    } else {
      $fileAlert .=  "File is not an image.";
      $uploadOk = 0;
    }
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000) {
    $fileAlert .=  "Sorry, your file must be under 5MB in size.";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    $fileAlert .=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    $fileAlert = $fileAlert;
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      //$fileAlert =  "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
      $fileAlert = "";
      if (isset($photo)) {
        if ($photo != $target_file) {
          unlink($photo);
        }
      }
    } else {
      $fileAlert .=  "There was an error uploading your file.";
    }
  }
} else {
  $uploadOk = 0;
}
?>