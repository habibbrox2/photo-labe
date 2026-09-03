# PhotoLabe — 2027 UI/UX Redesign Execution Plan

Source of intent: `AI CODING AGENT MASTER PROMPT.md` + `FRONTEND_UI_AUDIT.md`
Stack (locked): Laravel 12 + Blade + Tailwind v4 + Alpine 3 + Vite · no React/Vue/Bootstrap.
Non-negotiables: **preserve all routes/controllers/models/business logic/admin/data/SEO**. Original design language only — reference sites inform UX patterns, never markup/styles.

## Principles (from the brief)
- Premium creative-studio × modern-SaaS × international B2B feel — not a "typical outsourcing" look.
- Strong typography, whitespace, editorial layouts, neutral premium backgrounds, one sophisticated accent, subtle borders, controlled radius. Avoid: generic blue corporate, excessive gradients/glass, giant round cards, template-y cards, random animation.
- High-converting, accessible, fast, responsive, SEO-clean.

## Phases

### Phase 0 — Foundation (largely DONE via the audit work)
- [x] Full codebase audit (`FRONTEND_UI_AUDIT.md`)
- [x] Design tokens: `primary` (indigo) + `accent` (violet) + `surface` palettes; raw `indigo/purple` removed (588 swaps)
- [x] Component primitives: `.btn` system, `.surface-card`, `.form-control-modern`, `.eyebrow`, `.lead`
- [x] Blade components: `<x-icon>` (46 icons), `<x-status-badge>`, `<x-section-header>`, `<x-form-field>`, `<x-empty-state>`, `<x-loading-skeleton>`, `<x-breadcrumbs>`
- [x] Global a11y: skip-to-content, focus-visible, header dropdown keyboard/ARIA, Escape handling
- [x] Dynamic cart badge · lazy/async images · responsive admin tables · admin breadcrumbs + empty states

### Phase 1 — Global chrome (next milestone, per-page review checkpoints)
- [x] Navigation: premium minimal light sticky header (warm paper + hairline border, gold accents, solid gold CTAs, refined panels); a11y work carried forward
- [x] Footer: warm-dark chapter re-skin — subtle ring panel, gold + ghost CTAs, real service links, unified gold accents
- [ ] `.page-hero` + shared inner-page hero component (editorial, image-led, minimal) — light variant landed on Services index (Phase 2); remaining inner pages still use dark `.page-hero` until the shared component ships
- [ ] Type ramp: define display/heading/body scale; adopt `.eyebrow`/`.lead` everywhere; kill emoji-in-copy drift
- [ ] Radius/shadow discipline pass (global), remove duplicated flash alerts → shared `<x-flash>`-style block

### Phase 2 — Page-by-page premium redesign (one page per iteration, review then ship)
Order: Home (hero/trust/services/bento) → Services index/show → Portfolio → Products → Quote → Contact → Checkout/Cart → Customer dashboard → Auth (align with Phase 1 chrome) → Blog.
Each iteration: static-first, preserve every form/route contract, keep Tailwind utilities, re-run `npm run build`, verify HTTP 200 on live routes.

### Phase 3 — Trust & conversion patterns
- [x] Real proof points — homepage trust strip uses real portfolio client names (+500 more, no fake logos)
- [x] Clearer service→quote conversion — every service card/index shows from-price + turnaround; per-service pricing tiers on detail pages; every inner page closes with a quote CTA
- [x] Social proof on Quote — top client testimonials (from DB) on the get-a-quote trust column
- [x] Checkout progress indicator — Cart → Checkout → Confirmation stepper on checkout
- [ ] Case-study depth: add challenge/approach/result fields to portfolio when real client work is added

### Phase 4 — Craft & quality pass
- [x] `prefers-reduced-motion` single rule (one global block)
- [x] Dead CSS trimmed: `.animate-float`, `.gradient-text`, `.glass(-dark)`, `.card-hover`, `.bento-grid`, `.btn-modern`, `.reveal`, `.img-loading`, `.input-modern`, `.dark-mode-auto`, `.glow(-purple)`, fade/scale keyframes + dark `.page-hero` (≈12 utilities) removed — none referenced in views/JS
- [x] Legacy-pattern sweep clean (no inline handlers/old colors/emoji drift in frontend)
- [ ] axe/Lighthouse pass on top 6 pages (needs browser tooling)
- [ ] Screenshot regression (Playwright) for hero pages

### Phase 5 — Styleguide & docs
- [x] `/admin/styleguide` (admin-gated) — live color tokens, type ramp, button system, form controls, status badges, icon set, empty-state + section-header previews
- [ ] README + STYLEGUIDE notes refresh

## Working agreement
- **No cloning**: all layout/type/color decisions are original; reference sites are pattern research only.
- **No functionality loss**: every changed Blade file is diffed for removed routes/forms/ids.
- **Review cadence**: after each Phase-1/2 page, the user reviews in-browser (`http://localhost:8000`) before the next page starts.
- **Safe refactors only**: CSS class swaps, token substitution, attribute additions; backend untouched unless a bug blocks the UI.

---

## Change log — palette & mood (restart point)

- **Palette:** Warm neutral + amber/gold. `primary` = warm espresso ink (buttons/links/text); `accent` = amber/gold (eyebrows, highlights, marketing CTAs, focus rings); `gray` + `surface` tokens warmed to stone/paper.
- **Mood:** Light & airy — paper surfaces, dark ink text, color used sparingly; light header/nav and light heroes, with at most 1–2 dark chapter sections.
- **Status:** Phases restarted with this palette. Phase 1 chrome (header/nav) re-skinned to the light treatment; Phase 2 page-by-page resumes from Home.

## Change log — iteration notes

- **Phase 1 complete (chrome):** light header/nav, warm-dark footer chapter, all a11y carried. `glass-dark` gone from public chrome; gradient CTAs → solid gold.
- **Phase 2 kickoff — Services index (shipped):** light editorial hero (`.page-hero-light` variant: paper, hairline, gold dot glow, breadcrumb, kicker eyebrow, display headline, trust bullets, floating sample-image collage), quiet count chips with espresso active state, flat editorial service cards (hairline, image zoom, kicker/arrow-row, meta hairline with price + turnaround), inline SVG filters for no-image placeholders, `prefers-reduced-motion` respected.
- **Phase 2 complete:** every frontend page redesigned to the light/gold system — Home, Services index/show, Portfolio index/show, Products index/show, Get a Quote, Contact, Cart, Checkout + success, Blog index/show, About, FAQ, Pricing, Before/After, CMS pages. All dark `.page-hero` heroes converted to `.page-hero-light` / `<x-page-hero>`; customer + admin panels keep their own chrome.
- **Phase 3 complete** (trust strip, service price transparency, quote social proof, checkout stepper); **Phase 4 craft pass** (dead CSS removed, single reduced-motion rule); **Phase 5** `/admin/styleguide` live.

