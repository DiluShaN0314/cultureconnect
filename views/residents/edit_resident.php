<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Edit Resident</h2>
        <a href="/cultureconnect/residents" class="btn btn-secondary" title="Back"><i class="fas fa-arrow-left"></i></a>
    </div>

    <div class="activity max-width-600">
    <div class="activity max-width-600">
        <?php 
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        ?>

        <form action="/cultureconnect/residents/update" method="POST">
            <input type="hidden" name="id" value="<?php echo $resident['id']; ?>">
            <div class="form-group">
                <label for="name">Full Name <span class="asterisk">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($resident['name']); ?>" data-required="true">
                <?php if (isset($errors['name'])): ?><span class="error-text"><?php echo $errors['name']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email <span class="asterisk">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($resident['email']); ?>" data-required="true">
                <?php if (isset($errors['email'])): ?><span class="error-text"><?php echo $errors['email']; ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="area_id">Area <span class="asterisk">*</span></label>
                <select id="area_id" name="area_id" data-required="true">
                    <?php while ($area = $areas->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $area['id']; ?>" <?php if($area['id'] == $resident['area_id']) echo 'selected'; ?>>
                            <?php echo $area['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <?php if (isset($errors['area_id'])): ?><span class="error-text"><?php echo $errors['area_id']; ?></span><?php endif; ?>
            </div>
            <div class="grid-2 mb-15">
                <div class="form-group mb-0">
                    <label>Age Group <span class="asterisk">*</span></label>
                    <select name="age_group" data-required="true">
                        <option value="18-25" <?php if($resident['age_group'] == '18-25') echo 'selected'; ?>>18-25</option>
                        <option value="26-35" <?php if($resident['age_group'] == '26-35') echo 'selected'; ?>>26-35</option>
                        <option value="36-45" <?php if($resident['age_group'] == '36-45') echo 'selected'; ?>>36-45</option>
                        <option value="46+" <?php if($resident['age_group'] == '46+') echo 'selected'; ?>>46+</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label>Gender <span class="asterisk">*</span></label>
                    <select name="gender" data-required="true">
                        <option value="Male" <?php if($resident['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($resident['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if($resident['gender'] == 'Other') echo 'selected'; ?>>Other</option>
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
                        <?php foreach($interests as $interest): 
                            $isSelected = in_array($interest['id'], $user_interests);
                        ?>
                            <div class="multiselect-option <?php echo $isSelected ? 'selected' : ''; ?>">
                                <input type="checkbox" name="interests[]" value="<?php echo $interest['id']; ?>" <?php echo $isSelected ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($interest['name']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="flex-gap-10">
                <button type="submit" class="btn btn-success">Update Resident</button>
                <a href="/cultureconnect/residents" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
