/* =================== PRODUCT FORM PREVIEW ENGINE =================== */

document.addEventListener("DOMContentLoaded", function () {
    // Cache DOM elements to minimize repeated queries
    const inputName = document.getElementById("input-name");
    const inputShort = document.getElementById("input-short");
    const inputCategory = document.getElementById("input-category");
    const inputPrice = document.getElementById("input-price");

    // Preview display elements updated in real-time
    const previewTitle = document.getElementById("preview-title");
    const previewCode = document.getElementById("preview-code");
    const previewBadge = document.getElementById("preview-badge");
    const previewPrice = document.getElementById("preview-price");

    // Fail silently if preview elements don't exist (e.g., on non-form pages)
    if (!inputName || !inputShort || !inputCategory || !inputPrice) {
        return;
    }

    // Format currency using Spanish locale (1.234,56 format)
    function formatEuro(value) {
        if (value === "" || value === null || value === undefined || isNaN(value)) {
            return "0,00";
        }
        return parseFloat(value).toLocaleString("es-ES", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Update preview card whenever any input changes
    function updatePreview() {
        // Display product name or placeholder
        if (inputName.value.trim() !== "") {
            previewTitle.textContent = inputName.value;
        } else {
            previewTitle.textContent = "New Asset Name";
        }

        // Display short code in uppercase for consistency
        if (inputShort.value.trim() !== "") {
            previewCode.textContent = inputShort.value.toUpperCase();
        } else {
            previewCode.textContent = "ASSET-CODE";
        }

        // Update category badge with color-coded styling
        const selectedOption = inputCategory.options[inputCategory.selectedIndex];
        if (selectedOption && selectedOption.value !== "") {
            const rawColor = selectedOption.getAttribute("data-color") || "rgba(111, 66, 193, 0.12)";
            const categoryLabel = selectedOption.getAttribute("data-label") || "Unknown";
            
            previewBadge.style.backgroundColor = rawColor;
            previewBadge.textContent = categoryLabel;

            // Match border and text color to category for visual consistency
            let textAccentColor = "var(--primary)";
            let borderAccentColor = "rgba(15, 98, 254, 0.3)";
            
            if (rawColor.includes("255, 91")) { // components
                textAccentColor = "rgba(255, 91, 0, 1)";
                borderAccentColor = "rgba(255, 91, 0, 0.4)";
            } else if (rawColor.includes("36, 161")) { // peripherals
                textAccentColor = "rgba(36, 161, 72, 1)";
                borderAccentColor = "rgba(36, 161, 72, 0.4)";
            } else if (rawColor.includes("0, 217")) { // storage
                textAccentColor = "rgba(0, 170, 200, 1)";
                borderAccentColor = "rgba(0, 217, 255, 0.4)";
            } else if (rawColor.includes("159, 24")) { // networking
                textAccentColor = "rgba(159, 24, 83, 1)";
                borderAccentColor = "rgba(159, 24, 83, 0.4)";
            } else if (rawColor.includes("15, 98")) { // computers
                textAccentColor = "var(--primary)";
                borderAccentColor = "rgba(15, 98, 254, 0.3)";
            } else {
                textAccentColor = "var(--primary)";
                borderAccentColor = "rgba(15, 98, 254, 0.3)";
            }

            previewBadge.style.color = textAccentColor;
            previewBadge.style.border = `1px solid ${borderAccentColor}`;
        } else {
            previewBadge.textContent = "Uncategorized";
            previewBadge.style.backgroundColor = "rgba(111, 66, 193, 0.12)";
            previewBadge.style.color = "#6F42C1";
            previewBadge.style.border = "1px solid rgba(111, 66, 193, 0.3)";
        }

        // Display formatted price
        previewPrice.textContent = formatEuro(inputPrice.value);
    }

    // Listen for changes in form inputs and update preview in real-time
    inputName.addEventListener("input", updatePreview);
    inputShort.addEventListener("input", updatePreview);
    inputCategory.addEventListener("change", updatePreview);
    inputPrice.addEventListener("input", updatePreview);

    // Render preview with initial values (useful for edit forms with pre-filled data)
    updatePreview();

    // 3D card tilt effect: card follows mouse movement within preview container
    const studio = document.querySelector(".preview-studio-container");
    const cardWrap = document.querySelector(".preview-card-wrap");
    
    if (studio && cardWrap) {
        studio.addEventListener("mousemove", function (e) {
            const rect = studio.getBoundingClientRect();
            const x = e.clientX - rect.left - (rect.width / 2);
            const y = e.clientY - rect.top - (rect.height / 2);
            
            // Calculate tilt angles from mouse position (max 12 degrees for subtle effect)
            const tiltX = -(y / rect.height) * 12;
            const tiltY = (x / rect.width) * 12;
            
            cardWrap.style.transition = "transform 0.1s ease";
            cardWrap.style.transform = `rotateX(${tiltX}deg) rotateY(${tiltY}deg) translateY(-5px)`;
        });
        
        // Return to neutral position when mouse leaves preview area
        studio.addEventListener("mouseleave", function () {
            cardWrap.style.transition = "transform 0.5s ease";
            cardWrap.style.transform = "rotateX(1deg) rotateY(0deg) translateY(0px)";
        });
    }
});
