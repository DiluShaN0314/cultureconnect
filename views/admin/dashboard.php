<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <h2>Admin Dashboard Overview</h2>

    <div class="cards">
        <div class="card">
            <h3 style="color: #3498db;"><?php echo $stats['residents']; ?></h3>
            <p>Total Residents</p>
        </div>
        <div class="card">
            <h3 style="color: #1abc9c;"><?php echo $stats['smes']; ?></h3>
            <p>Total SMEs</p>
        </div>
        <div class="card">
            <h3 style="color: #f1c40f;"><?php echo $stats['products']; ?></h3>
            <p>Total Products</p>
        </div>
        <div class="card">
            <h3 style="color: #e67e22;"><?php echo $stats['votes']; ?></h3>
            <p>Total Votes</p>
        </div>
    </div>

    <div class="flex-gap-30 mt-30">
        <div class="activity flex-1">
            <h3>Recent Residents</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($stats['recent_residents'] as $res): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($res['name']); ?></td>
                            <td><?php echo htmlspecialchars($res['email']); ?></td>
                            <td><?php echo date('d M Y', strtotime($res['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="mt-15">
                <a href="/cultureconnect/residents" class="btn-link">View All Residents <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="activity flex-1">
            <h3>Recent Community Votes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Resident</th>
                        <th>Product</th>
                        <th>Vote</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($stats['recent_votes'] as $vote): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vote['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($vote['product_name']); ?></td>
                            <td>
                                <span class="badge <?php echo $vote['vote'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?> text-bold">
                                    <?php echo $vote['vote']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="mt-15">
                <a href="/cultureconnect/votes" class="btn-link">View All Votes <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
