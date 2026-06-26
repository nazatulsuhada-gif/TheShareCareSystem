<?php

include("connection.php");

$id=$_GET['id'];

$sql="UPDATE donations
      SET status='Approved'
      WHERE donationID='$id'";

mysqli_query($conn,$sql);

header("Location: adminDonationReview.php");

?>