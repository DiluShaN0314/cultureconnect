<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Edit Area</h2>
        <a href="/cultureconnect/areas" class="btn btn-secondary">&larr; Back to Areas</a>
    </div>

    <div class="activity max-width-500">
        <form action="/cultureconnect/areas/update" method="POST">
            <input type="hidden" name="id" value="<?php echo $area['id']; ?>">
            <div class="form-group">
                <label for="name">Area Name</label>
                <input type="text" id="name" name="name" value="<?php echo $area['name']; ?>" required>
            </div>
            <div class="flex-gap-10">
                <button type="submit" class="btn btn-success">Update Area</button>
                <a href="/cultureconnect/areas" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
