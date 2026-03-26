<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Edit Resident</h2>
        <a href="/cultureconnect/residents" class="btn btn-secondary">&larr; Back to Residents</a>
    </div>

    <div class="activity max-width-600">
        <form action="/cultureconnect/residents/update" method="POST">
            <input type="hidden" name="id" value="<?php echo $resident['id']; ?>">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($resident['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($resident['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Area</label>
                <select name="area_id" required>
                    <?php while ($area = $areas->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $area['id']; ?>" <?php if($area['id'] == $resident['area_id']) echo 'selected'; ?>>
                            <?php echo $area['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="grid-2 mb-15">
                <div class="form-group mb-0">
                    <label>Age Group</label>
                    <select name="age_group" required>
                        <option value="18-25" <?php if($resident['age_group'] == '18-25') echo 'selected'; ?>>18-25</option>
                        <option value="26-35" <?php if($resident['age_group'] == '26-35') echo 'selected'; ?>>26-35</option>
                        <option value="36-45" <?php if($resident['age_group'] == '36-45') echo 'selected'; ?>>36-45</option>
                        <option value="46+" <?php if($resident['age_group'] == '46+') echo 'selected'; ?>>46+</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="Male" <?php if($resident['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($resident['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if($resident['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Areas of Interest</label>
                <div class="grid-2 bg-light p-10 br-4">
                    <?php foreach($interests as $interest): ?>
                        <label class="font-normal cursor-pointer">
                            <input type="checkbox" name="interests[]" value="<?php echo $interest['id']; ?>" 
                                <?php if(in_array($interest['id'], $user_interests)) echo 'checked'; ?>>
                            <?php echo $interest['name']; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="flex-gap-10">
                <button type="submit" class="btn btn-success">Update Resident</button>
                <a href="/cultureconnect/residents" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
