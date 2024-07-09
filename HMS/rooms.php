<?php
// Redirect to login page if admin session is not set
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');

// Fetch Manage Rooms with updated query to correctly determine room status
$roomsQuery = "
    SELECT Rooms.roomID, Rooms.roomNumber, Rooms.roomType, Rooms.roomStatus, Bookings.bookingStatusID, Bookings.dateFrom, Bookings.dateTo
    FROM Rooms
    LEFT JOIN RoomsBooked ON Rooms.roomID = RoomsBooked.roomID
    LEFT JOIN Bookings ON RoomsBooked.bookingID = Bookings.bookingID
    ORDER BY Rooms.roomNumber ASC;";

$roomsResult = $conn->query($roomsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - Hotel Management System</title>
    <link rel="stylesheet" href="styles/rooms.css"> <!-- Adjust this path to your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-J2Sp4s4stwrbI8Rg+Rv5ZgDlDxV0cpFk1MPXeha4Wf8t3NUF7/1rCD2nF7DoCf/l0h7p1PPTd79MDcdJwHK3Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="main-content">
        <h1 class="dash"><i class="fas fa-bed"></i> Manage Rooms </h1>
        <h2 class="rooms">Room List
            <span>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search by room number...">
                <button onclick="searchRoom()" class="btn"><i class="fas fa-search"></i></button>
            </div> 
            <a href="index.php?page=add_room" class="btn add-btn"><i class="fas fa-plus"></i> Add New Room</a>
            </span>
        </h2>
        <table id="roomsTable">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">Room No <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(1)">Room Type <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(2)">Booking Status <i class="fas fa-sort"></i></th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php

$today = date('Y-m-d');
$rooms = [];

while ($row = $roomsResult->fetch_assoc()) {
    if (!isset($rooms[$row['roomID']])) {
        $rooms[$row['roomID']] = [
            'roomNumber' => $row['roomNumber'],
            'roomType' => $row['roomType'],
            'bookingStatus' => 'Available',
            'checkIn' => '-',
            'checkOut' => '-',
            'roomStatus' => $row['roomStatus']
        ];
    }

    if ($row['bookingStatusID'] !== null) {
        // Always prioritize future bookings over past bookings
        if ($row['dateTo'] >= $today) {
            $rooms[$row['roomID']]['bookingStatus'] = 'Occupied';
            $rooms[$row['roomID']]['checkIn'] = $row['dateFrom'];
            $rooms[$row['roomID']]['checkOut'] = $row['dateTo'];
            $rooms[$row['roomID']]['roomStatus'] = 'Occupied';
        } elseif ($rooms[$row['roomID']]['bookingStatus'] !== 'Occupied') {
            // If no future booking, check past booking to mark available
            $rooms[$row['roomID']]['bookingStatus'] = 'Available';
            $rooms[$row['roomID']]['checkIn'] = '-';
            $rooms[$row['roomID']]['checkOut'] = '-';
            $rooms[$row['roomID']]['roomStatus'] = 'Available';
        }
    }
}

foreach ($rooms as $roomID => $room) {
    $updateStatusQuery = "UPDATE Rooms SET roomStatus = '{$room['roomStatus']}' WHERE roomID = {$roomID}";
    $conn->query($updateStatusQuery);
}


// Display room information in table rows
foreach ($rooms as $roomID => $room) {
    echo "<tr>
            <td>{$room['roomNumber']}</td>
            <td>{$room['roomType']}</td>
            <td>{$room['bookingStatus']}</td>
            <td>{$room['checkIn']}</td>
            <td>{$room['checkOut']}</td>
            <td class='action-links'>
                <a href='index.php?page=edit_room&id={$roomID}' class='btn'><i class='fas fa-edit'></i></a>
                <a href='index.php?page=view_room&id={$roomID}' class='btn'><i class='fas fa-eye'></i></a>
                <button class='btn btn-danger' onclick='confirmDelete({$roomID})'><i class='fas fa-trash'></i></button>
            </td>
          </tr>";
}

?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this room?')) {
                window.location.href = 'delete_room.php?id=' + id;
            }
        }

        // Function to sort table columns
        function sortTable(columnIndex) {
            const table = document.querySelector("#roomsTable tbody");
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
            
            table.innerHTML = ""; // Clear existing rows
            table.append(...sortedRows); // Append sorted rows
        }

        // Function to search room by number
        function searchRoom() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("roomsTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Index 0 for Room Number column
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

<?php
// Close database connection
$conn->close();
?>