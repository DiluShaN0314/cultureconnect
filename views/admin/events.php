<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Manage Events & Products</h2>
        <a href="/cultureconnect/products/add" class="btn btn-success">+ Add New Product</a>
    </div>

    <div class="activity">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Vendor</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($products) > 0): ?>
                    <?php foreach($products as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['id']); ?></td>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td>£<?php echo htmlspecialchars(number_format($p['price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($p['business_name'] ?? 'Vendor'); ?></td>
                            <td class="text-right">
                                <a href="/cultureconnect/products/edit?id=<?php echo htmlspecialchars($p['id']); ?>" class="btn-link mr-10">Edit</a>
                                <a href="/cultureconnect/products/delete?id=<?php echo htmlspecialchars($p['id']); ?>" class="btn-link-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
