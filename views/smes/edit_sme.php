<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Edit SME</h2>
        <a href="/cultureconnect/smes" class="btn btn-secondary" title="Back"><i class="fas fa-arrow-left"></i></a>
    </div>

    <div class="activity max-width-600">
        <?php 
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        ?>
        <form action="/cultureconnect/smes/update" method="POST">
            <input type="hidden" name="id" value="<?php echo $sme['id']; ?>">
            <div class="form-group">
                <label for="business_name">Business Name <span class="asterisk">*</span></label>
                <input type="text" id="business_name" name="business_name" value="<?php echo htmlspecialchars($sme['business_name']); ?>" data-required="true">
                <?php if (isset($errors['business_name'])): ?><span class="error-text"><?php echo $errors['business_name']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="contact_email">Contact Email <span class="asterisk">*</span></label>
                <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($sme['contact_email']); ?>" data-required="true">
                <?php if (isset($errors['contact_email'])): ?><span class="error-text"><?php echo $errors['contact_email']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($sme['phone']); ?>">
            </div>
            <div class="form-group">
                <label for="portfolio_link">Portfolio Link</label>
                <input type="url" id="portfolio_link" name="portfolio_link" value="<?php echo htmlspecialchars($sme['portfolio_link']); ?>">
            </div>
            <div class="flex-gap-10">
                <button type="submit" class="btn btn-success">Update SME</button>
                <a href="/cultureconnect/smes" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
