<?php
include('db.php');
include 'syncToGoogle2.php';

// Handle new trip form submission
if (isset($_POST['add_trip'])) {
    $trip_complete = isset($_POST['trip_complete']) ? 1 : 0;

    $sql = "INSERT INTO thirdparty_trips (
        invoice_no, lr_no, trip_date, vehicle_no, from_place, to_place, port_reach_time, start_time,
        gate_reach_time, customer_left_time, consignor, consignee, commission_receiver, biller_name,
        transporter_name, transporter_mob, truck_driver_name, truck_driver_mob, product_name, loading_weight,
        unloading_weight, short_weight, transport_rate, total_charges, advance, commission, net_payable,
        payment_voucher_no, trip_complete
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssssssddddddddsi",
        $_POST['invoice_no'], $_POST['lr_no'], $_POST['trip_date'], $_POST['vehicle_no'], $_POST['from_place'],
        $_POST['to_place'], $_POST['port_reach_time'], $_POST['start_time'], $_POST['gate_reach_time'],
        $_POST['customer_left_time'], $_POST['consignor'], $_POST['consignee'], $_POST['commission_receiver'],
        $_POST['biller_name'], $_POST['transporter_name'], $_POST['transporter_mob'], $_POST['truck_driver_name'],
        $_POST['truck_driver_mob'], $_POST['product_name'], $_POST['loading_weight'], $_POST['unloading_weight'],
        $_POST['short_weight'], $_POST['transport_rate'], $_POST['total_charges'], $_POST['advance'],
        $_POST['commission'], $_POST['net_payable'], $_POST['payment_voucher_no'], $trip_complete
    );
    $stmt->execute();

    // ✅ Fix added: capture inserted ID
    $inserted_id = $stmt->insert_id;

    echo "<script>alert('Trip added successfully!'); window.location='thirdparty_trip.php';</script>";

    $syncData = [
        "action" => "add",
        "id" => $inserted_id,
        "invoice_no" => $_POST['invoice_no'],
        "lr_no" => $_POST['lr_no'],
        "trip_date" => $_POST['trip_date'],
        "vehicle_no" => $_POST['vehicle_no'],
        "from_place" => $_POST['from_place'],
        "to_place" => $_POST['to_place'],
        "port_reach_time" => $_POST['port_reach_time'],
        "start_time" => $_POST['start_time'],
        "gate_reach_time" => $_POST['gate_reach_time'],
        "customer_left_time" => $_POST['customer_left_time'],
        "consignor" => $_POST['consignor'],
        "consignee" => $_POST['consignee'],
        "commission_receiver" => $_POST['commission_receiver'],
        "biller_name" => $_POST['biller_name'],
        "transporter_name" => $_POST['transporter_name'],
        "transporter_mob" => $_POST['transporter_mob'],
        "truck_driver_name" => $_POST['truck_driver_name'],
        "truck_driver_mob" => $_POST['truck_driver_mob'],
        "product_name" => $_POST['product_name'],
        "loading_weight" => $_POST['loading_weight'],
        "unloading_weight" => $_POST['unloading_weight'],
        "short_weight" => $_POST['short_weight'],
        "transport_rate" => $_POST['transport_rate'],
        "total_charges" => $_POST['total_charges'],
        "advance" => $_POST['advance'],
        "commission" => $_POST['commission'],
        "net_payable" => $_POST['net_payable'],
        "payment_voucher_no" => $_POST['payment_voucher_no'],
        "trip_complete" => $trip_complete
    ];
    syncToGoogleSheet($syncData);
}

