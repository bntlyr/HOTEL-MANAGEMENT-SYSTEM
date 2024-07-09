<?php
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');

// Fetch all staffs from database with additional information like staff type and shift
$query = "SELECT s.id, st.staff_type, s.fname, s.lname, s.hiring_date, sh.shift_timing, s.salary
          FROM staffs s
          INNER JOIN staff_type st ON s.staff_type_id = st.staff_type_id
          INNER JOIN shift sh ON s.shiftid = sh.shift_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff - Hotel Management System</title>
    <link rel="stylesheet" href="styles/staffs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-J2Sp4s4stwrbI8Rg+Rv5ZgDlDxV0cpFk1MPXeha4Wf8t3NUF7/1rCD2nF7DoCf/l0h7p1PPTd79MDcdJwHK3Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="main-content">
    <h1 class="dash"><i class="fas fa-user-tie"></i> Staff Section </h1>
        <h2 class="staffs">Staff List 
            <span>
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search by name...">
                    <button onclick="searchStaff()" class="btn"><i class="fas fa-search"></i></button>
                </div> 
                <a href="index.php?page=add_staff" class="btn add-btn"><i class="fas fa-plus"></i> Add New Staff</a>
            </span>
        </h2>
        <table id="staffTable">
            <thead>
                <tr>
                    <th>ID <i class="fas fa-sort" onclick="sortTable(0)"></i></th>
                    <th>Position</th>
                    <th>Name <i class="fas fa-sort" onclick="sortTable(2)"></i></th>
                    <th>Hiring Date</th>
                    <th>Shift</th>
                    <th>Salary <i class="fas fa-sort" onclick="sortTable(5)"></i></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['staff_type']; ?></td>
                        <td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                        <td><?php echo $row['hiring_date']; ?></td>
                        <td><?php echo $row['shift_timing']; ?></td>
                        <td><?php echo '₱' . number_format($row['salary'], 2); ?></td>
                        <td class="action-links">
                            <a href="index.php?page=edit_staff&id=<?php echo $row['id']; ?>" class="btn"><i class="fas fa-edit"></i></a>
                            <a href="index.php?page=view_staff&id=<?php echo $row['id']; ?>" class="btn"><i class="fas fa-eye"></i></a>
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this staff member?')) {
                window.location.href = 'delete_staff.php?id=' + id;
            }
        }

        // Function to sort table columns
        function sortTable(columnIndex) {
            const table = document.querySelector("#staffTable tbody");
            const rows = Array.from(table.rows);
            const sortedRows = rows.sort((a, b) => {
                const aText = a.cells[columnIndex].textContent.trim();
                const bText = b.cells[columnIndex].textContent.trim();
                
                if (!isNaN(aText) && !isNaN(bText)) {
                    return aText - bText;
                } else {
                    return aText.localeCompare(bText);
                }
            });
            
            table.append(...sortedRows);
        }

        // Function to search staff by name
        function searchStaff() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("staffTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2]; // Index 2 for Name column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>
