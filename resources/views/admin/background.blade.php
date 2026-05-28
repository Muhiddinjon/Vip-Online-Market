<style>
    /* ── Fon rasm + qora overlay (87%) ── */
    body {
        background:
            linear-gradient(rgba(0, 0, 0, 0.87), rgba(0, 0, 0, 0.87)),
            url('/images/fon.jpg') center / cover fixed no-repeat !important;
    }

    /* ── Layout wrappers — shaffof ── */
    .fi-layout,
    .fi-body > div,
    .fi-simple-layout {
        background: transparent !important;
    }

    .fi-main,
    .fi-main-ctn {
        background: transparent !important;
    }

    /* ── Sidebar ── */
    .fi-sidebar {
        background: rgba(8, 8, 16, 0.96) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    /* ── Topbar ── */
    .fi-topbar,
    header.fi-topbar {
        background: rgba(8, 8, 16, 0.95) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    /* ── Login sahifasi ── */
    .fi-simple-layout .fi-simple-main {
        background: transparent !important;
    }

    .fi-simple-page {
        background: rgba(8, 8, 16, 0.95) !important;
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.07) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6) !important;
        padding: 2.5rem 2.25rem !important;
        width: 100% !important;
        max-width: 420px !important;
    }

    /* Login karta ichidagi bo'shliqlar */
    .fi-simple-page .fi-header {
        margin-bottom: 1.75rem !important;
    }

    .fi-simple-page .fi-fo-field-wrp {
        margin-bottom: 1.25rem !important;
    }

    .fi-simple-page .fi-btn {
        margin-top: 0.5rem !important;
    }

    /* ── Jadval / Section kartalar ── */
    .fi-ta-ctn,
    .fi-section,
    .fi-card {
        background: rgba(10, 10, 20, 0.94) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-color: rgba(255, 255, 255, 0.06) !important;
    }

    /* ── Select dropdown z-index fix ──
       backdrop-filter creates a stacking context, so a section below
       always paints over the open dropdown of a section above it.
       :focus-within elevates the active section while the select is open. */
    .fi-section {
        position: relative;
        z-index: 1;
    }
    .fi-section:focus-within {
        z-index: 20 !important;
    }

    /* ── Dashboard widgets ── */
    .fi-wi-stats-overview-stat {
        background: rgba(10, 10, 20, 0.94) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-color: rgba(255, 255, 255, 0.06) !important;
    }
</style>
