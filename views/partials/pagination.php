<?php
/** @var int $pageno */
/** @var int $numPages */
/** @var string $callback */
?>
<div class="lc-pagination">
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
            <li class="page-item<?= $pageno <= 1 ? ' disabled' : '' ?>">
                <a class="page-link" href="#" <?= $pageno > 1 ? 'onclick="' . $callback . '(' . ($pageno - 1) . '); return false;"' : '' ?>>
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
            <?php for ($y = 1; $y <= $numPages; $y++): ?>
                <li class="page-item<?= $y === $pageno ? ' active' : '' ?>">
                    <a class="page-link" href="#" onclick="<?= $callback ?>(<?= $y ?>); return false;"><?= $y ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item<?= $pageno >= $numPages ? ' disabled' : '' ?>">
                <a class="page-link" href="#" <?= $pageno < $numPages ? 'onclick="' . $callback . '(' . ($pageno + 1) . '); return false;"' : '' ?>>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>
