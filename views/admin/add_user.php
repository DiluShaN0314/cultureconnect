<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Create New User Account</h2>
        <a href="/cultureconnect/residents" class="btn btn-secondary" title="Back"><i class="fas fa-arrow-left"></i></a>
    </div>

    <div class="activity max-width-600">
        <?php 
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        ?>

        <form action="/cultureconnect/admin/users/store" method="POST" id="add-user-form">
            <div class="form-group">
                <label for="role">User Role <span class="asterisk">*</span></label>
                <select name="role" id="role-selector" data-required="true" onchange="toggleFields()">
                    <option value="user">Resident (User)</option>
                    <option value="sme">Small/Medium Enterprise (SME)</option>
                    <option value="admin">Administrator</option>
                </select>
                <?php if (isset($errors['role'])): ?><span class="error-text"><?php echo $errors['role']; ?></span><?php endif; ?>
            </div>

            <div class="form-group" id="name-group">
                <label id="name-label" for="name">Full Name <span class="asterisk">*</span></label>
                <input type="text" id="name" name="name" placeholder="Enter name" data-required="true" value="<?php echo htmlspecialchars($_SESSION['old_input']['name'] ?? ''); ?>">
                <?php if (isset($errors['name'])): ?><span class="error-text"><?php echo $errors['name']; ?></span><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email Address <span class="asterisk">*</span></label>
                <input type="email" id="email" name="email" placeholder="email@example.com" data-required="true" value="<?php echo htmlspecialchars($_SESSION['old_input']['email'] ?? ''); ?>">
                <?php if (isset($errors['email'])): ?><span class="error-text"><?php echo $errors['email']; ?></span><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password <span class="asterisk">*</span></label>
                <input type="password" id="password" name="password" placeholder="Set a password" data-required="true">
                <?php if (isset($errors['password'])): ?><span class="error-text"><?php echo $errors['password']; ?></span><?php endif; ?>
            </div>

            <!-- SME Fields -->
            <div id="sme-fields" style="display: none; grid-column: span 1;">
                <div class="form-group">
                    <label for="business_name">Business Name <span class="asterisk">*</span></label>
                    <input type="text" name="business_name" id="business_name" placeholder="Business name" value="<?php echo htmlspecialchars($_SESSION['old_input']['business_name'] ?? ''); ?>">
                    <?php if (isset($errors['business_name'])): ?><span class="error-text"><?php echo $errors['business_name']; ?></span><?php endif; ?>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" placeholder="Phone number" value="<?php echo htmlspecialchars($_SESSION['old_input']['phone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="portfolio_link">Portfolio Link</label>
                        <input type="url" id="portfolio_link" name="portfolio_link" placeholder="Portfolio URL" value="<?php echo htmlspecialchars($_SESSION['old_input']['portfolio_link'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            
            <?php unset($_SESSION['old_input']); ?>

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
                    <div class="custom-multiselect" id="interests-multiselect">
                        <div class="multiselect-trigger">
                            <span class="trigger-text">Select Options...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="multiselect-dropdown">
                            <?php foreach($interests as $interest): ?>
                                <div class="multiselect-option">
                                    <input type="checkbox" name="interests[]" value="<?php echo $interest['id']; ?>">
                                    <span><?php echo htmlspecialchars($interest['name']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
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
        nameLabel.innerHTML = 'Full Name <span class="asterisk">*</span>';
    } else if (role === 'sme') {
        residentFields.style.display = 'none';
        smeFields.style.display = 'block';
        nameLabel.innerHTML = 'Contact Person Name <span class="asterisk">*</span>';
        document.getElementById('business_name').dataset.required = "true";
    } else {
        residentFields.style.display = 'none';
        smeFields.style.display = 'none';
        nameLabel.innerHTML = 'Full Name <span class="asterisk">*</span>';
        document.getElementById('business_name').dataset.required = "false";
    }
}
// Initial toggle
toggleFields();
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
