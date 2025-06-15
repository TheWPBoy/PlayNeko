(function() {
    function initArtPlayerOnContainer(container) {
        if (!container || container.dataset.artInitialized) return;
        const videoUrl = container.dataset.videoUrl;
        const poster = container.dataset.poster;
        if (window.Artplayer && videoUrl) {
            container.dataset.artInitialized = '1';
            new Artplayer({
                container: container,
                url: videoUrl,
                poster: poster || '',
                autoplay: false,
                theme: "#f00",
                volume: 0.5,
                isLive: false,
                setting: true,
                playbackRate: true,
                fullscreen: true,
                fullscreenWeb: false,
                pip: false,
                autoOrientation: true,
                lock: true,
                flip: true,
                aspectRatio: true,
                miniProgressBar: true,
                backdrop: true,
                playsInline: true,
                autoPlayback: true,
                airplay: true,
            });
        }
    }

    function scanAndInitAll() {
        // Find all video containers that have not been initialized
        document.querySelectorAll('#video-container:not([data-art-initialized])').forEach(initArtPlayerOnContainer);
    }

    // Initial scan
    document.addEventListener('DOMContentLoaded', scanAndInitAll);

    // MutationObserver for the whole body
    const observer = new MutationObserver(() => {
        scanAndInitAll();
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // Also try to init when Artplayer script loads (in case of async load)
    if (window.Artplayer) {
        scanAndInitAll();
    } else {
        const script = document.querySelector('script[src*="artplayer"]');
        if (script) {
            script.addEventListener('load', scanAndInitAll);
        }
    }
})();