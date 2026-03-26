<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Manage Residents</h2>
        <a href="/cultureconnect/admin/users/add" class="btn btn-success">Create New User Account</a>
    </div>

    <div class="activity">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Area</th>
                    <th>Age Group</th>
                    <th>Interests</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['area_name']; ?></td>
                        <td><?php echo $row['age_group']; ?></td>
                        <td><span class="fs-12"><?php echo htmlspecialchars($row['interests'] ?? 'None'); ?></span></td>
                        <td class="text-right">
                            <a href="/cultureconnect/residents/edit?id=<?php echo $row['id']; ?>" class="btn-link-edit" style="color: #3498db; text-decoration: none; margin-right: 10px;">Edit</a>
                            <a href="/cultureconnect/residents/delete?id=<?php echo $row['id']; ?>" class="btn-link-delete" style="color: #e74c3c; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this resident?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
