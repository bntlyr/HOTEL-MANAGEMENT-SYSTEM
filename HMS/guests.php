<?php
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');

// Fetch Guests Lists with sorting and searching functionality
$guestsQuery = "
    SELECT Guests.guestID, Guests.firstName, Guests.lastName, Guests.emailAddress, Rooms.roomNumber, Bookings.dateFrom, Bookings.dateTo, Payments.amount
    FROM Guests
    JOIN Bookings ON Guests.guestID = Bookings.guestID
    JOIN RoomsBooked ON Bookings.bookingID = RoomsBooked.bookingID
    JOIN Rooms ON RoomsBooked.roomID = Rooms.roomID
    JOIN Payments ON Bookings.bookingID = Payments.bookingID
    ORDER BY Guests.firstName ASC, Payments.amount DESC; 
";
$guestsResult = $conn->query($guestsQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Guests - Hotel Management System</title>
    <link rel="stylesheet" href="styles/guests.css"> <!-- Adjust this path to your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-J2Sp4s4stwrbI8Rg+Rv5ZgDlDxV0cpFk1MPXeha4Wf8t3NUF7/1rCD2nF7DoCf/l0h7p1PPTd79MDcdJwHK3Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="main-content">
        <h1 class="dash"><i class="fas fa-users"></i> Guests Section</h1>
        <h2 class="guests">Guest List <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search by name...">
            <button onclick="searchGuest()" class="btn"><i class="fas fa-search"></i></button>
        </div>
    </h2>
        <table id="guestsTable">
            <thead>
                <tr>
                    <th>Name <i class="fas fa-sort" onclick="sortTable(0)"></i></th>
                    <th>Email</th>
                    <th>Room Number</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Payment <i class="fas fa-sort" onclick="sortTable(5)"></i></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($guestsResult->num_rows > 0) {
                    while($row = $guestsResult->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['firstName']} {$row['lastName']}</td>
                                <td>{$row['emailAddress']}</td>
                                <td>{$row['roomNumber']}</td>
                                <td>{$row['dateFrom']}</td>
                                <td>{$row['dateTo']}</td>
                                <td>{$row['amount']}</td>
                                <td class='action-links'>
                                    <a href='index.php?page=view_guest&id={$row['guestID']}' class='btn'><i class='fas fa-eye'></i></a>
                                    <button class='btn btn-danger' onclick='confirmDelete({$row['guestID']})'><i class='fas fa-trash'></i></button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this guest?')) {
                window.location.href = 'delete_guest.php?id=' + id;
            }
        }

        // Function to sort table columns
        function sortTable(columnIndex) {
            const table = document.querySelector("#guestsTable tbody");
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

        // Function to search guests by name
        function searchGuest() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("guestsTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Index 0 for Name column
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
