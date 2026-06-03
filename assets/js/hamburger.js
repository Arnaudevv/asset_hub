(function () {
    "use strict";

    /* ── Inject the hamburger button and overlay into the DOM ── */
    function injectHamburgerUI() {
        // Button
        const btn = document.createElement("button");
        btn.className = "hamburger-btn";
        btn.setAttribute("aria-label", "Abrir menú");
        btn.setAttribute("aria-expanded", "false");
        btn.setAttribute("aria-controls", "main-sidebar");
        btn.innerHTML = `
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        `;

        // Overlay
        const overlay = document.createElement("div");
        overlay.className = "sidebar-overlay";
        overlay.setAttribute("aria-hidden", "true");

        document.body.appendChild(btn);
        document.body.appendChild(overlay);

        return { btn, overlay };
    }

    /* ── Add id to the existing sidebar (for aria-controls) ── */
    function tagSidebar() {
        const sidebar = document.querySelector(".sidebar");
        if (sidebar && !sidebar.id) {
            sidebar.id = "main-sidebar";
        }
        return sidebar;
    }

    /* ── Main logic ── */
    function init() {
        const sidebar = tagSidebar();
        if (!sidebar) return;

        const { btn, overlay } = injectHamburgerUI();

        function openMenu() {
            sidebar.classList.add("is-open");
            overlay.classList.add("is-visible");
            btn.classList.add("is-open");
            btn.setAttribute("aria-expanded", "true");
            document.body.style.overflow = "hidden"; // prevents background scroll
        }

        function closeMenu() {
            sidebar.classList.remove("is-open");
            overlay.classList.remove("is-visible");
            btn.classList.remove("is-open");
            btn.setAttribute("aria-expanded", "false");
            document.body.style.overflow = "";
        }

        // Toggle when clicking the button
        btn.addEventListener("click", function (e) {
            e.stopPropagation();
            sidebar.classList.contains("is-open") ? closeMenu() : openMenu();
        });

        // Close when clicking the overlay
        overlay.addEventListener("click", closeMenu);

        // Close when clicking outside the sidebar (without active overlay)
        document.addEventListener("click", function (e) {
            if (
                sidebar.classList.contains("is-open") &&
                !sidebar.contains(e.target) &&
                !btn.contains(e.target)
            ) {
                closeMenu();
            }
        });

        // Close with the Escape key
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && sidebar.classList.contains("is-open")) {
                closeMenu();
            }
        });

        // Close when clicking a link inside the sidebar (UX on mobile)
        sidebar.querySelectorAll("a.nav-link").forEach(function (link) {
            link.addEventListener("click", closeMenu);
        });

        // Reset state when resizing to desktop
        const mq = window.matchMedia("(min-width: 768px)");
        mq.addEventListener("change", function (e) {
            if (e.matches) {
                closeMenu();
            }
        });
    }

    // Execute when the DOM is ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();