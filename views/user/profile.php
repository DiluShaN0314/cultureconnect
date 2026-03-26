<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>My Profile & Settings</h2>
        <a href="/cultureconnect/user-dashboard" class="btn btn-secondary">&larr; Back to Dashboard</a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="success-message">Profile updated successfully!</div>
    <?php endif; ?>

    <div class="activity max-width-600">
        <form action="/cultureconnect/profile/update" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <?php if ($_SESSION['role'] === 'sme' && is_array($sme)): ?>
            <div class="border-top mt-10 p-top-15">
                <h3 class="mb-15">Business Profile</h3>
                <div class="form-group">
                    <label for="business_name">Business Name</label>
                    <input type="text" id="business_name" name="business_name" value="<?php echo htmlspecialchars($sme['business_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact_email">Business Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($sme['contact_email'] ?? ''); ?>" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($sme['phone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="portfolio_link">Portfolio/Website Link</label>
                        <input type="url" id="portfolio_link" name="portfolio_link" value="<?php echo htmlspecialchars($sme['portfolio_link'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($_SESSION['role'] === 'user'): ?>
            <div class="grid-2">
                <div class="form-group">
                    <label for="age_group">Age Group</label>
                    <select id="age_group" name="age_group" required>
                        <option value="18-25" <?php echo (isset($user['age_group']) && $user['age_group'] == '18-25') ? 'selected' : ''; ?>>18-25</option>
                        <option value="26-35" <?php echo (isset($user['age_group']) && $user['age_group'] == '26-35') ? 'selected' : ''; ?>>26-35</option>
                        <option value="36-45" <?php echo (isset($user['age_group']) && $user['age_group'] == '36-45') ? 'selected' : ''; ?>>36-45</option>
                        <option value="46-60" <?php echo (isset($user['age_group']) && $user['age_group'] == '46-60') ? 'selected' : ''; ?>>46-60</option>
                        <option value="60+" <?php echo (isset($user['age_group']) && $user['age_group'] == '60+') ? 'selected' : ''; ?>>60+</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="Male" <?php echo (isset($user['gender']) && $user['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($user['gender']) && $user['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo (isset($user['gender']) && $user['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        <option value="Prefer Not to Say" <?php echo (isset($user['gender']) && $user['gender'] == 'Prefer Not to Say') ? 'selected' : ''; ?>>Prefer Not to Say</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="area_id">Residing Area</label>
                <select id="area_id" name="area_id" required>
                    <?php foreach($areas as $area): ?>
                        <option value="<?php echo $area['id']; ?>" <?php echo (isset($user['area_id']) && $user['area_id'] == $area['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($area['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Areas of Interest</label>
                <div class="bg-light p-15 br-8" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <?php foreach($interests_list as $interest): ?>
                        <label class="cursor-pointer fs-14 flex items-center">
                            <input type="checkbox" name="interests[]" value="<?php echo $interest['id']; ?>" 
                                <?php echo in_array($interest['id'], $user_interests) ? 'checked' : ''; ?> class="mr-10">
                            <?php echo htmlspecialchars($interest['name']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="border-top mt-30 p-top-15">
                <h3 class="mb-15">Security</h3>
                <div class="form-group">
                    <label for="password">Change Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password" placeholder="New password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-15">Save All Changes</button>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
