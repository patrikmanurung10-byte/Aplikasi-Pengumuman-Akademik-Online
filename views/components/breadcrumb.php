<!-- Breadcrumb Component -->
<?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
<nav aria-label="breadcrumb" class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="<?php echo \App\Core\View::url('/'); ?>" class="text-decoration-none">
                    <i class="bi bi-house me-1"></i>Home
                </a>
            </li>
            <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                <?php if ($index === count($breadcrumbs) - 1): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php if (isset($breadcrumb['icon'])): ?>
                            <i class="bi bi-<?php echo $breadcrumb['icon']; ?> me-1"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($breadcrumb['title']); ?>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?php echo \App\Core\View::url($breadcrumb['url']); ?>" class="text-decoration-none">
                            <?php if (isset($breadcrumb['icon'])): ?>
                                <i class="bi bi-<?php echo $breadcrumb['icon']; ?> me-1"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($breadcrumb['title']); ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
        
        <!-- Page Actions (Optional) -->
        <?php if (isset($page_actions) && !empty($page_actions)): ?>
        <div class="page-actions">
            <?php foreach ($page_actions as $action): ?>
                <a href="<?php echo \App\Core\View::url($action['url']); ?>" 
                   class="btn btn-<?php echo $action['type'] ?? 'primary'; ?> btn-sm me-2">
                    <?php if (isset($action['icon'])): ?>
                        <i class="bi bi-<?php echo $action['icon']; ?> me-1"></i>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($action['title']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</nav>
<?php endif; ?>

<style>
.breadcrumb {
    background: rgba(13, 110, 253, 0.1);
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-weight: bold;
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
    transform: translateX(2px);
    transition: all 0.2s ease;
}

.breadcrumb-item.active {
    font-weight: 600;
    color: #0d6efd;
}

.page-actions .btn:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}
</style>