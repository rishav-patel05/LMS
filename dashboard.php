<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LMS Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #007bff, #00c6ff);
            height: 100vh;
            color: #333;
        }

        header {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        header h1 {
            color: #007bff;
            font-size: 22px;
            letter-spacing: 1px;
        }

        header a {
            background: #ff4757;
            color: white;
            padding: 8px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px);
            gap: 40px;
        }

        .card {
            width: 280px;
            height: 180px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: 0.3s ease;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.15);
        }

        .card h2 {
            font-size: 20px;
            color: #007bff;
            margin: 10px 0;
        }

        .card p {
            color: #666;
            font-size: 14px;
        }

        .card a {
            display: inline-block;
            margin-top: 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .card a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<header>
    <h1>LMS Dashboard</h1>
    <div>
        <span>Welcome, <b><?php echo $_SESSION['username']; ?></b></span>
        <a href="logout.php">Logout</a>
    </div>
</header>

<div class="container">
    <div class="card">
        <h2>🚛 Rented Vehicle</h2>
        <p>Manage and record trips using rented vehicles</p>
        <a href="rented_trip.php">Open</a>
    </div>

    <div class="card">
        <h2>🚚 Third Party</h2>
        <p>Manage and record trips using third-party transport</p>
        <a href="thirdparty_trip.php">Open</a>
    </div>
</div>

</body>
</html>
