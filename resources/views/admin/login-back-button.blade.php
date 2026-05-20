<div style="text-align:center; margin-top: 1.25rem;">
    <a href="/"
       style="
           display: inline-flex;
           align-items: center;
           gap: 0.35rem;
           color: rgba(255,255,255,0.45);
           font-size: 0.85rem;
           text-decoration: none;
           transition: color 0.2s;
       "
       onmouseover="this.style.color='rgba(255,255,255,0.80)'"
       onmouseout="this.style.color='rgba(255,255,255,0.45)'"
    >
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2.2" style="flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        {{ __('admin.auth.back_to_home') }}
    </a>
</div>
