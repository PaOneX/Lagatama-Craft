<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

class ProductService
{
    private int $perPage;

    public function __construct()
    {
        $this->perPage = (int) config('app.pagination', 12);
    }

    public function renderList(int $page, string $mode = 'list', array $params = []): string
    {
        $pageno = $page > 0 ? $page : 1;
        $offset = ($pageno - 1) * $this->perPage;

        if ($mode === 'search') {
            $term = $params['term'] ?? '';
            $total = Product::countSearchByName($term);
            $items = Product::searchByName($term, $offset, $this->perPage);
            $callback = 'searchProduct';
            $emptyMsg = $this->emptySearchHtml();
        } elseif ($mode === 'advanced') {
            $total = Product::countAdvancedSearch($params);
            $items = Product::advancedSearch($params, $offset, $this->perPage);
            $callback = 'advSearchProduct';
            $emptyMsg = $this->emptySearchHtml();
        } else {
            $total = Product::countAll();
            $items = Product::paginate($offset, $this->perPage);
            $callback = 'loadProduct';
            $emptyMsg = 'No Product Here..';
        }

        if (empty($items)) {
            return is_string($emptyMsg) ? $emptyMsg : $emptyMsg;
        }

        return $this->renderProducts($items, $pageno, (int) ceil($total / $this->perPage), $callback);
    }

    private function renderProducts(array $items, int $pageno, int $numPages, string $callback): string
    {
        ob_start();
        echo '<div class="lc-product-grid">';
        foreach ($items as $d) {
            $price = $d['price'] ?? $d['stock_price'] ?? 0;
            include base_path('views/partials/product-card.php');
        }
        echo '</div>';
        include base_path('views/partials/pagination.php');
        return (string) ob_get_clean();
    }

    private function emptySearchHtml(): string
    {
        return '<div class="lc-empty">'
            . '<i class="bi bi-search"></i>'
            . '<h2>No results found</h2>'
            . '<p>Try adjusting your search or filters.</p></div>';
    }
}
