<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <h2>All Community Votes</h2>

    <div class="activity">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Resident</th>
                    <th>Product/Service</th>
                    <th>Vote</th>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <th class="text-right">Actions</th>
                    <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td>
                        <span class="badge <?php echo $row['vote'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?> text-bold">
                            <?php echo $row['vote']; ?>
                        </span>
                    </td>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <td class="text-right">
                            <a href="/cultureconnect/votes/delete?id=<?php echo $row['id']; ?>" class="btn-link-danger" onclick="return confirm('Are you sure you want to delete this vote?')">Remove</a>
                        </td>
                    <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
