<?php include "layouts/header.php"; ?>

<?php
// Identify the category with the highest total inventory value for the "Leading Sector" card
$topCategoryName  = 'None';
$maxCategoryValue = 0;
foreach ($categoryStats as $cat) {
    if ($cat['total_value'] > $maxCategoryValue) {
        $maxCategoryValue = $cat['total_value'];
        $topCategoryName  = $cat['icon'] . ' ' . $cat['name'];
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard-scroll-wrapper">
    <div class="container-fluid mt-2 animate-fade-in-up">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Analytics Dashboard</h2>
                <p class="text-muted mb-0">Real-time overview of the IT Asset inventory</p>
            </div>
        </div>

        <!-- Summary metric cards -->
        <div class="stats-grid animate-fade-in-up delay-1">

            <div class="stat-card stat-card-total">
                <div class="stat-card__info">
                    <span class="stat-card__label">Total Assets</span>
                    <span class="stat-card__value"><?= $dashboardStats['total_count']; ?></span>
                </div>
                <div class="stat-card__icon"><i class="bi bi-laptop"></i></div>
            </div>

            <div class="stat-card stat-card-value">
                <div class="stat-card__info">
                    <span class="stat-card__label">Inventory Value</span>
                    <span class="stat-card__value"><?= number_format($dashboardStats['total_value'], 2, '.', ','); ?> €</span>
                </div>
                <div class="stat-card__icon"><i class="bi bi-currency-euro"></i></div>
            </div>

            <div class="stat-card stat-card-avg">
                <div class="stat-card__info">
                    <span class="stat-card__label">Average Price</span>
                    <span class="stat-card__value"><?= number_format($dashboardStats['avg_price'], 2, '.', ','); ?> €</span>
                </div>
                <div class="stat-card__icon"><i class="bi bi-tag"></i></div>
            </div>

            <div class="stat-card stat-card-expensive">
                <div class="stat-card__info">
                    <span class="stat-card__label">Leading Sector</span>
                    <span class="stat-card__value text-truncate" style="max-width: 270px;" title="<?= htmlspecialchars($topCategoryName); ?>">
                        <?= htmlspecialchars($topCategoryName); ?>
                    </span>
                </div>
                <div class="stat-card__icon"><i class="bi bi-award"></i></div>
            </div>

        </div>

        <!-- Charts row 1: distribution + valuation -->
        <div class="dashboard-grid animate-fade-in-up delay-2">

            <div class="chart-card">
                <div class="chart-card__header">
                    <div>
                        <h5 class="chart-card__title"><i class="bi bi-pie-chart text-primary"></i> Asset Distribution</h5>
                        <span class="chart-card__subtitle">Number of items registered in each category</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-card__header">
                    <div>
                        <h5 class="chart-card__title"><i class="bi bi-bar-chart-line text-success"></i> Category Valuation</h5>
                        <span class="chart-card__subtitle">Total capital invested in each category (EUR)</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="categoryValuationChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Charts row 2: price ranges + top products list -->
        <div class="dashboard-grid animate-fade-in-up delay-3">

            <div class="chart-card">
                <div class="chart-card__header">
                    <div>
                        <h5 class="chart-card__title"><i class="bi bi-funnel text-warning"></i> Price Range Profiling</h5>
                        <span class="chart-card__subtitle">Distribution of products by pricing tiers</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="priceRangeChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-card__header mb-3">
                    <div>
                        <h5 class="chart-card__title"><i class="bi bi-gem text-danger"></i> Top 5 Premium Assets</h5>
                        <span class="chart-card__subtitle">Most valuable items currently in inventory</span>
                    </div>
                </div>
                <div class="expensive-list">
                    <?php if (empty($topProducts)): ?>
                        <p class="text-muted text-center py-4">No assets found in database.</p>
                    <?php else: ?>
                        <?php foreach ($topProducts as $p): ?>
                            <div class="expensive-item">
                                <div class="expensive-item__name-section">
                                    <span class="expensive-item__name" title="<?= htmlspecialchars($p['name']); ?>">
                                        <?= htmlspecialchars($p['name']); ?>
                                    </span>
                                    <span class="expensive-item__shortname">
                                        <?= htmlspecialchars($p['short_name']); ?>
                                    </span>
                                </div>
                                <div class="expensive-item__price-badge">
                                    <span class="expensive-item__price"><?= number_format($p['price'], 2, '.', ','); ?> €</span>
                                    <span class="expensive-item__cat"><?= htmlspecialchars($p['category_icon'] . ' ' . $p['category_long_name']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    // Serialize PHP data to JavaScript for client-side chart rendering
    const categoryData = <?php echo json_encode(array_map(function($cat) {
        return [
            'name'  => $cat['name'],
            'label' => $cat['icon'] . ' ' . $cat['long_name'],
            'count' => (int)$cat['product_count'],
            'value' => (float)$cat['total_value']
        ];
    }, $categoryStats)); ?>;

    const priceRanges = <?php echo json_encode([
        'Under 50 €'    => (int)$priceDistribution['under_50'],
        '50 € - 150 €'  => (int)$priceDistribution['range_50_150'],
        '150 € - 500 €' => (int)$priceDistribution['range_150_500'],
        'Over 500 €'    => (int)$priceDistribution['over_500']
    ]); ?>;

    <?php
    // Helper: replaces the alpha value in an rgba() string
    function setAlpha($rgba, $alpha) {
        return preg_replace(
            '/rgba\((\d+,\s*\d+,\s*\d+),\s*[\d.]+\)/',
            "rgba($1, $alpha)",
            $rgba
        );
    }
    ?>

    // Build a color palette keyed by category name, sourced from the database
    const categoryColorPalette = {
        <?php foreach ($categories as $category): ?>
            "<?= strtolower($category->getCategoryName()) ?>": {
                bg:     "<?= setAlpha($category->getCategoryColor(), 0.75) ?>",
                border: "<?= setAlpha($category->getCategoryColor(), 1) ?>"
            },
        <?php endforeach; ?>
    };

    const fallbackColor = { bg: 'rgba(111, 66, 193, 0.75)', border: 'rgba(111, 66, 193, 1)' };

    const labels       = categoryData.map(c => c.label);
    const counts       = categoryData.map(c => c.count);
    const values       = categoryData.map(c => c.value);
    const bgColors     = categoryData.map(c => (categoryColorPalette[c.name] || fallbackColor).bg);
    const borderColors = categoryData.map(c => (categoryColorPalette[c.name] || fallbackColor).border);

    // Shared Chart.js options applied to all three charts
    const commonChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 16,
                    font: {
                        family: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
                        size: 11,
                        weight: '500'
                    },
                    color: '#525252'
                }
            },
            tooltip: {
                backgroundColor: '#161616',
                titleFont: {
                    family: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
                    size: 13,
                    weight: 'bold'
                },
                bodyFont: {
                    family: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
                    size: 12
                },
                padding: 10,
                cornerRadius: 8,
                displayColors: true
            }
        }
    };

    // Chart 1: Doughnut — product count per category
    const ctx1 = document.getElementById('categoryDistributionChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: bgColors,
                borderColor: borderColors,
                borderWidth: 1.5,
                hoverOffset: 6
            }]
        },
        options: {
            ...commonChartOptions,
            cutout: '65%',
            plugins: {
                ...commonChartOptions.plugins,
                tooltip: {
                    ...commonChartOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            const val   = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct   = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return ` ${val} items (${pct}%)`;
                        }
                    }
                }
            }
        }
    });

    // Chart 2: Bar — total capital invested per category
    const ctx2 = document.getElementById('categoryValuationChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Capital Invested (€)',
                data: values,
                backgroundColor: bgColors.map(c => c.replace('0.75', '0.65')),
                borderColor: borderColors,
                borderWidth: 1.5,
                borderRadius: 8,
                barThickness: 28
            }]
        },
        options: {
            ...commonChartOptions,
            plugins: {
                ...commonChartOptions.plugins,
                legend: { display: false },
                tooltip: {
                    ...commonChartOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return ` Value: ${context.raw.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} €`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#525252', font: { size: 10 } }
                },
                y: {
                    grid: { color: 'rgba(0, 0, 0, 0.04)' },
                    ticks: {
                        color: '#525252',
                        font: { size: 10 },
                        callback: function(value) { return value.toLocaleString('en-US') + ' €'; }
                    }
                }
            }
        }
    });

    // Chart 3: Polar area — product count per price tier
    const ctx3 = document.getElementById('priceRangeChart').getContext('2d');
    new Chart(ctx3, {
        type: 'polarArea',
        data: {
            labels: Object.keys(priceRanges),
            datasets: [{
                data: Object.values(priceRanges),
                backgroundColor: [
                    'rgba(15, 98, 254, 0.65)',
                    'rgba(0, 217, 255, 0.65)',
                    'rgba(255, 91, 0, 0.65)',
                    'rgba(159, 24, 83, 0.65)'
                ],
                borderColor: [
                    'rgba(15, 98, 254, 1)',
                    'rgba(0, 217, 255, 1)',
                    'rgba(255, 91, 0, 1)',
                    'rgba(159, 24, 83, 1)'
                ],
                borderWidth: 1.5
            }]
        },
        options: {
            ...commonChartOptions,
            scales: {
                r: {
                    grid:       { color: 'rgba(0, 0, 0, 0.04)' },
                    angleLines: { color: 'rgba(0, 0, 0, 0.04)' },
                    ticks:      { display: false }
                }
            },
            plugins: {
                ...commonChartOptions.plugins,
                tooltip: {
                    ...commonChartOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) { return ` ${context.raw} items`; }
                    }
                }
            }
        }
    });
</script>

<?php include "layouts/footer.php"; ?>