<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>My Cultural Impact (Votes)</h2>
        <a href="/cultureconnect/products" class="btn btn-primary">Browse More Events</a>
    </div>

    <div class="activity">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product/Service</th>
                    <th>Category</th>
                    <th>Your Vote</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($votes) > 0): ?>
                    <?php foreach ($votes as $v): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($v['created_at'])); ?></td>
                        <td class="text-bold"><?php echo htmlspecialchars($v['product_name']); ?></td>
                        <td><span class="badge"><?php echo htmlspecialchars($v['category']); ?></span></td>
                        <td>
                            <span class="badge <?php echo $v['vote'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?> text-bold">
                                <?php echo $v['vote']; ?>
                            </span>
                        </td>
                        <td class="text-right">
                            <form action="/cultureconnect/votes/store" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $v['product_id']; ?>">
                                <input type="hidden" name="vote" value="<?php echo $v['vote'] === 'Yes' ? 'No' : 'Yes'; ?>">
                                <button type="submit" class="btn-link" style="border:none; cursor:pointer; background:none; font-family:inherit;">Change to <?php echo $v['vote'] === 'Yes' ? 'No' : 'Yes'; ?></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">You haven't cast any votes yet. Start by browsing local services!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
