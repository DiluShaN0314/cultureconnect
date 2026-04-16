<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Community Feedback Analysis</h2>
        <a href="/cultureconnect/sme-dashboard" class="btn btn-secondary" title="Back"><i class="fas fa-arrow-left"></i></a>
    </div>

    <div class="activity">
        <div class="p-20 border-bottom">
            <h3 class="m-0">Recent Resident Votes</h3>
            <p class="text-muted fs-14">Track how the community is responding to your cultural products and services.</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Product/Service</th>
                    <th>Category</th>
                    <th>Vote</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($votes) > 0): ?>
                    <?php foreach($votes as $v): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($v['resident_name'] ?: 'Anonymous'); ?></td>
                            <td><strong><?php echo htmlspecialchars($v['product_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($v['category']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $v['vote'] == 'Yes' ? 'success' : 'danger'; ?>">
                                    <?php echo $v['vote'] == 'Yes' ? '👍 Approved' : '👎 Disapproved'; ?>
                                </span>
                            </td>
                            <td class="text-muted fs-12"><?php echo date('M d, Y', strtotime($v['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted p-20">No feedback received yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
