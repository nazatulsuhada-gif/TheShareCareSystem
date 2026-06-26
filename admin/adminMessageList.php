<?php 
session_start();
include ("../db.php");



$result = mysqli_query($conn,
    "SELECT * FROM contact_messages
     ORDER BY created_at DESC");
?>




<!DOCTYPE html>
<html>
<head>
    <title>Admin Messages - The Share Care</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<?php include("navbar.php"); ?>

<div class="header">
    <h1>Message List</h1>
</div>



<table align="center" border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Message</th>
    <th>Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['phone']) ?></td>
    <td><a href="adminMessageRead.php?id=<?= $row['message_id'] ?>">
        <?= htmlspecialchars(substr($row['message'],0,50)) ?>...
    </a>
    <td><?= $row['created_at'] ?></td>
    
    
</td>
</td>
    
</tr>

<?php } ?>

</table>

</div>

</body>
</html>