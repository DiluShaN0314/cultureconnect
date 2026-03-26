<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Manage Areas</h2>
        <a href="/cultureconnect/areas/add" class="btn btn-success">Add New Area</a>
    </div>

    <div class="activity">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td class="text-right">
                            <a href="/cultureconnect/areas/edit?id=<?php echo $row['id']; ?>" class="btn-link mr-10">Edit</a>
                            <a href="/cultureconnect/areas/delete?id=<?php echo $row['id']; ?>" class="btn-link-danger" onclick="return confirm('Are you sure you want to delete this area?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
