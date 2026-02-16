@push('css_or_js')
<script>
    window.funnelTrack = function(eventName, payload = {}) {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({event: eventName, ...payload});
    }

    document.addEventListener('DOMContentLoaded', function () {
        const pageHolder = document.querySelector('[data-funnel-page]');
        const page = pageHolder ? pageHolder.getAttribute('data-funnel-page') : null;
        if (page) {
            funnelTrack('page_view', {page: page});
        }

        document.querySelectorAll('[data-track-event]').forEach((item) => {
            item.addEventListener('click', function () {
                const eventName = this.getAttribute('data-track-event');
                const payloadRaw = this.getAttribute('data-track-payload');
                let payload = {};
                try {
                    payload = payloadRaw ? JSON.parse(payloadRaw) : {};
                } catch (e) {
                    payload = {};
                }
                funnelTrack(eventName, payload);
            });
        });
    });
</script>
@endpush
