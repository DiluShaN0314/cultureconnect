<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CultureConnect Dashboard</title>
<link rel="stylesheet" href="/cultureconnect/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="/cultureconnect/assets/js/script.js" defer></script>


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
    <div class="profile-dropdown">
        <div class="profile-btn">
            <i class="fas fa-user-circle fa-lg"></i>
            <span><?php echo htmlspecialchars($_SESSION['username'] ?? "User"); ?></span>
            <i class="fas fa-chevron-down fa-xs"></i>
        </div>
        <div class="dropdown-content">
            <a href="/cultureconnect/profile"><i class="fas fa-cog"></i> Profile Settings</a>
            <a href="/cultureconnect/logout" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div id="success-banner" class="success-banner">
        <i class="fas fa-check-circle"></i>
        <span><?php echo $_SESSION['success']; ?></span>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="sidebar">
    <?php if ($role === 'admin'): ?>
        <a href="/cultureconnect/residents">Residents</a>
        <a href="/cultureconnect/smes">SMEs</a>
        <a href="/cultureconnect/events">Events</a>
        <a href="/cultureconnect/areas">Areas</a>
        <a href="/cultureconnect/votes">Community Votes</a>
    <?php elseif ($role === 'sme'): ?>
        <a href="/cultureconnect/sme-dashboard">My Dashboard</a>
        <a href="/cultureconnect/events">Products & Events</a>
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