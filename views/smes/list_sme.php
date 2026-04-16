<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2>Manage SMEs</h2>
        <a href="/cultureconnect/smes/add" class="btn btn-success">Add New SME</a>
    </div>

    <div class="activity">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Business Name</th>
                    <th>Contact Email</th>
                    <th>Phone</th>
                    <th>Portfolio</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['business_name']; ?></td>
                        <td><?php echo $row['contact_email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><a href="<?php echo $row['portfolio_link']; ?>" target="_blank"><?php echo $row['portfolio_link']; ?></a></td>
                        <td class="text-right">
                            <a href="/cultureconnect/smes/edit?id=<?php echo $row['id']; ?>" class="btn-link mr-10" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="/cultureconnect/smes/delete?id=<?php echo $row['id']; ?>" class="btn-link-danger" onclick="return confirm('Are you sure you want to delete this SME?')" title="Delete"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
