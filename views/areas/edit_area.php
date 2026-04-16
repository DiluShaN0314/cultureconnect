<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Edit Area</h2>
        <a href="/cultureconnect/areas" class="btn btn-secondary" title="Back"><i class="fas fa-arrow-left"></i></a>
    </div>

    <div class="activity max-width-500">
        <?php 
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        ?>

        <form action="/cultureconnect/areas/update" method="POST">
            <input type="hidden" name="id" value="<?php echo $area['id']; ?>">
            <div class="form-group">
                <label for="name">Area Name <span class="asterisk">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo $area['name']; ?>" data-required="true">
                <?php if (isset($errors['name'])): ?><span class="error-text"><?php echo $errors['name']; ?></span><?php endif; ?>
            </div>
            <div class="flex-gap-10">
                <button type="submit" class="btn btn-success">Update Area</button>
                <a href="/cultureconnect/areas" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
