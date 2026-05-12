<script>
(function () {
    function playOrderSound() {
        try {
            var AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            var ctx = new AudioCtx();
            var notes = [880, 1108, 880, 1108];
            notes.forEach(function (freq, i) {
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = 'triangle';
                osc.frequency.value = freq;
                var t = ctx.currentTime + i * 0.22;
                gain.gain.setValueAtTime(0.35, t);
                gain.gain.exponentialRampToValueAtTime(0.001, t + 0.25);
                osc.start(t);
                osc.stop(t + 0.25);
            });
        } catch (e) {}
    }

    function showOrderToast() {
        var el = document.getElementById('order-alert-toast');
        if (!el) {
            el = document.createElement('div');
            el.id = 'order-alert-toast';
            el.style.cssText = [
                'position:fixed', 'top:20px', 'right:20px', 'z-index:99999',
                'background:#f59e0b', 'color:#000', 'padding:12px 20px',
                'border-radius:8px', 'font-weight:bold', 'font-size:15px',
                'box-shadow:0 4px 12px rgba(0,0,0,0.3)',
                'transition:opacity 0.5s',
            ].join(';');
            document.body.appendChild(el);
        }
        el.textContent = '🔔 Yangi buyurtma keldi!';
        el.style.opacity = '1';
        clearTimeout(el._timeout);
        el._timeout = setTimeout(function () {
            el.style.opacity = '0';
        }, 5000);
    }

    document.addEventListener('livewire:init', function () {
        Livewire.on('new-pending-order', function () {
            playOrderSound();
            showOrderToast();
        });
    });
})();
</script>
