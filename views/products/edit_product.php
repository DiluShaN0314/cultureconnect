<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Edit Product</h2>
        <a href="/cultureconnect/events" class="btn btn-secondary">&larr; Back to Events</a>
    </div>

    <div class="activity max-width-600">
        <?php 
        $error = $error ?? '';
        $success = $success ?? '';
        ?>

        <?php if($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <?php if($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>

        <form method="POST" action="/cultureconnect/products/update">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
            
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
            </div>
            <div class="form-group">
                <label>Price Category</label>
                <select name="price_category" required>
                    <option value="Affordable" <?php if($product['price_category'] == 'Affordable') echo 'selected'; ?>>Affordable</option>
                    <option value="Moderate" <?php if($product['price_category'] == 'Moderate') echo 'selected'; ?>>Moderate</option>
                    <option value="Premium" <?php if($product['price_category'] == 'Premium') echo 'selected'; ?>>Premium</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price (£)</label>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <div class="form-group">
                    <label>Assigned Business (SME)</label>
                    <select name="sme_id" required>
                        <?php foreach($smes as $sme): ?>
                            <option value="<?php echo $sme['id']; ?>" <?php echo $product['sme_id'] == $sme['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sme['business_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" name="sme_id" value="<?php echo $product['sme_id']; ?>">
            <?php endif; ?>
            
            <div class="flex-gap-10">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="/cultureconnect/events" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
