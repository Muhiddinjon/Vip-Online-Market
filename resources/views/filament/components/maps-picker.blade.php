<div
    x-data="{
        open: false,
        lat: null,
        lng: null,
        map: null,
        marker: null,

        openModal() {
            this.open = true;
            // Read existing values from the form inputs directly
            const latEl = document.querySelector('[data-map-lat]');
            const lngEl = document.querySelector('[data-map-lng]');
            if (latEl?.value) this.lat = latEl.value;
            if (lngEl?.value) this.lng = lngEl.value;
            this.$nextTick(() => this.loadLeaflet());
        },

        loadLeaflet() {
            if (window.L) { this.initMap(); return; }

            const cssId = 'leaflet-css';
            if (!document.getElementById(cssId)) {
                const link = document.createElement('link');
                link.id = cssId;
                link.rel = 'stylesheet';
                link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(link);
            }

            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.onload = () => this.initMap();
            document.head.appendChild(script);
        },

        initMap() {
            if (this.map) {
                this.map.invalidateSize();
                return;
            }

            const existingLat = parseFloat(this.lat) || 41.2995;
            const existingLng = parseFloat(this.lng) || 69.2401;

            this.map = L.map('lmap-canvas').setView([existingLat, existingLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(this.map);

            if (this.lat && this.lng) {
                this.addMarker([existingLat, existingLng]);
            }

            this.map.on('click', (e) => {
                this.lat = e.latlng.lat.toFixed(7);
                this.lng = e.latlng.lng.toFixed(7);
                this.addMarker([e.latlng.lat, e.latlng.lng]);
            });
        },

        addMarker(latlng) {
            if (this.marker) this.marker.remove();
            this.marker = L.marker(latlng).addTo(this.map);
        },

        save() {
            if (this.lat && this.lng) {
                const setField = (selector, value) => {
                    const el = document.querySelector(selector);
                    if (!el) return;
                    const nativeSet = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;
                    nativeSet.call(el, value);
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                };
                setField('[data-map-lat]', this.lat);
                setField('[data-map-lng]', this.lng);
            }
            this.open = false;
        },
    }"
>
    {{-- Trigger --}}
    <button
        type="button"
        @click="openModal()"
        style="
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px; border-radius: 8px; border: none;
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            color: #fff; font-size: 0.875rem; font-weight: 600;
            cursor: pointer; box-shadow: 0 2px 8px rgba(124,58,237,0.35);
            transition: opacity .15s;
        "
        onmouseover="this.style.opacity='0.85'"
        onmouseout="this.style.opacity='1'"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;flex-shrink:0;">
            <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
        </svg>
        Xaritadan belgilash
    </button>

    {{-- Saved coords hint --}}
    <div style="margin-top:6px;font-size:0.75rem;color:#6b7280;" x-show="lat && lng">
        📍 <span x-text="lat"></span>, <span x-text="lng"></span>
    </div>

    {{-- MODAL --}}
    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            style="position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;"
            @keydown.escape.window="open=false"
        >
            {{-- Backdrop --}}
            <div
                style="position:absolute;inset:0;background:rgba(0,0,0,0.7);backdrop-filter:blur(3px);"
                @click="open=false"
            ></div>

            {{-- Modal --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                style="
                    position:relative; width:100%; max-width:880px;
                    border-radius:14px; overflow:hidden;
                    background:#111827;
                    box-shadow:0 24px 60px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.07);
                "
                @click.stop
            >
                {{-- Header --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;background:#111827;border-bottom:1px solid rgba(255,255,255,0.08);">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#5b21b6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" style="width:20px;height:20px;">
                                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <div style="color:#f9fafb;font-weight:700;font-size:0.95rem;line-height:1.2;">Joylashuvni belgilang</div>
                            <div style="color:#6b7280;font-size:0.75rem;margin-top:1px;">Xaritaga bosib marker qo'ying</div>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="open=false"
                        style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,0.06);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#9ca3af;transition:all .15s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.12)';this.style.color='#f9fafb'"
                        onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='#9ca3af'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px;">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>

                {{-- Map --}}
                <div style="position:relative;">
                    <div id="lmap-canvas" style="width:100%;height:480px;display:block;background:#1f2937;"></div>

                    {{-- Coords overlay --}}
                    <div
                        x-show="lat"
                        style="
                            position:absolute;bottom:14px;left:14px;z-index:1000;
                            background:rgba(17,24,39,0.92);border:1px solid rgba(255,255,255,0.1);
                            border-radius:10px;padding:10px 14px;backdrop-filter:blur(10px);
                        "
                    >
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:50%;background:#34d399;animation:lmap-pulse 2s infinite;flex-shrink:0;"></div>
                            <span style="color:#a7f3d0;font-size:0.8rem;font-family:monospace;font-weight:600;" x-text="lat + ', ' + lng"></span>
                        </div>
                    </div>

                    {{-- Hint --}}
                    <div
                        x-show="!lat"
                        style="
                            position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:1000;
                            background:rgba(17,24,39,0.85);border:1px solid rgba(255,255,255,0.1);
                            border-radius:10px;padding:12px 20px;text-align:center;pointer-events:none;
                        "
                    >
                        <div style="color:#9ca3af;font-size:0.875rem;">👆 Xaritaga bosib joylashuvni belgilang</div>
                    </div>
                </div>

                {{-- Footer --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#0f172a;border-top:1px solid rgba(255,255,255,0.08);">
                    <div>
                        <span x-show="lat" style="color:#34d399;font-size:0.82rem;font-weight:500;display:flex;align-items:center;gap:5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" style="width:14px;height:14px;"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" /></svg>
                            Joylashuv tanlandi
                        </span>
                        <span x-show="!lat" style="color:#6b7280;font-size:0.82rem;">Tanlang...</span>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button
                            type="button"
                            @click="open=false"
                            style="padding:9px 18px;border-radius:8px;border:1px solid rgba(255,255,255,0.1);background:transparent;color:#9ca3af;cursor:pointer;font-size:0.875rem;font-weight:500;transition:all .15s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.06)';this.style.color='#f9fafb'"
                            onmouseout="this.style.background='transparent';this.style.color='#9ca3af'"
                        >
                            Bekor qilish
                        </button>
                        <button
                            type="button"
                            @click="save()"
                            :disabled="!lat"
                            :style="lat ? 'opacity:1;cursor:pointer;' : 'opacity:0.35;cursor:not-allowed;'"
                            style="
                                padding:9px 22px;border-radius:8px;border:none;
                                background:linear-gradient(135deg,#7c3aed,#5b21b6);
                                color:#fff;font-size:0.875rem;font-weight:600;
                                display:flex;align-items:center;gap:6px;transition:opacity .15s;
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" style="width:14px;height:14px;"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" /></svg>
                            Saqlash
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
@keyframes lmap-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
</style>