// Handle update trip
if (isset($_POST['update_trip'])) {
    $trip_complete = isset($_POST['trip_complete']) ? 1 : 0;
    $id = $_POST['trip_id'];

    $sql = "UPDATE thirdparty_trips SET
        invoice_no='{$_POST['invoice_no']}',
        lr_no='{$_POST['lr_no']}',
        trip_date='{$_POST['trip_date']}',
        vehicle_no='{$_POST['vehicle_no']}',
        from_place='{$_POST['from_place']}',
        to_place='{$_POST['to_place']}',
        port_reach_time='{$_POST['port_reach_time']}',
        start_time='{$_POST['start_time']}',
        gate_reach_time='{$_POST['gate_reach_time']}',
        customer_left_time='{$_POST['customer_left_time']}',
        consignor='{$_POST['consignor']}',
        consignee='{$_POST['consignee']}',
        commission_receiver='{$_POST['commission_receiver']}',
        biller_name='{$_POST['biller_name']}',
        transporter_name='{$_POST['transporter_name']}',
        transporter_mob='{$_POST['transporter_mob']}',
        truck_driver_name='{$_POST['truck_driver_name']}',
        truck_driver_mob='{$_POST['truck_driver_mob']}',
        product_name='{$_POST['product_name']}',
        loading_weight='{$_POST['loading_weight']}',
        unloading_weight='{$_POST['unloading_weight']}',
        short_weight='{$_POST['short_weight']}',
        transport_rate='{$_POST['transport_rate']}',
        total_charges='{$_POST['total_charges']}',
        advance='{$_POST['advance']}',
        commission='{$_POST['commission']}',
        net_payable='{$_POST['net_payable']}',
        payment_voucher_no='{$_POST['payment_voucher_no']}',
        trip_complete='$trip_complete'
        WHERE id=$id";

    $conn->query($sql);
    echo "<script>alert('Trip updated successfully!'); window.location='thirdparty_trip.php';</script>";

    $syncData = [
        "action" => "update",
        // ✅ Fix added: use correct id
        "id" => $_POST['trip_id'],
        "invoice_no" => $_POST['invoice_no'],
        "lr_no" => $_POST['lr_no'],
        "trip_date" => $_POST['trip_date'],
        "vehicle_no" => $_POST['vehicle_no'],
        "from_place" => $_POST['from_place'],
        "to_place" => $_POST['to_place'],
        "port_reach_time" => $_POST['port_reach_time'],
        "start_time" => $_POST['start_time'],
        "gate_reach_time" => $_POST['gate_reach_time'],
        "customer_left_time" => $_POST['customer_left_time'],
        "consignor" => $_POST['consignor'],
        "consignee" => $_POST['consignee'],
        "commission_receiver" => $_POST['commission_receiver'],
        "biller_name" => $_POST['biller_name'],
        "transporter_name" => $_POST['transporter_name'],
        "transporter_mob" => $_POST['transporter_mob'],
        "truck_driver_name" => $_POST['truck_driver_name'],
        "truck_driver_mob" => $_POST['truck_driver_mob'],
        "product_name" => $_POST['product_name'],
        "loading_weight" => $_POST['loading_weight'],
        "unloading_weight" => $_POST['unloading_weight'],
        "short_weight" => $_POST['short_weight'],
        "transport_rate" => $_POST['transport_rate'],
        "total_charges" => $_POST['total_charges'],
        "advance" => $_POST['advance'],
        "commission" => $_POST['commission'],
        "net_payable" => $_POST['net_payable'],
        "payment_voucher_no" => $_POST['payment_voucher_no'],
        "trip_complete" => $trip_complete
    ];
    syncToGoogleSheet($syncData);
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM thirdparty_trips WHERE id=$id");
    echo "<script>alert('Trip deleted'); window.location='thirdparty_trip.php';</script>";
}

