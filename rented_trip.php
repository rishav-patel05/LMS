<?php
session_start();
include 'db.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle Add Trip
if (isset($_POST['add_trip'])) {
 $trip_complete = $_POST['trip_complete'] ?? 0;
 // ✅ checkbox status

    $sql = "INSERT INTO rented_trips 
            (invoice_no, lr_no, trip_date, vehicle_no, from_place, to_place, port_reach_time, start_time, company_gate_reach, company_left_time, consignor, consignee, truck_owner_name, owner_number, truck_driver_name, truck_driver_mob, loading_weight, unloading_weight, short_weight, product_name, trip_complete)
            VALUES (
                '{$_POST['invoice_no']}',
                '{$_POST['lr_no']}',
                '{$_POST['trip_date']}',
                '{$_POST['vehicle_no']}',
                '{$_POST['from_place']}',
                '{$_POST['to_place']}',
                '{$_POST['port_reach_time']}',
                '{$_POST['start_time']}',
                '{$_POST['company_gate_reach']}',
                '{$_POST['company_left_time']}',
                '{$_POST['consignor']}',
                '{$_POST['consignee']}',
                '{$_POST['truck_owner_name']}',
                '{$_POST['owner_number']}',
                '{$_POST['truck_driver_name']}',
                '{$_POST['truck_driver_mob']}',
                '{$_POST['loading_weight']}',
                '{$_POST['unloading_weight']}',
                '{$_POST['short_weight']}',
                '{$_POST['product_name']}',
                '$trip_complete'  -- ✅ new column
            )";
    $conn->query($sql);
    header("Location: rented_trip.php");
    exit();
}
// Handle Update Trip
if (isset($_POST['update_trip'])) {
    $trip_complete = isset($_POST['trip_complete']) ? 1 : 0;
    $id = $_POST['id']; // hidden field

    $sql = "UPDATE rented_trips SET
        invoice_no='{$_POST['invoice_no']}',
        lr_no='{$_POST['lr_no']}',
        trip_date='{$_POST['trip_date']}',
        vehicle_no='{$_POST['vehicle_no']}',
        from_place='{$_POST['from_place']}',
        to_place='{$_POST['to_place']}',
        port_reach_time='{$_POST['port_reach_time']}',
        start_time='{$_POST['start_time']}',
        company_gate_reach='{$_POST['company_gate_reach']}',
        company_left_time='{$_POST['company_left_time']}',
        consignor='{$_POST['consignor']}',
        consignee='{$_POST['consignee']}',
        truck_owner_name='{$_POST['truck_owner_name']}',
        owner_number='{$_POST['owner_number']}',
        truck_driver_name='{$_POST['truck_driver_name']}',
        truck_driver_mob='{$_POST['truck_driver_mob']}',
        loading_weight='{$_POST['loading_weight']}',
        unloading_weight='{$_POST['unloading_weight']}',
        short_weight='{$_POST['short_weight']}',
        product_name='{$_POST['product_name']}',
        trip_complete='$trip_complete'
        WHERE id='$id'";
    $conn->query($sql);
    header("Location: rented_trip.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM rented_trips WHERE id=$id");
    header("Location: rented_trip.php");
    exit();
}

// ✅ Handle Toggle Trip Completion
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $conn->query("UPDATE rented_trips SET trip_complete = 1 - trip_complete WHERE id=$id");
    header("Location: rented_trip.php");
    exit();
}

// Fetch records
$result = $conn->query("SELECT * FROM rented_trips ORDER BY id DESC");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Rented Trips | LMS</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f9fc;
            margin: 0;
        }
        header {
            background: #007bff;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header a {
            color: white;
            text-decoration: none;
            background: #ff4757;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .container {
            padding: 30px;
            overflow-x: auto;
        }
        h2 {
            color: #007bff;
        }
        .add-btn {
            background: #007bff;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .add-btn:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #eee;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        .action-btn {
            background: #ff4757;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
        }
        .edit-btn {
            background: #28a745;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            width: 700px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            overflow-y: auto;
            max-height: 90vh;
        }
        .modal-content h3 {
            text-align: center;
            color: #007bff;
        }
        .modal-content input {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
        }
        .close {
            float: right;
            font-size: 22px;
            cursor: pointer;
            color: red;
        }
    </style>
</head>
<body>

<header>
    <h1>🚛 Rented Vehicle Trips</h1>
    <div>
        <a href="dashboard.php">⬅ Back</a>
    </div>
</header>

<div class="container">
    <button class="add-btn" onclick="openModal()">+ Add Trip</button>

       <table>
        <tr>
            <th>ID</th>
            <th>Goods Invoice No</th>
            <th>LR No</th>
            <th>Trip Date</th>
            <th>Vehicle No</th>
            <th>From</th>
            <th>To</th>
            <th>Port Reach Time</th>
            <th>Start Time</th>
            <th>Gate Reach Time</th>
            <th>Left Time</th>
            <th>Consignor</th>
            <th>Consignee</th>
            <th>Truck Owner Name</th>
            <th>Owner Number</th>
            <th>Truck Driver Name</th>
            <th>Truck Driver Mobile</th>
            <th>Loading Weight</th>
            <th>Unloading Weight</th>
            <th>Short Weight</th>
            <th>Product Name</th>
            <th>Actions</th>
        </tr>

