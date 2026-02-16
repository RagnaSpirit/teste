@push('css_or_js')
<style>
    .funnel-br {
        --fb-red: #ea1d2c;
        --fb-red-dark: #c6101f;
        --fb-ink: #1f1f1f;
        --fb-muted: #667085;
        --fb-soft: #fff5f6;
    }

    .funnel-br .hero {
        background: linear-gradient(135deg, #fff 0%, #fff5f6 70%);
        border: 1px solid #ffe3e6;
        border-radius: 24px;
        padding: 3rem 2rem;
        box-shadow: 0 10px 30px rgba(234, 29, 44, .08);
    }

    .funnel-br h1, .funnel-br h2, .funnel-br h3 { color: var(--fb-ink); font-weight: 800; }
    .funnel-br .kicker { color: var(--fb-red); font-weight: 700; text-transform: uppercase; letter-spacing: .04em; font-size: .8rem; }
    .funnel-br .lead { color: var(--fb-muted); max-width: 720px; }

    .funnel-br .cta-main {
        background: var(--fb-red);
        border-color: var(--fb-red);
        color: #fff;
        border-radius: 12px;
        font-weight: 700;
        padding: .72rem 1.15rem;
    }

    .funnel-br .cta-main:hover { background: var(--fb-red-dark); border-color: var(--fb-red-dark); color: #fff; }
    .funnel-br .cta-outline {
        border: 2px solid var(--fb-red);
        color: var(--fb-red);
        border-radius: 12px;
        font-weight: 700;
        padding: .62rem 1rem;
        background: #fff;
    }

    .funnel-br .grid-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1rem;
        margin-top: 1.25rem;
    }

    .funnel-br .card-lite {
        border: 1px solid #eee;
        border-radius: 16px;
        padding: 1rem;
        background: #fff;
        height: 100%;
    }

    .funnel-br .step-list { counter-reset: step; margin: 0; padding: 0; list-style: none; }
    .funnel-br .step-list li {
        counter-increment: step;
        margin-bottom: .75rem;
        padding: .85rem 1rem .85rem 3rem;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        position: relative;
        background: #fff;
    }
    .funnel-br .step-list li::before {
        content: counter(step);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--fb-red);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        position: absolute;
        left: .85rem;
        top: .75rem;
    }

    .funnel-br .section-box {
        border: 1px solid #f0f0f0;
        border-radius: 18px;
        padding: 1.25rem;
        margin-top: 1rem;
        background: #fff;
    }

    .funnel-br details {
        border: 1px solid #f1f1f1;
        border-radius: 12px;
        padding: .75rem .95rem;
        background: #fff;
    }

    .funnel-br details summary { cursor: pointer; font-weight: 700; }
</style>
@endpush
