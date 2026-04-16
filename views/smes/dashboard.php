<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="welcome-banner mb-30">
        <h1>Welcome, <?php echo htmlspecialchars($sme['business_name']); ?>!</h1>
        <p>Your cultural business workspace.</p>
    </div>

    <div class="cards">
        <div class="card">
            <h3 style="color: #3498db;"><?php echo $total_products; ?></h3>
            <p>Digital Products</p>
        </div>
        <div class="card">
            <h3 style="color: #1abc9c;"><?php echo $total_votes; ?></h3>
            <p>Community Votes</p>
        </div>
        <div class="card">
            <h3 style="color: #f1c40f;"><?php echo $approval_rate; ?>%</h3>
            <p>Approval Rate</p>
        </div>
    </div>

    <div class="grid-2 mt-30">
        <div class="activity card">
            <div class="flex-between mb-20 p-20 border-bottom">
                <h3 class="m-0">Business Portfolio</h3>
                <a href="/cultureconnect/products/add" class="btn btn-primary btn-sm">+ Add Item</a>
            </div>
            <div class="p-20">
                <p><strong>Business Name:</strong> <?php echo htmlspecialchars($sme['business_name']); ?></p>
                <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($sme['contact_email']); ?></p>
                <?php if ($sme['portfolio_link']): ?>
                    <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($sme['portfolio_link']); ?>" target="_blank"><?php echo htmlspecialchars($sme['portfolio_link']); ?></a></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="activity card">
            <div class="flex-between mb-20 p-20 border-bottom">
                <h3 class="m-0">Community Feedback</h3>
                <a href="/cultureconnect/votes" class="btn-link">Analyze All <i class="fas fa-chart-line"></i></a>
            </div>
            <div class="p-20">
                <?php if (count($recent_votes) > 0): ?>
                    <ul class="notification-list">
                        <?php foreach($recent_votes as $vote): ?>
                            <li class="mb-10 fs-14 pb-10 border-bottom">
                                <strong><?php echo htmlspecialchars($vote['product_name']); ?></strong> 
                                received a <span class="badge badge-<?php echo $vote['vote'] == 'Yes' ? 'success' : 'danger'; ?>"><?php echo $vote['vote']; ?></span> vote.
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No recent feedback received.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
