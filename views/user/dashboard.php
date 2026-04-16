<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">

    <div class="flex-between mb-20">
        <h2>Resident Dashboard</h2>
        <div class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    </div>

    <!-- Stats Cards -->
    <div class="cards">
        <div class="card">
            <h3 class="color-primary"><?php echo htmlspecialchars($stats['area_name']); ?></h3>
            <p>Your Residing Area</p>
        </div>
        <div class="card">
            <h3 style="color: #e67e22;"><?php echo count($stats['interests']); ?></h3>
            <p>Areas of Interest</p>
            <div class="fs-12 text-muted mt-5">
                <?php echo implode(', ', array_slice($stats['interests'], 0, 3)); ?>
                <?php if(count($stats['interests']) > 3) echo '...'; ?>
            </div>
        </div>
        <div class="card">
            <h3 style="color: #27ae60;"><?php echo $stats['total_votes']; ?></h3>
            <p>Total Votes Cast</p>
        </div>
        <div class="card">
            <h3 style="color: #2980b9;"><?php echo $stats['yes_votes']; ?></h3>
            <p>Positive Impacts (Yes)</p>
        </div>
    </div>

    <!-- Quick Voting Section -->
    <div class="mb-30">
        <div class="flex-between mb-15">
            <h3>Recommend for You</h3>
            <a href="/cultureconnect/products" class="btn-link">View All Events <i class="fas fa-calendar-alt"></i></a>
        </div>
        
        <div class="grid-3">
            <?php 
            // Show top 3 products the user hasn't voted on (or just top 3)
            $suggested = array_slice($products, 0, 3);
            foreach($suggested as $p): 
                $current_vote = $user_votes[$p['id']] ?? null;
            ?>
                <div class="activity p-20 br-8">
                    <div class="badge badge-success mb-10"><?php echo htmlspecialchars($p['category']); ?></div>
                    <h4 class="m-0"><?php echo htmlspecialchars($p['name']); ?></h4>
                    <p class="fs-14 text-muted mb-15"><?php echo substr(htmlspecialchars($p['description']), 0, 60) . '...'; ?></p>
                    
                    <div class="flex-between items-center border-top p-top-15">
                        <div class="text-bold">£<?php echo number_format($p['price'], 2); ?></div>
                        <div class="flex-gap-10">
                            <form action="/cultureconnect/votes/store" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <input type="hidden" name="vote" value="Yes">
                                <button type="submit" class="btn btn-sm <?php echo $current_vote === 'Yes' ? 'btn-success' : 'btn-outline-success'; ?>">Yes</button>
                            </form>
                            <form action="/cultureconnect/votes/store" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <input type="hidden" name="vote" value="No">
                                <button type="submit" class="btn btn-sm <?php echo $current_vote === 'No' ? 'btn-danger' : 'btn-outline-danger'; ?>">No</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="activity">
        <h3>Your Voting History</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product/Service</th>
                    <th>Your Impact</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($stats['recent_activity']) > 0): ?>
                    <?php foreach ($stats['recent_activity'] as $act): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($act['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($act['activity']); ?></td>
                        <td>
                            <span class="badge <?php echo $act['status'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?> text-bold">
                                <?php echo $act['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">You haven't cast any votes yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
