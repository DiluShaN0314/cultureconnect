<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Manage Events & Products</h2>
        <a href="/cultureconnect/products/add" class="btn btn-success">+ Add New Product</a>
    </div>

    <!-- Search and Filter Bar -->
    <div class="activity p-20 mb-20">
        <form method="GET" action="/cultureconnect/events" class="flex-between items-center flex-wrap gap-15">
            <div class="flex-grow-1 flex-gap-10">
                <input type="text" name="search" placeholder="Search by name or description..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="width: 100%; border-radius: 6px; border: 1px solid #ddd; padding: 10px;">
            </div>
            <div class="flex-gap-20 items-center">
                <select name="category" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; background-color: white;">
                    <option value="">All Categories</option>
                    <option value="Art" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Art') ? 'selected' : ''; ?>>Art</option>
                    <option value="Music" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Music') ? 'selected' : ''; ?>>Music</option>
                    <option value="Theatre" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Theatre') ? 'selected' : ''; ?>>Theatre</option>
                    <option value="Digital Media" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Digital Media') ? 'selected' : ''; ?>>Digital Media</option>
                    <option value="Heritage" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Heritage') ? 'selected' : ''; ?>>Heritage</option>
                </select>

                <label class="flex-gap-5 items-center cursor-pointer">
                    <input type="checkbox" name="price_limit" value="200" <?php echo (isset($_GET['price_limit']) && $_GET['price_limit'] == '200') ? 'checked' : ''; ?>>
                    <span>Under £200</span>
                </label>

                <button type="submit" class="btn btn-success">Apply Filters</button>
                <?php if(isset($_GET['search']) || isset($_GET['category']) || isset($_GET['price_limit'])): ?>
                    <a href="/cultureconnect/events" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>
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