<?php while ($row = $result->fetch_assoc()) {
    // safe check: handles missing array key without warning
$completed = !empty($row['trip_complete']) && $row['trip_complete'] == 1;
$rowColor = $completed ? '#d4edda' : '#fff3cd';

?>
<tr style="background-color: <?= $rowColor ?>;">
    <td><?= $row['id'] ?></td>
    <td><?= $row['invoice_no'] ?></td>
    <td><?= $row['lr_no'] ?></td>
    <td><?= $row['trip_date'] ?></td>
    <td><?= $row['vehicle_no'] ?></td>
    <td><?= $row['from_place'] ?></td>
    <td><?= $row['to_place'] ?></td>
    <td><?= $row['port_reach_time'] ?></td>
    <td><?= $row['start_time'] ?></td>
    <td><?= $row['company_gate_reach'] ?></td>
    <td><?= $row['company_left_time'] ?></td>
    <td><?= $row['consignor'] ?></td>
    <td><?= $row['consignee'] ?></td>
    <td><?= $row['truck_owner_name'] ?></td>
    <td><?= $row['owner_number'] ?></td>
    <td><?= $row['truck_driver_name'] ?></td>
    <td><?= $row['truck_driver_mob'] ?></td>
    <td><?= $row['loading_weight'] ?></td>
    <td><?= $row['unloading_weight'] ?></td>
    <td><?= $row['short_weight'] ?></td>
    <td><?= $row['product_name'] ?></td>
   <td>
    <a href="#" 
   class="edit-btn" 
   style="padding:5px 10px;text-decoration:none;"
   onclick='openEditModal(<?= json_encode($row) ?>)'>Edit</a>

    <a href="?delete=<?= $row['id'] ?>" class="action-btn" onclick="return confirm('Delete this trip?')">Delete</a>
</td>

</tr>
<?php } ?>


    </table>

</div>

<!-- Modal for Add Trip -->
<div id="tripModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Add Rented Vehicle Trip</h3>
        <form method="POST">
            <input type="hidden" name="id" id="trip_id">
            <input type="text" name="invoice_no" placeholder="Goods Invoice No" required>
            <input type="text" name="lr_no" placeholder="LR No" required>
            <input type="date" name="trip_date" required>
            <input type="text" name="vehicle_no" placeholder="Vehicle No" required>
            <input type="text" name="from_place" placeholder="From" required>
            <input type="text" name="to_place" placeholder="To" required>
            <input type="time" name="port_reach_time" placeholder="Port Reach Time">
            <input type="time" name="start_time" placeholder="Start Time">
            <input type="time" name="company_gate_reach" placeholder="Gate Reach Time">
            <input type="time" name="company_left_time" placeholder="Left Time">
            <input type="text" name="consignor" placeholder="Consignor">
            <input type="text" name="consignee" placeholder="Consignee">
            <input type="text" name="truck_owner_name" placeholder="Truck Owner Name">
            <input type="text" name="owner_number" placeholder="Owner Number">
            <input type="text" name="truck_driver_name" placeholder="Truck Driver Name">
            <input type="text" name="truck_driver_mob" placeholder="Truck Driver Mobile">
            <input type="number" step="0.01" name="loading_weight" placeholder="Loading Weight">
            <input type="number" step="0.01" name="unloading_weight" placeholder="Unloading Weight">
            <input type="number" step="0.01" name="short_weight" placeholder="Short Weight">
            <input type="text" name="product_name" placeholder="Product Name">
            <div style="display:flex;align-items:center;gap:8px;margin-top:10px;margin-bottom:15px;">
                

    <input type="checkbox" name="trip_complete" id="trip_complete" value="1" style="width:auto;">
    <label for="trip_complete" style="font-weight:500;">Trip Complete</label>
    
</div>



            <button type="submit" name="add_trip" class="add-btn" style="width:100%;">Save Trip</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('tripModal');
    function openModal() { modal.style.display = 'flex'; }
    function closeModal() { modal.style.display = 'none'; }
    window.onclick = function(e) { if (e.target == modal) closeModal(); }

let isEditing = false;
let editId = null;

function openEditModal(data) {
    isEditing = true;
    editId = data.id;
    const modal = document.getElementById('tripModal');
    modal.style.display = 'flex';
    document.querySelector('.modal-content h3').innerText = 'Edit Rented Vehicle Trip';
    document.querySelector('button[name="add_trip"]').name = 'update_trip';
    document.querySelector('button[name="update_trip"]').innerText = 'Update Trip';

    // Fill form values
    for (const key in data) {
        const input = document.querySelector(`[name="${key}"]`);
        if (input) input.value = data[key];
    }

    // ✅ set hidden id field
    document.getElementById('trip_id').value = data.id;

    // Checkbox
    document.querySelector('#trip_complete').checked = data.trip_complete == 1;
}


function openModal() {
    isEditing = false;
    editId = null;
    const modal = document.getElementById('tripModal');
    modal.style.display = 'flex';
    document.querySelector('.modal-content h3').innerText = 'Add Rented Vehicle Trip';
    document.querySelector('button[name="update_trip"]').name = 'add_trip';
    document.querySelector('button[name="add_trip"]').innerText = 'Save Trip';
    document.querySelector('form').reset();
    document.querySelector('#trip_complete').checked = false;
}

function closeModal() {
    document.getElementById('tripModal').style.display = 'none';
}


</script>

</body>
</html>
