<?php
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

$query = "SELECT * FROM HotelX_booking";
$result = mysqli_query($conn, $query);

?>

<!-- HTML for Admin Dashboard -->
<h1>Admin Dashboard</h1>
<a href="add_content.php">Add New Content</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td>
                    <a href="edit_content.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="delete_content.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
