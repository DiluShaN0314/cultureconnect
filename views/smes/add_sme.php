<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Add New SME</h2>
        <a href="/cultureconnect/smes" class="btn btn-secondary">&larr; Back to SMEs</a>
    </div>

    <div class="activity max-width-600">
        <form action="/cultureconnect/smes/store" method="POST">
            <div class="form-group">
                <label>Business Name</label>
                <input type="text" name="business_name" required>
            </div>
            <div class="form-group">
                <label>Contact Email</label>
                <input type="email" name="contact_email" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone">
            </div>
            <div class="form-group">
                <label>Portfolio Link</label>
                <input type="url" name="portfolio_link">
            </div>
            <div class="flex-gap-10">
                <button type="submit" class="btn btn-success">Save SME</button>
                <a href="/cultureconnect/smes" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