// Fetch records
$result = $conn->query("SELECT * FROM thirdparty_trips ORDER BY id DESC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Third Party Trips | LMS</title>
  <style>
  body {
    font-family: 'Poppins', sans-serif;
    background: #f7f9fc;
    margin: 0;
  }
  .container {
    width: 95%;
    margin: 30px auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    padding-bottom: 20px;
  }
  .header-bar {
    background: #904ee1ff;
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 12px 12px 0 0;
  }
  button {
    background: #ff8000ff;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
  }
  button:hover { background: #0056b3; }

  .table-container {
    overflow-x: auto;
    padding: 20px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
  th, td {
    border: 1px solid #eee;
    padding: 10px;
    text-align: center;
  }
  th {
    background: #904ee1ff;
    color: white;
  }
  tr:hover { background-color: #f1f1f1; }

  .action-btn {
    background: #dc3545;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
  }
  .edit-btn {
    background: #28a745;
    color: white;
  }

  /* Modal */
  .modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }
  .modal-content {
    background: white;
    padding: 25px;
    width: 80%;
    max-height: 90%;
    overflow-y: auto;
    border-radius: 10px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
  }
  .modal-content h3 {
    text-align: center;
    color: #007bff;
  }
  .form-group {
    margin-bottom: 10px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    width: 100%;
  }
  .close {
    float: right;
    font-size: 22px;
    cursor: pointer;
    color: red;
  }
  .checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 10px 0 20px;
  }
  header a {
            color: white;
            text-decoration: none;
            background: #ff4757;
            padding: 8px 15px;
            border-radius: 5px;
        }
         .sheet-btn {
    background: #28a745;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 10px;
}
.sheet-btn:hover {
    background: #1e7e34;
}

  </style>
</head>
<body>

<div class="container">
  <div class="header-bar">
    <header>
    <h1>🚛 Third Party Vehicle Trips</h1>
    <div>
        <a href="dashboard.php">⬅ Back</a>
    </div>
</header>
    <button id="openModal">+ Add Trip</button>
     <button class="sheet-btn" onclick="window.open('https://docs.google.com/spreadsheets/d/1EueKK3XpbTyMbB6R5IN12V6_dnfoQcxarpA6g_I5urM/edit?gid=942277814#gid=942277814', '_blank')">
      📄 Open Sheet
     </button>
    </form>
  </div>

  <div class="table-container">
    <table>
      <tr>
        <th>ID</th>
        <th>Invoice No</th>
        <th>LR No</th>
        <th>Date</th>
        <th>Vehicle</th>
        <th>From</th>
        <th>To</th>
        <th>Port Reach</th>
        <th>Start Time</th>
        <th>Gate Reach</th>
        <th>Customer Left</th>
        <th>Consignor</th>
        <th>Consignee</th>
        <th>Commission Receiver</th>
        <th>Biller Name</th>
        <th>Transporter Name</th>
        <th>Transporter Mob</th>
        <th>Driver Name</th>
        <th>Driver Mob</th>
        <th>Product</th>
        <th>Loading Wt</th>
        <th>Unloading Wt</th>
        <th>Short Wt</th>
        <th>Transport Rate</th>
        <th>Total</th>
        <th>Advance</th>
        <th>Commission</th>
        <th>Net Payable</th>
        <th>Payment Voucher</th>
        <th>Action</th>
      </tr>

<?php while($row = $result->fetch_assoc()): ?>
  <?php 
    $trip_complete = isset($row['trip_complete']) ? $row['trip_complete'] : 0;
    $rowColor = ($trip_complete == 1) ? '#C8E6C9' : '#FFE0B2'; // ✅ Green / Orange
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
    <td><?= $row['gate_reach_time'] ?></td>
    <td><?= $row['customer_left_time'] ?></td>
    <td><?= $row['consignor'] ?></td>
    <td><?= $row['consignee'] ?></td>
    <td><?= $row['commission_receiver'] ?></td>
    <td><?= $row['biller_name'] ?></td>
    <td><?= $row['transporter_name'] ?></td>
    <td><?= $row['transporter_mob'] ?></td>
    <td><?= $row['truck_driver_name'] ?></td>
    <td><?= $row['truck_driver_mob'] ?></td>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['loading_weight'] ?></td>
    <td><?= $row['unloading_weight'] ?></td>
    <td><?= $row['short_weight'] ?></td>
    <td><?= $row['transport_rate'] ?></td>
    <td><?= $row['total_charges'] ?></td>
    <td><?= $row['advance'] ?></td>
    <td><?= $row['commission'] ?></td>
    <td><?= $row['net_payable'] ?></td>
    <td><?= $row['payment_voucher_no'] ?></td>
    <td>
      <a href="javascript:void(0)" 
         onclick='openEditModal(<?= json_encode($row) ?>)' 
         class="edit-btn" 
         style="padding:5px 10px;text-decoration:none;">Edit</a>
      <a href="?delete=<?= $row['id'] ?>" class="action-btn" onclick="return confirm('Delete this trip?')">Delete</a>
    </td>
  </tr>
<?php endwhile; ?>
</table>
  </div>
</div>

<!-- Modal -->
<div id="tripModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Add New Trip</h3>
    <form method="POST" id="tripForm">
      <input type="hidden" name="trip_id" id="trip_id">
      <div class="form-group">
        <input type="text" name="invoice_no" placeholder="Invoice No" required>
        <input type="text" name="lr_no" placeholder="LR No" required>
        <input type="date" name="trip_date" required>
        <input type="text" name="vehicle_no" placeholder="Vehicle No" required>
        <input type="text" name="from_place" placeholder="From Place" required>
        <input type="text" name="to_place" placeholder="To Place" required>
        <input type="time" name="port_reach_time">
        <input type="time" name="start_time">
        <input type="time" name="gate_reach_time">
        <input type="time" name="customer_left_time">
        <input type="text" name="consignor" placeholder="Consignor">
        <input type="text" name="consignee" placeholder="Consignee">
        <input type="text" name="commission_receiver" placeholder="Commission Receiver">
        <input type="text" name="biller_name" placeholder="Biller Name">
        <input type="text" name="transporter_name" placeholder="Transporter Name">
        <input type="text" name="transporter_mob" placeholder="Transporter Mobile">
        <input type="text" name="truck_driver_name" placeholder="Driver Name">
        <input type="text" name="truck_driver_mob" placeholder="Driver Mobile">
        <input type="text" name="product_name" placeholder="Product Name">
        <input type="number" step="0.01" name="loading_weight" placeholder="Loading Weight">
        <input type="number" step="0.01" name="unloading_weight" placeholder="Unloading Weight">
        <input type="number" step="0.01" name="short_weight" placeholder="Short Weight">
        <input type="number" step="0.01" name="transport_rate" placeholder="Transport Rate">
        <input type="number" step="0.01" name="total_charges" placeholder="Total Charges">
        <input type="number" step="0.01" name="advance" placeholder="Advance">
        <input type="number" step="0.01" name="commission" placeholder="Commission">
        <input type="number" step="0.01" name="net_payable" placeholder="Net Payable">
        <input type="text" name="payment_voucher_no" placeholder="Payment Voucher No">
      </div>

      <div class="checkbox-group">
        <input type="checkbox" name="trip_complete" id="trip_complete" style="width:auto;">
        <label for="trip_complete" style="font-weight:500;">Trip Complete</label>
      </div>

      <button type="submit" name="add_trip">Add Trip</button>
  </div>
</div>

<script>
  const modal = document.getElementById("tripModal");
  const openModalBtn = document.getElementById("openModal");
  const closeModal = document.querySelector(".close");

  openModalBtn.onclick = () => {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
    document.querySelector('h3').innerText = "Add New Trip";
    document.querySelector('button[name="add_trip"]').name = "add_trip";
    document.querySelector('button[name="add_trip"]').innerText = "Add Trip";
    document.getElementById('tripForm').reset();
    document.getElementById('trip_id').value = "";
  };

  closeModal.onclick = () => {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  };

  window.onclick = (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      document.body.style.overflow = "auto";
    }
  };

  function openEditModal(data) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
    document.querySelector('h3').innerText = "Edit Trip";

    // Fill all fields
    for (let key in data) {
      if (document.querySelector(`[name="${key}"]`)) {
        document.querySelector(`[name="${key}"]`).value = data[key];
      }
    }

    // Checkbox
    document.getElementById('trip_complete').checked = data.trip_complete == 1;

    // Set hidden id
    document.getElementById('trip_id').value = data.id;

    // Change button for update
    const btn = document.querySelector('button[name="add_trip"]');
    btn.name = 'update_trip';
    btn.innerText = 'Update Trip';
  }
</script>

</body>
</html>
