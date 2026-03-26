<?php
// Products are passed from ProductController::list()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - CultureConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; margin: 0; }
        .navbar { background: #2c3e50; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        .navbar a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .navbar a:hover { text-decoration: underline; }
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: 600; display: inline-block; transition: background 0.3s; border: none; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .btn-sm { padding: 6px 10px; font-size: 12px; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-warning { background: #f39c12; }
        .btn-warning:hover { background: #d68910; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ecf0f1; }
        th { background: #34495e; color: white; font-weight: 600; }
        tr:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="navbar">
        <h2 style="margin:0; font-size: 20px;">CultureConnect Admin</h2>
        <div>
            <a href="/cultureconnect/admin-dashboard">Dashboard</a>
            <a href="/cultureconnect/logout">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="header">
            <h2 style="margin:0; color: #2c3e50;">Manage Products</h2>
            <a href="/cultureconnect/products/add" class="btn">+ Add New Product</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Vendor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($products) > 0): ?>
                    <?php foreach($products as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['id']); ?></td>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td>£<?php echo htmlspecialchars(number_format($p['price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($p['business_name'] ?? 'Vendor'); ?></td>
                            <td>
                                <a href="/cultureconnect/products/edit?id=<?php echo htmlspecialchars($p['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="/cultureconnect/products/delete?id=<?php echo htmlspecialchars($p['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #7f8c8d;">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
