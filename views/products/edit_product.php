<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Edit Product</h2>
        <a href="/cultureconnect/events" class="btn btn-secondary" title="Back"><i class="fas fa-arrow-left"></i></a>
    </div>

    <div class="activity max-width-600">
        <?php 
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST" action="/cultureconnect/products/update">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
            
            <div class="form-group">
                <label for="name">Product/Event Name <span class="asterisk">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" data-required="true">
                <?php if (isset($errors['name'])): ?><span class="error-text"><?php echo $errors['name']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="description">Description <span class="asterisk">*</span></label>
                <textarea id="description" name="description" rows="4" data-required="true"><?php echo htmlspecialchars($product['description']); ?></textarea>
                <?php if (isset($errors['description'])): ?><span class="error-text"><?php echo $errors['description']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="category">Category <span class="asterisk">*</span></label>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" data-required="true">
                <?php if (isset($errors['category'])): ?><span class="error-text"><?php echo $errors['category']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="price_category">Price Category <span class="asterisk">*</span></label>
                <select id="price_category" name="price_category" data-required="true">
                    <option value="Affordable" <?php if($product['price_category'] == 'Affordable') echo 'selected'; ?>>Affordable</option>
                    <option value="Moderate" <?php if($product['price_category'] == 'Moderate') echo 'selected'; ?>>Moderate</option>
                    <option value="Premium" <?php if($product['price_category'] == 'Premium') echo 'selected'; ?>>Premium</option>
                </select>
                <?php if (isset($errors['price_category'])): ?><span class="error-text"><?php echo $errors['price_category']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="price">Price (£) <span class="asterisk">*</span></label>
                <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" data-required="true">
                <?php if (isset($errors['price'])): ?><span class="error-text"><?php echo $errors['price']; ?></span><?php endif; ?>
            </div>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <div class="form-group">
                    <label for="sme_id">Assigned Business (SME) <span class="asterisk">*</span></label>
                    <select id="sme_id" name="sme_id" data-required="true">
                        <?php foreach($smes as $sme): ?>
                            <option value="<?php echo $sme['id']; ?>" <?php echo $product['sme_id'] == $sme['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sme['business_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['sme_id'])): ?><span class="error-text"><?php echo $errors['sme_id']; ?></span><?php endif; ?>
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
