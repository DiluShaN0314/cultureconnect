<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Add New Resident</h2>
        <a href="/cultureconnect/residents" class="btn btn-secondary" title="Back"><i class="fas fa-arrow-left"></i></a>
    </div>

    <div class="activity max-width-600">
    <div class="activity max-width-600">
        <?php 
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        ?>

        <form action="/cultureconnect/residents/store" method="POST">
            <div class="form-group">
                <label for="name">Full Name <span class="asterisk">*</span></label>
                <input type="text" id="name" name="name" data-required="true" value="<?php echo htmlspecialchars($_SESSION['old_input']['name'] ?? ''); ?>">
                <?php if (isset($errors['name'])): ?><span class="error-text"><?php echo $errors['name']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email <span class="asterisk">*</span></label>
                <input type="email" id="email" name="email" data-required="true" value="<?php echo htmlspecialchars($_SESSION['old_input']['email'] ?? ''); ?>">
                <?php if (isset($errors['email'])): ?><span class="error-text"><?php echo $errors['email']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="password">Password <span class="asterisk">*</span></label>
                <input type="password" id="password" name="password" data-required="true">
                <?php if (isset($errors['password'])): ?><span class="error-text"><?php echo $errors['password']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="area_id">Area <span class="asterisk">*</span></label>
                <select id="area_id" name="area_id" data-required="true">
                    <option value="">Select Area</option>
                    <?php while ($area = $areas->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $area['id']; ?>" <?php echo (isset($_SESSION['old_input']['area_id']) && $_SESSION['old_input']['area_id'] == $area['id']) ? 'selected' : ''; ?>>
                            <?php echo $area['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <?php if (isset($errors['area_id'])): ?><span class="error-text"><?php echo $errors['area_id']; ?></span><?php endif; ?>
            </div>
            <?php unset($_SESSION['old_input']); ?>
            <div class="grid-2 mb-15">
                <div class="form-group mb-0">
                    <label>Age Group <span class="asterisk">*</span></label>
                    <select name="age_group" data-required="true">
                        <option value="18-25">18-25</option>
                        <option value="26-35">26-35</option>
                        <option value="36-45">36-45</option>
                        <option value="46+">46+</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label>Gender <span class="asterisk">*</span></label>
                    <select name="gender" data-required="true">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="interests">Areas of Interest</label>
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
            <div class="flex-gap-10">
                <button type="submit" class="btn btn-success">Save Resident</button>
                <a href="/cultureconnect/residents" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
