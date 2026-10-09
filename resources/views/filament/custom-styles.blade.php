<style>
    /* Custom Luxury Theme Overrides for Zyricz Commerce Admin */
    :root {
        --zyricz-gold: #f59e0b;
        --zyricz-gold-dark: #b45309;
    }

    body {
        letter-spacing: -0.011em;
    }

    /* Topbar Glassmorphism & Centering */
    .fi-topbar {
        backdrop-filter: blur(12px) !important;
        background-color: rgba(255, 255, 255, 0.88) !important;
        border-bottom: 1px solid rgba(228, 228, 231, 0.7) !important;
    }
    .dark .fi-topbar {
        background-color: rgba(18, 18, 21, 0.88) !important;
        border-bottom: 1px solid rgba(39, 39, 42, 0.7) !important;
    }

    /* Brand Logo alignment in sidebar and topbar */
    .fi-logo {
        display: inline-flex !important;
        align-items: center !important;
        height: auto !important;
    }

    /* Topbar Custom Action Controls */
    .zyricz-topbar-actions {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-right: 6px !important;
    }

    .zyricz-topbar-actions svg {
        width: 13px !important;
        height: 13px !important;
        min-width: 13px !important;
        max-width: 13px !important;
        min-height: 13px !important;
        max-height: 13px !important;
        display: inline-block !important;
        vertical-align: middle !important;
    }

    /* Storefront Button */
    .zyricz-storefront-btn {
        background-color: #ffffff !important;
        color: #27272a !important;
        border: 1px solid #e4e4e7 !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    }
    .zyricz-storefront-btn:hover {
        background-color: #fef3c7 !important;
        color: #b45309 !important;
        border-color: #f59e0b !important;
        transform: translateY(-1px);
    }
    .dark .zyricz-storefront-btn {
        background-color: #27272a !important;
        color: #f4f4f5 !important;
        border: 1px solid #3f3f46 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
    }
    .dark .zyricz-storefront-btn:hover {
        background-color: #3f3f46 !important;
        color: #fbbf24 !important;
        border-color: #f59e0b !important;
        transform: translateY(-1px);
    }

    /* Responsive adjustments for topbar badge */
    @media (max-width: 640px) {
        .zyricz-live-badge {
            display: none !important;
        }
        .zyricz-logo-text {
            display: none !important;
        }
    }

    /* Sidebar Refinement */
    .fi-sidebar {
        border-right: 1px solid rgba(228, 228, 231, 0.8) !important;
    }
    .dark .fi-sidebar {
        border-right: 1px solid rgba(39, 39, 42, 0.8) !important;
        background-color: #0c0c0e !important;
    }

    /* Sidebar Navigation Items */
    .fi-sidebar-item-active .fi-sidebar-item-btn {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.08) 100%) !important;
        border-left: 3px solid #f59e0b !important;
        font-weight: 600 !important;
    }

    /* Card & Table Polish */
    .fi-section, .fi-ta-ctn {
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05) !important;
        border: 1px solid rgba(228, 228, 231, 0.8) !important;
    }
    .dark .fi-section, .dark .fi-ta-ctn {
        box-shadow: 0 8px 30px -4px rgba(0, 0, 0, 0.4) !important;
        border: 1px solid rgba(39, 39, 42, 0.8) !important;
        background-color: #121215 !important;
    }

    /* Table row hover smoothness */
    .fi-ta-row {
        transition: background-color 0.15s ease-in-out;
    }

    /* Buttons micro-interaction */
    .fi-btn {
        border-radius: 0.65rem !important;
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .fi-btn:hover {
        transform: translateY(-1px);
    }
</style>
