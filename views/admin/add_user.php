<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Create New User Account</h2>
        <a href="/cultureconnect/residents" class="btn btn-secondary">&larr; Back to Users</a>
    </div>

    <div class="activity max-width-600">
        <form action="/cultureconnect/admin/users/store" method="POST" id="add-user-form">
            <div class="form-group">
                <label for="role">User Role</label>
                <select name="role" id="role-selector" required onchange="toggleFields()">
                    <option value="user">Resident (User)</option>
                    <option value="sme">Small/Medium Enterprise (SME)</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div class="form-group" id="name-group">
                <label id="name-label">Full Name</label>
                <input type="text" name="name" placeholder="Enter name" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Set a password" required>
            </div>

            <!-- SME Fields -->
            <div id="sme-fields" style="display: none; grid-column: span 1;">
                <div class="form-group">
                    <label>Business Name</label>
                    <input type="text" name="business_name" id="business_name" placeholder="Business name">
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="Phone number">
                    </div>
                    <div class="form-group">
                        <label>Portfolio Link</label>
                        <input type="url" name="portfolio_link" placeholder="Portfolio URL">
                    </div>
                </div>
            </div>

            <!-- Resident-specific fields -->
            <div id="resident-fields">
                <div class="grid-2">
                    <div class="form-group">
                        <label>Age Group</label>
                        <select name="age_group">
                            <option value="18-25">18-25</option>
                            <option value="26-35">26-35</option>
                            <option value="36-45">36-45</option>
                            <option value="46-60">46-60</option>
                            <option value="60+">60+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                            <option value="Prefer Not to Say">Prefer Not to Say</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Residing Area</label>
                    <select name="area_id">
                        <?php foreach ($areas as $area): ?>
                            <option value="<?php echo $area['id']; ?>"><?php echo htmlspecialchars($area['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Areas of Interest</label>
                    <div class="activity p-15 br-8 bg-light" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                        <?php foreach($interests as $interest): ?>
                            <label class="cursor-pointer fs-14 flex items-center">
                                <input type="checkbox" name="interests[]" value="<?php echo $interest['id']; ?>" class="mr-10">
                                <?php echo htmlspecialchars($interest['name']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="flex-gap-10 mt-30">
                <button type="submit" class="btn btn-success">Create User Account</button>
                <a href="/cultureconnect/residents" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFields() {
    const role = document.getElementById('role-selector').value;
    const residentFields = document.getElementById('resident-fields');
    const smeFields = document.getElementById('sme-fields');
    const nameLabel = document.getElementById('name-label');
    
    if (role === 'user') {
        residentFields.style.display = 'block';
        smeFields.style.display = 'none';
        nameLabel.textContent = 'Full Name';
    } else if (role === 'sme') {
        residentFields.style.display = 'none';
        smeFields.style.display = 'block';
        nameLabel.textContent = 'Contact Person Name';
        document.getElementById('business_name').required = true;
    } else {
        residentFields.style.display = 'none';
        smeFields.style.display = 'none';
        nameLabel.textContent = 'Full Name';
    }
}
// Initial toggle
toggleFields();
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
