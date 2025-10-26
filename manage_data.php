<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ADD
if (isset($_POST['add'])) {
    $type = $_POST['type'];
    $name = trim($_POST['name']);

    if ($name != '') {
        $table = ($type === 'clients') ? 'clients' : 'locations';
        $stmt = $conn->prepare("INSERT IGNORE INTO $table (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }
    header("Location: manage_data.php");
    exit();
}

// DELETE
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = (int)$_GET['delete'];
    $table = ($_GET['type'] === 'clients') ? 'clients' : 'locations';
    $conn->query("DELETE FROM $table WHERE id=$id");
    header("Location: manage_data.php");
    exit();
}

// FETCH DATA
$clients = $conn->query("SELECT id, name FROM clients ORDER BY name ASC");
$locations = $conn->query("SELECT id, name FROM locations ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Clients & Locations</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f7f9fc;
      margin: 0;
      padding: 30px;
    }
    h2 {
      color: #007bff;
    }
    .container {
      display: flex;
      gap: 40px;
      flex-wrap: wrap;
    }
    .box {
      flex: 1;
      min-width: 300px;
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    input[type=text] {
      width: 70%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-right: 8px;
    }
    button {
      background: #007bff;
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover { background: #0056b3; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #eee;
      padding: 8px;
      text-align: left;
    }
    th {
      background: #007bff;
      color: white;
    }
    a.delete {
      color: #dc3545;
      text-decoration: none;
      font-weight: bold;
    }
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    .back-btn {
      background: #6c757d;
    }
    .back-btn:hover {
      background: #5a6268;
    }
  </style>
</head>
<body>
<div class="top-bar">
  <h1>⚙ Manage Clients & Locations</h1>
  <button class="back-btn" id="backBtn">⬅ Back</button>
</div>


  <div class="container">
    <div class="box">
      <h2>Clients (Consignor / Consignee)</h2>
      <form method="POST">
        <input type="hidden" name="type" value="clients">
        <input type="text" name="name" placeholder="Add new client" required>
        <button type="submit" name="add">Add</button>
      </form>
      <table>
        <tr><th>ID</th><th>Name</th><th>Action</th></tr>
        <?php while($c = $clients->fetch_assoc()) { ?>
          <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><a class="delete" href="?delete=<?= $c['id'] ?>&type=clients" onclick="return confirm('Delete this client?')">🗑 Delete</a></td>
          </tr>
        <?php } ?>
      </table>
    </div>

    <div class="box">
      <h2>Locations (From / To)</h2>
      <form method="POST">
        <input type="hidden" name="type" value="locations">
        <input type="text" name="name" placeholder="Add new location" required>
        <button type="submit" name="add">Add</button>
      </form>
      <table>
        <tr><th>ID</th><th>Name</th><th>Action</th></tr>
        <?php while($l = $locations->fetch_assoc()) { ?>
          <tr>
            <td><?= $l['id'] ?></td>
            <td><?= htmlspecialchars($l['name']) ?></td>
            <td><a class="delete" href="?delete=<?= $l['id'] ?>&type=locations" onclick="return confirm('Delete this location?')">🗑 Delete</a></td>
          </tr>
        <?php } ?>
      </table>
    </div>
  </div>

  <script>
  // Dynamically set back button target based on referring page
  const referrer = document.referrer.split("/").pop();
  const backBtn = document.getElementById("backBtn");

  if(referrer === "rented_trip.php") {
    backBtn.onclick = () => window.location.href = 'rented_trip.php';
  } else if(referrer === "thirdparty_trip.php") {
    backBtn.onclick = () => window.location.href = 'thirdparty_trip.php';
  } else {
    backBtn.onclick = () => window.history.back(); // fallback
  }
</script>
</body>
</html>
