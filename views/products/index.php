<?php include($_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/layouts/header.php'); ?>

<div class="content">
    <div class="flex-between mb-20">
        <h2 class="m-0 color-dark">Cultural Products & Services</h2>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/cultureconnect/products/add" class="btn btn-primary">+ Add New Product</a>
        <?php endif; ?>
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
