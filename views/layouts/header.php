<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CultureConnect Dashboard</title>
<link rel="stylesheet" href="/cultureconnect/assets/css/style.css">

</head>

<body>

<div class="header">
    <?php
        $role = $_SESSION['role'] ?? null;
        $home_link = '/cultureconnect/';
        if($role === 'admin') {
            $home_link = '/cultureconnect/admin-dashboard';
        } elseif ($role === 'sme') {
            $home_link = '/cultureconnect/sme-dashboard';
        } elseif ($role === 'user') {
            $home_link = '/cultureconnect/user-dashboard';
        }
    ?>
    <h2 class="m-0"><a href="<?php echo $home_link; ?>" class="logo-link">CultureConnect</a></h2>
    <div>
        <a href="/cultureconnect/profile" style="color:white; text-decoration:none; margin-right:15px;">👤 Profile</a>
        Welcome <?php echo $_SESSION['username'] ?? "User"; ?>
        | <a style="color:white;" href="/cultureconnect/logout">Logout</a>
    </div>
</div>

<div class="sidebar">
    <?php if ($role === 'admin'): ?>
        <a href="/cultureconnect/residents">Manage Residents</a>
        <a href="/cultureconnect/smes">Manage SMEs</a>
        <a href="/cultureconnect/events">Manage Events</a>
        <a href="/cultureconnect/areas">Manage Areas</a>
        <a href="/cultureconnect/votes">Community Votes</a>
    <?php elseif ($role === 'sme'): ?>
        <a href="/cultureconnect/sme-dashboard">My Dashboard</a>
        <a href="/cultureconnect/events">Manage Products & Events</a>
        <a href="/cultureconnect/products/add">Add New Item</a>
        <a href="/cultureconnect/votes">Community Feedback</a>
        <a href="/cultureconnect/profile">Settings</a>
    <?php else: ?>
        <!--<a href="/cultureconnect/user-dashboard">User Dashboard</a>-->
        <a href="/cultureconnect/products">Browse Events</a>
        <a href="/cultureconnect/votes">My Votes</a>
        <!-- Other user-specific links if any -->
    <?php endif; ?>
</div>