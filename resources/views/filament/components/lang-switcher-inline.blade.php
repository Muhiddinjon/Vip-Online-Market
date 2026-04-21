@once
<style>
.fi-lang-sw-inline select {
    appearance: none;
    -webkit-appearance: none;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 5px 32px 5px 10px;
    font-size: 0.8125rem;
    font-weight: 500;
    cursor: pointer;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%236b7280' d='M1 3l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 9px center;
    color: #374151;
    min-width: 148px;
    font-family: inherit;
    line-height: 1.5;
    transition: border-color .15s, box-shadow .15s;
}
.fi-lang-sw-inline select:hover { border-color: #9ca3af; }
.fi-lang-sw-inline select:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.12); }
.dark .fi-lang-sw-inline select { background-color: #1f2937; border-color: #374151; color: #d1d5db; }
.dark .fi-lang-sw-inline select:hover { border-color: #4b5563; }
</style>
@endonce

<div
    class="fi-lang-sw-inline"
    x-data="{}"
    x-init="$el.querySelector('select').value = ($wire.data && $wire.data.lang) ? $wire.data.lang : 'uz'"
>
    <select x-on:change="$wire.set('data.lang', $event.target.value)">
        <option value="uz">🇺🇿 O'zbekcha</option>
        <option value="en">🇬🇧 English</option>
        <option value="tr">🇹🇷 Türkçe</option>
    </select>
</div>
