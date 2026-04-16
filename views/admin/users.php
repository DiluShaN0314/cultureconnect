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
                        <td>
                            <div class="interests-container">
                                <button class="interests-toggle" title="View Interests">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <div class="interests-snippet">
                                    <div class="snippet-header">Resident Interests</div>
                                    <div class="snippet-body">
                                        <?php if (!empty($row['interests'])): ?>
                                            <?php 
                                            $tags = explode(', ', $row['interests']);
                                            foreach($tags as $tag): 
                                            ?>
                                                <span class="interest-tag"><?php echo htmlspecialchars($tag); ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted fs-12">No interests listed.</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">
                            <a href="/cultureconnect/residents/edit?id=<?php echo $row['id']; ?>" class="btn-link-edit" style="color: #3498db; text-decoration: none; margin-right: 10px;" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="/cultureconnect/residents/delete?id=<?php echo $row['id']; ?>" class="btn-link-delete" style="color: #e74c3c; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this resident?')" title="Delete"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
