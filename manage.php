<?php
session_start();
// // 1. LOGIN CHECKING FOR MANAGER
// if (!isset($_SESSION["manager_logged_in"]) || $_SESSION["manager_logged_in"] !== true) {
//     header("Location: manager_login.php");
//     exit();
// }
// 2. DATABASE CONNECTION
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


// 3. DELETE EOI THEO JOBREF
$delete_message = "";
if (isset($_POST["delete_btn"])) {
    $jobref = trim($_POST["delete_jobref"]);
    if ($jobref !== "") {
        $query = "DELETE FROM eoi WHERE jobref='$jobref'";
        if (mysqli_query($conn, $query)) {
            $delete_message = "Deleted all EOIs with Job Reference: $jobref";
        } else {
            $delete_message = "Error deleting: " . mysqli_error($conn);
        }
    }
}

// 4. UPDATE STATUS
$update_message = "";
if (isset($_POST["update_btn"])) {
    $eoinumber = trim($_POST["eoi_number"]);
    $status = trim($_POST["new_status"]);

    if ($eoinumber != "" && $status != "") {
        $query = "UPDATE eoi SET status='$status' WHERE EOInumber='$eoinumber'";
        if (mysqli_query($conn, $query)) {
            $update_message = "EOI #$eoinumber updated to status: $status";
        } else {
            $update_message = "Error updating status: " . mysqli_error($conn);
        }
    }
}

// 5. SEARCH + SORT
$where = " WHERE 1 "; 

// Filter based on jobref
if (!empty($_GET["search_jobref"])) {
    $jobref = trim($_GET["search_jobref"]);
    $where .= " AND jobref='$jobref' ";
}

// Filter based on firstname
if (!empty($_GET["search_firstname"])) {
    $firstname = trim($_GET["search_firstname"]);
    $where .= " AND Fname LIKE '%$firstname%' ";
}

// Filter based on lastname
if (!empty($_GET["search_lastname"])) {
    $lastname = trim($_GET["search_lastname"]);
    $where .= " AND Lname LIKE '%$lastname%' ";
}

// Sorting by criteria
$sort = "";
if (!empty($_GET["sortby"])) {
    $sortby = $_GET["sortby"];
    $allowed = ["EOInumber", "Fname", "Lname", "jobref", "status"];
    if (in_array($sortby, $allowed)) {
        $sort = " ORDER BY $sortby ASC";
    }
}

// 6. LAST QUERY FOR LIST
$query = "SELECT * FROM eoi $where $sort";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage EOI</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        th { background: #f0f0f0; }
        form { margin-bottom: 25px; padding: 10px; border: 1px solid #ddd; }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>

<h1>Manage Applications (EOI)</h1>

     <!-- DELETE FORM -->
<h3>Delete All EOIs by Job Reference</h3>
<p class="msg"><?= $delete_message ?></p>
<form method="post">
    Job Reference: <input name="delete_jobref">
    <button name="delete_btn">Delete</button>
</form>

     <!-- UPDATE STATUS FORM -->
<h3>Update EOI Status</h3>
<p class="msg"><?= $update_message ?></p>
<form method="post">
    EOI Number: <input name="eoi_number">
    New Status:
    <select name="new_status">
        <option value="New">New</option>
        <option value="Current">Current</option>
        <option value="Final">Final</option>
    </select>
    <button name="update_btn">Update</button>
</form>

     <!-- SEARCH + SORT FORM -->
<h3>Search & Sort EOIs</h3>
<form method="get">
    Job Reference: <input name="search_jobref">
    First Name: <input name="search_firstname">
    Last Name: <input name="search_lastname">

    Sort by:
    <select name="sortby">
        <option value="">None</option>
        <option value="EOInumber">EOI Number</option>
        <option value="firstname">First Name</option>
        <option value="lastname">Last Name</option>
        <option value="jobref">Job Ref</option>
        <option value="status">Status</option>
    </select>

    <button type="submit">Search</button>
</form>

     <!-- DISPLAY TABLE -->
<table>
    <tr>
        <th>EOI Number</th>
        <th>Job Ref</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
    </tr>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['EOInumber']}</td>
                <td>{$row['jobref']}</td>
                <td>{$row['firstname']} {$row['lastname']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['status']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No results found.</td></tr>";
    }
    ?>
</table>

</body>
</html>