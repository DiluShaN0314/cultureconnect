<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2 class="m-0 color-dark">Cultural Products & Services</h2>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/cultureconnect/products/add" class="btn btn-primary">+ Add New Product</a>
        <?php endif; ?>
    </div>

    <!-- Search and Filter Bar -->
    <div class="activity p-20 mb-20">
        <form method="GET" action="/cultureconnect/products" class="flex-between items-center flex-wrap gap-15">
            <div class="flex-grow-1 flex-gap-10">
                <input type="text" name="search" placeholder="Search products or services..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="width: 100%; border-radius: 6px; border: 1px solid #ddd; padding: 10px;">
            </div>
            <div class="flex-gap-20 items-center">
                <select name="category" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; background-color: white;">
                    <option value="">All Categories</option>
                    <option value="Art" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Art') ? 'selected' : ''; ?>>Art</option>
                    <option value="Music" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Music') ? 'selected' : ''; ?>>Music</option>
                    <option value="Theatre" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Theatre') ? 'selected' : ''; ?>>Theatre</option>
                    <option value="Digital Media" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Digital Media') ? 'selected' : ''; ?>>Digital Media</option>
                    <option value="Heritage" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Heritage') ? 'selected' : ''; ?>>Heritage</option>
                </select>
                
                <label class="flex-gap-5 items-center cursor-pointer">
                    <input type="checkbox" name="price_limit" value="200" <?php echo (isset($_GET['price_limit']) && $_GET['price_limit'] == '200') ? 'checked' : ''; ?>>
                    <span>Under £200</span>
                </label>

                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <?php if(isset($_GET['search']) || isset($_GET['category']) || isset($_GET['price_limit'])): ?>
                    <a href="/cultureconnect/products" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="grid-3">
        <?php if(count($products) > 0): ?>
            <?php foreach($products as $p): 
                $current_vote = $user_votes[$p['id']] ?? null;
            ?>
                <div class="activity p-20 br-8">
                    <div class="flex-between items-center mb-10">
                        <span class="badge badge-success"><?php echo htmlspecialchars($p['category']); ?></span>
                        <span class="fs-12 text-muted"><?php echo htmlspecialchars($p['price_category']); ?></span>
                    </div>
                    
                    <h3 class="m-0"><?php echo htmlspecialchars($p['name']); ?></h3>
                    <p class="text-muted fs-14 mb-15"><?php echo htmlspecialchars($p['description']); ?></p>
                    
                    <div class="fs-12 text-bold mb-15">
                        By: <?php echo htmlspecialchars($p['business_name'] ?? 'Local Vendor'); ?>
                    </div>

                    <div class="flex-between items-center border-top p-top-15">
                        <div class="fs-20 text-bold color-success">£<?php echo number_format($p['price'], 2); ?></div>
                        
                        <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                            <div class="flex-gap-10">
                                <form action="/cultureconnect/votes/store" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                    <input type="hidden" name="vote" value="Yes">
                                    <button type="submit" class="btn btn-sm <?php echo $current_vote === 'Yes' ? 'btn-success' : 'btn-outline-success'; ?>" title="Beneficial Impact">Yes</button>
                                </form>
                                <form action="/cultureconnect/votes/store" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                    <input type="hidden" name="vote" value="No">
                                    <button type="submit" class="btn btn-sm <?php echo $current_vote === 'No' ? 'btn-danger' : 'btn-outline-danger'; ?>" title="No Impact">No</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card text-center text-muted" style="grid-column: span 3;">
                No cultural products available at the moment.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/footer.php'); ?>
