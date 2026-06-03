<?php
include "layouts/header.php";
require_once "models/CategoryModel.php";

// Load the category to edit using the ID passed in the URL
$id_category = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_category) {
    $category  = Category::getCategoryById($id_category);
    $name      = $category->getCategoryName();
    $long_name = $category->getCategoryLongName();
    $color     = $category->getCategoryColor();
    $icon      = $category->getCategoryIcon();

    if (!$category) {
        echo "<div class='alert alert-danger'>Category not found.</div>";
        include "layouts/footer.php";
        exit;
    }
}
?>

<link rel="stylesheet" href="assets/styles/main.css">
<link rel="stylesheet" href="assets/styles/productForm.css">
<link rel="stylesheet" href="assets/styles/categoryForm.css">

<div class="form-page add-category-view">
    <div class="container-fluid form-header">

        <!-- Page header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <div class="x">
                <h2 class="fw-bold mb-0">Edit Category Specifications</h2>
                <p class="text-muted mb-0">Modify the details of the selected category.</p>
            </div>
        </div>

    </div>

    <!-- Two-column layout: form (left) + live preview (right) -->
    <div class="form-scroll-wrapper">
        <div class="container-fluid">
            <div class="row g-3 mt-1">

                <!-- Form column -->
                <div class="col-12 col-xl-7 d-flex">
                    <div class="card premium-form-card w-100 border-0">
                        <div class="card-header-gradient">
                            <h5 class="m-0 fw-bold text-dark">Category Panel</h5>
                            <p class="text-muted small m-0 mt-1">Define the identity, color and icon for this category.</p>
                        </div>

                        <div class="card-body-custom">
                            <form action="index.php?action=editCategory" method="POST" id="category-form">
                                <!-- Hidden field carries the category ID to the controller -->
                                <input type="hidden" name="id_category" value="<?php echo $id_category; ?>">

                                <!-- Long name (slug): pre-filled with the current value -->
                                <div class="premium-field-group">
                                    <label class="premium-field-label">
                                        <i class="bi bi-hash"></i> Long Name (slug)
                                    </label>
                                    <div class="premium-input-wrapper">
                                        <i class="bi bi-fingerprint premium-input-icon"></i>
                                        <input class="premium-input-field" type="text" name="long_name" id="input-name"
                                               value="<?php echo $long_name ?>"
                                               placeholder="Ex. Components (gpu, cpu, ssd, ram, ...)"
                                               autocomplete="off"
                                               pattern="[a-z0-9_-]+"
                                               title="Lowercase letters, numbers, hyphens and underscores only"
                                               required>
                                    </div>
                                </div>

                                <!-- Display name: pre-filled with the current value -->
                                <div class="premium-field-group">
                                    <label class="premium-field-label">
                                        <i class="bi bi-tag"></i> Display Name
                                    </label>
                                    <div class="premium-input-wrapper">
                                        <i class="bi bi-fonts premium-input-icon"></i>
                                        <input class="premium-input-field" type="text" name="name" id="input-long-name"
                                               value="<?php echo ucfirst($name) ?>"
                                               placeholder="Ex. Computers & Laptops"
                                               autocomplete="off" required>
                                    </div>
                                </div>

                                <!-- Color and emoji pickers side-by-side.
                                     Hidden inputs carry the selected values on submit;
                                     JS pre-selects the category's current color and emoji. -->
                                <div class="row g-3">

                                    <div class="col-12 col-md-6">
                                        <div class="premium-field-group mb-0">
                                            <label class="premium-field-label">
                                                <i class="bi bi-palette"></i> Color
                                            </label>
                                            <input type="hidden" name="color" id="input-color" value="<?php echo $color ?>" required>
                                            <div class="color-picker-grid" id="color-picker-grid"></div>
                                            <div class="color-preview-row">
                                                <span class="color-preview-dot" id="color-preview-dot"></span>
                                                <span class="color-preview-label" id="color-preview-label"><?php echo $color ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="premium-field-group mb-0">
                                            <label class="premium-field-label">
                                                <i class="bi bi-emoji-smile"></i> Icon (Emoji)
                                            </label>
                                            <input type="hidden" name="icon" id="input-icon" value="<?php echo $icon ?>" required>
                                            <div class="emoji-picker-grid" id="emoji-picker-grid"></div>
                                            <div class="emoji-preview-row">
                                                <span class="emoji-preview-current" id="emoji-preview-current"><?php echo $icon ?></span>
                                                <span class="text-muted" style="font-size:12px;">Selected icon</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="premium-divider"></div>

                                <button type="submit" class="premium-btn-submit">
                                    <i class="bi bi-plus-circle-fill"></i> Save Specifications
                                </button>
                                <a href="index.php?action=categories" class="premium-btn-cancel">
                                    <i class="bi bi-arrow-left"></i> Cancel and return
                                </a>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Live preview column: mirrors the category card shown in categoriesView -->
                <div class="col-12 col-xl-5 d-flex">
                    <div class="preview-studio-container w-100">
                        <div class="preview-studio-bg-pattern"></div>

                        <div class="preview-studio-header">
                            <span class="preview-studio-title">
                                <i class="bi bi-eye-fill"></i> Live Preview Card
                            </span>
                            <span class="preview-studio-status">
                                <span class="pulse-dot"></span> Active Sync
                            </span>
                        </div>

                        <div class="preview-card-wrap">
                            <div class="preview-category-card" id="preview-cat-card">
                                <div class="preview-category-card__content">
                                    <div class="preview-category-card__header">
                                        <h5 class="preview-category-card__title" id="preview-cat-title">Category Name</h5>
                                        <span class="preview-category-card__emoji" id="preview-cat-emoji">📦</span>
                                    </div>
                                    <div class="preview-category-card__footer">
                                        <div class="preview-cat-buttons">
                                            <button type="button" class="preview-cat-btn preview-cat-btn--configure">
                                                <i class="bi bi-gear"></i> Configure
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="preview-tip">
                            <i class="bi bi-info-circle-fill text-primary"></i>
                            As you configure, the card updates to match the style in your Categories view.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<style>
    /* Color picker */
    .color-picker-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 7px;
        max-height: 80px;
        overflow-y: auto;
        scrollbar-width: thin;
        padding: 7px;
    }

    .color-swatch {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
        flex-shrink: 0;
    }

    .color-swatch:hover {
        transform: scale(1.2);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .color-swatch.selected {
        border-color: var(--text-primary);
        box-shadow: 0 0 0 3px rgba(0,0,0,0.12);
        transform: scale(1.1);
    }

    .color-preview-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 5px;
    }

    .color-preview-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1.5px solid rgba(0,0,0,0.1);
        display: inline-block;
        flex-shrink: 0;
        background-color: rgba(15, 98, 254, 0.12);
    }

    .color-preview-label {
        font-size: 10px;
        font-family: var(--font-mono);
        color: var(--text-secondary);
    }

    /* Emoji picker */
    .emoji-picker-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 7px;
        max-height: 80px;
        overflow-y: auto;
        padding: 2px;
        scrollbar-width: thin;
    }

    .emoji-btn {
        font-size: 20px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 2px solid transparent;
        background: var(--bg-secondary);
        cursor: pointer;
        transition: transform 0.15s ease, border-color 0.15s ease, background 0.15s ease;
    }

    .emoji-btn:hover {
        transform: scale(1.2);
        background: rgba(15, 98, 254, 0.07);
    }

    .emoji-btn.selected {
        border-color: var(--primary);
        background: rgba(15, 98, 254, 0.1);
        transform: scale(1.1);
    }

    .emoji-preview-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 5px;
    }

    .emoji-preview-current {
        font-size: 24px;
        line-height: 1;
    }

    /* Preview card: compact replica of .category-card from categoriesView */
    .preview-category-card {
        border-radius: 20px;
        padding: 20px 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        min-height: 220px;
        width: 100%;
        max-width: 260px;
        border: none;
        position: relative;
        overflow: hidden;
        margin-left: 25px;
        box-shadow: 0 12px 32px rgba(0,0,0,0.12);
        transition: background 0.3s ease, border-color 0.3s ease;
        background: linear-gradient(135deg, rgba(15, 98, 254, 0.08) 0%, rgba(69, 137, 255, 0.06) 100%);
        border-left: 4px solid rgba(15, 98, 254, 0.3);
    }

    .preview-category-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.3) 100%);
        pointer-events: none;
    }

    .preview-category-card__content {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
    }

    .preview-category-card__header {
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        width: 100%;
    }

    .preview-category-card__title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: -0.4px;
        text-transform: capitalize;
        color: rgba(15, 98, 254, 0.9);
        transition: color 0.3s ease;
    }

    .preview-category-card__emoji {
        font-size: 65px;
        line-height: 1;
        display: block;
        margin: 35px 0px 30px 0px;
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
    }

    .preview-category-card__footer {
        width: 100%;
        margin-top: auto;
        padding-top: 12px;
    }

    .preview-cat-buttons { width: 100%; display: flex; }

    .preview-cat-btn {
        flex: 1;
        padding: 8px 14px;
        border: 1.5px solid rgba(107,114,128,0.2);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        background: rgba(107,114,128,0.08);
        color: rgba(107,114,128,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: not-allowed;
        opacity: 0.7;
    }

    /* Spacing overrides to make the form more compact in this view */
    .add-category-view .card-body-custom      { padding: 16px 24px !important; }
    .add-category-view .card-header-gradient  { padding: 12px 24px !important; }
    .add-category-view .premium-field-group   { margin-bottom: 12px; }
    .add-category-view .premium-divider       { margin: 10px 0 !important; }
    .add-category-view.form-page .form-scroll-wrapper { padding-top: 0; }
</style>


<script>
    // Available RGBA color swatches (extend this list to add more options)
    const COLOR_PALETTE = [
        'rgba(15, 98, 254, 0.12)',  'rgba(15, 98, 254, 0.20)',
        'rgba(69, 137, 255, 0.15)', 'rgba(0, 80, 200, 0.12)',
        'rgba(255, 91, 0, 0.12)',   'rgba(255, 91, 0, 0.20)',
        'rgba(255, 138, 61, 0.15)', 'rgba(230, 60, 0, 0.12)',
        'rgba(36, 161, 72, 0.13)',  'rgba(36, 161, 72, 0.22)',
        'rgba(66, 190, 101, 0.15)', 'rgba(0, 128, 50, 0.12)',
        'rgba(0, 217, 255, 0.14)',  'rgba(0, 217, 255, 0.22)',
        'rgba(0, 180, 216, 0.15)',  'rgba(0, 150, 200, 0.12)',
        'rgba(220, 38, 38, 0.12)',  'rgba(220, 38, 38, 0.20)',
        'rgba(111, 66, 193, 0.12)', 'rgba(111, 66, 193, 0.22)',
        'rgba(159, 24, 83, 0.12)',  'rgba(159, 24, 83, 0.20)',
        'rgba(230, 180, 0, 0.15)',  'rgba(255, 210, 0, 0.18)',
        'rgba(107, 114, 128, 0.12)','rgba(107, 114, 128, 0.22)',
    ];

    // Available emoji icons (extend this list to add more options)
    const EMOJI_LIST = [
        '💻','🖥️','⌨️','🖱️','🖨️',
        '📱','📷','📸','🎮','🕹️',
        '🔌','🔋','💾','💿','📀',
        '🖧','📡','🔭','📟','📠',
        '🛰️','🔦','💡','🔧','🔩',
        '⚙️','🛠️','📦','🗄️','🗃️',
        '📁','📂','🗂️','📊','📈',
        '🖲️','🖱️','💽','📼','📺',
        '📻','⌚','🔒','🔓','🛡️',
        '🔐','🧲','🔬','⚡','🌐',
    ];

    // Initialise with the category's current values (injected by PHP)
    let currentColor = '<?php echo $color ?>';
    let currentEmoji = '<?php echo $icon ?>';

    // Build color swatch grid; pre-select the swatch matching currentColor
    const colorGrid = document.getElementById('color-picker-grid');

    COLOR_PALETTE.forEach(color => {
        const swatch = document.createElement('div');
        swatch.className             = 'color-swatch' + (color === currentColor ? ' selected' : '');
        swatch.style.backgroundColor = color;
        swatch.style.boxShadow       = 'inset 0 0 0 1px rgba(0,0,0,0.08)';
        swatch.title                 = color;
        swatch.dataset.color         = color;

        swatch.addEventListener('click', () => {
            document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
            swatch.classList.add('selected');
            currentColor = color;

            document.getElementById('input-color').value                        = color;
            document.getElementById('color-preview-dot').style.backgroundColor  = color;
            document.getElementById('color-preview-label').textContent           = color;

            updatePreviewCard();
        });

        colorGrid.appendChild(swatch);
    });

    // Initialise color dot with the category's current color
    document.getElementById('color-preview-dot').style.backgroundColor = currentColor;
    document.getElementById('color-preview-label').textContent         = currentColor;

    // Build emoji picker grid; pre-select the emoji matching currentEmoji
    const emojiGrid = document.getElementById('emoji-picker-grid');

    EMOJI_LIST.forEach(emoji => {
        const btn      = document.createElement('button');
        btn.type       = 'button'; // prevent accidental form submit
        btn.className  = 'emoji-btn' + (emoji === currentEmoji ? ' selected' : '');
        btn.textContent = emoji;
        btn.title       = emoji;

        btn.addEventListener('click', () => {
            document.querySelectorAll('.emoji-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            currentEmoji = emoji;

            document.getElementById('input-icon').value                  = emoji;
            document.getElementById('emoji-preview-current').textContent = emoji;

            updatePreviewCard();
        });

        emojiGrid.appendChild(btn);
    });

    // Redraws the preview card whenever color, emoji, or display name changes.
    // Derives border and text colors by replacing the alpha component of the selected RGBA string.
    function updatePreviewCard() {
        const card    = document.getElementById('preview-cat-card');
        const title   = document.getElementById('preview-cat-title');
        const emoji   = document.getElementById('preview-cat-emoji');
        const nameVal = document.getElementById('input-long-name').value.trim() || 'Category Name';

        const borderColor = currentColor.replace(/[\d.]+\)$/, '0.30)');
        const textColor   = currentColor.replace(/[\d.]+\)$/, '0.90)');

        card.style.background      = `linear-gradient(135deg, ${currentColor} 0%, ${currentColor.replace(/[\d.]+\)$/, '0.06)')} 100%)`;
        card.style.borderLeftColor = borderColor;

        title.textContent = nameVal;
        title.style.color = textColor;
        emoji.textContent = currentEmoji;
    }

    document.getElementById('input-long-name').addEventListener('input', updatePreviewCard);

    updatePreviewCard();
</script>

<?php include "layouts/footer.php"; ?>