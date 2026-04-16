<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Add New Product</h2>
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

        <form method="POST" action="/cultureconnect/products/store">
            <div class="form-group">
                <label for="name">Product/Event Name <span class="asterisk">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['old_input']['name'] ?? ''); ?>" data-required="true">
                <?php if (isset($errors['name'])): ?><span class="error-text"><?php echo $errors['name']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="description">Description <span class="asterisk">*</span></label>
                <textarea id="description" name="description" rows="4" data-required="true"><?php echo htmlspecialchars($_SESSION['old_input']['description'] ?? ''); ?></textarea>
                <?php if (isset($errors['description'])): ?><span class="error-text"><?php echo $errors['description']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="category">Category <span class="asterisk">*</span></label>
                <input type="text" id="category" name="category" placeholder="e.g. Visual Arts, Digital Media" value="<?php echo htmlspecialchars($_SESSION['old_input']['category'] ?? ''); ?>" data-required="true">
                <?php if (isset($errors['category'])): ?><span class="error-text"><?php echo $errors['category']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="price_category">Price Category <span class="asterisk">*</span></label>
                <select id="price_category" name="price_category" data-required="true">
                    <option value="Affordable" <?php echo (isset($_SESSION['old_input']['price_category']) && $_SESSION['old_input']['price_category'] == 'Affordable') ? 'selected' : ''; ?>>Affordable</option>
                    <option value="Moderate" <?php echo (isset($_SESSION['old_input']['price_category']) && $_SESSION['old_input']['price_category'] == 'Moderate') ? 'selected' : ''; ?>>Moderate</option>
                    <option value="Premium" <?php echo (isset($_SESSION['old_input']['price_category']) && $_SESSION['old_input']['price_category'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
                </select>
                <?php if (isset($errors['price_category'])): ?><span class="error-text"><?php echo $errors['price_category']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="price">Price (£) <span class="asterisk">*</span></label>
                <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($_SESSION['old_input']['price'] ?? ''); ?>" data-required="true">
                <?php if (isset($errors['price'])): ?><span class="error-text"><?php echo $errors['price']; ?></span><?php endif; ?>
            </div>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <div class="form-group">
                    <label for="sme_id">Assigned Business (SME) <span class="asterisk">*</span></label>
                    <select id="sme_id" name="sme_id" data-required="true">
                        <option value="">Select a business</option>
                        <?php foreach($smes as $sme): ?>
                            <option value="<?php echo $sme['id']; ?>" <?php echo (isset($_SESSION['old_input']['sme_id']) && $_SESSION['old_input']['sme_id'] == $sme['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sme['business_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['sme_id'])): ?><span class="error-text"><?php echo $errors['sme_id']; ?></span><?php endif; ?>
                </div>
            <?php else: ?>
                <input type="hidden" name="sme_id" value="<?php echo $_SESSION['sme_id']; ?>">
            <?php endif; ?>

            <?php unset($_SESSION['old_input']); ?>

            <div class="flex-gap-10">
                <button type="submit" class="btn btn-primary">Create Product</button>
                <a href="/cultureconnect/events" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
