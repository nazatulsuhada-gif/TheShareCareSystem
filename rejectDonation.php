<?php

include("connection.php");

$id=$_GET['id'];

$sql="UPDATE donations
      SET status='Rejected'
      WHERE donationID='$id'";

mysqli_query($conn,$sql);

header("Location: adminDonationReview.php");

?>