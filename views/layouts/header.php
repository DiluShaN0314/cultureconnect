<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CultureConnect Dashboard</title>
<link rel="stylesheet" href="/cultureconnect/public/css/dashboard.css">

<style>
body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f4f6f9;
}

.header{
    background:#2c3e50;
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.sidebar{
    width:220px;
    height:100vh;
    background:#34495e;
    position:fixed;
    top:0;
    left:0;
    padding-top:60px;
}

.sidebar a{
    display:block;
    color:white;
    padding:12px 20px;
    text-decoration:none;
}

.sidebar a:hover{
    background:#1abc9c;
}

.content{
    margin-left:220px;
    padding:30px;
}

.cards{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    width:200px;
    box-shadow:0 3px 8px rgba(0,0,0,0.1);
}

.card h3{
    margin:0;
    font-size:28px;
}

.card p{
    color:gray;
}

.activity{
    margin-top:30px;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 3px 8px rgba(0,0,0,0.1);
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:10px;
    border-bottom:1px solid #ddd;
}

</style>

</head>

<body>

<div class="header">
    <h2>CultureConnect</h2>
    <div>
        Welcome <?php echo $_SESSION['username'] ?? "User"; ?>
        | <a style="color:white;" href="/cultureconnect/logout">Logout</a>
    </div>
</div>

<div class="sidebar">
    <a href="/cultureconnect/user/dashboard">Dashboard</a>
    <a href="/cultureconnect/user/profile">Profile</a>
    <a href="/cultureconnect/user/events">Events</a>
    <a href="/cultureconnect/user/bookmarks">Bookmarks</a>
</div>