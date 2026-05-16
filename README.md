# 63.lv React Theme

**Version:** 1.6.7  
**Theme:** WordPress / headless React-style homepage for 63.lv  
**Requires PHP:** 7.4+  
**Recommended plugins:** WP BBuilder, Polylang

## v1.6.7 SEO + minified React enhancement + JS/cache fix

- Keeps the full homepage HTML server-rendered in view-source for Google and other crawlers.
- Hydrates that existing HTML with React instead of replacing it, so Ajax search, menu, gallery/lightbox and booking scripts keep working.
- Enqueues the minified React homepage bundle: `assets/js/63lv-react-home.min.js`.
- Uses production React/ReactDOM UMD builds.
- Adds verification markers: `data-seo-source="server-rendered"`, `data-react-mode="seo-hydration"`, and `data-react-rendered="hydrated"`.


## What this package includes

- 63.lv branding, screenshot and theme metadata.
- Headless React-style homepage matching the approved preview.
- Bootstrap-grid based layout and Swiper-compatible slider/gallery structure.
- Lightbox gallery behaviour.
- Pirts booking calendar preview with selectable available dates.
- Booking selection pre-fills the contact form message.
- Solarium pricing section.
- Web development pricing section.
- Google Map section for Bauskas iela 63, Rīga.
- Ajax search modal UI.
- Responsive header/mobile menu.
- BBuilder Dynamic Form integration with theme fallback.
- Theme-side fallback registration for common `wpbb/*` blocks, so imported demo content does not show unsupported block warnings before WP BBuilder is activated.
- Demo importer under **Appearance → 63.lv Demo Import**.
- Polylang-ready demo content with LV default and EN/RU translations for pages, menus and pirts blog posts.

## Installation

1. Upload and activate `63-react-theme.zip`.
2. Install and activate **WP BBuilder**.
3. Install and activate **Polylang**.
4. Go to **Appearance → 63.lv Demo Import**.
5. Click **Import / Update 63.lv Demo**.
6. In Polylang, verify languages:
   - LV as default
   - EN
   - RU

## BBuilder notes

The theme is designed to work with WP BBuilder blocks. The contact form uses the `wpbb/dynamic-form` block when WP BBuilder is active. If BBuilder is not active, the theme registers a safe fallback for the same block namespace and renders a fallback form, preventing the WordPress editor from showing unsupported block errors.

For full admin editing of all blocks, keep WP BBuilder active.

## Translation notes

The demo importer creates LV, EN and RU versions for the main pages and translated demo posts. It links translations when Polylang is available. Theme UI strings are also registered with Polylang.

## Booking calendar

The pirts calendar is a front-end preview calendar. Available days are selectable; the selected day is added to the contact form message when booking. This can later be connected to real availability logic, custom post types, WooCommerce bookings or a BBuilder booking block.


## Logo

The theme header uses transparent `assets/images/63lv/63lv-logo-services.webp` with transparent PNG fallback. A square version is included at `assets/images/63lv/63lv-logo-square.png`.

## Favicon / touch icon

A WP-ready touch icon is included at `assets/images/63lv/touch.png` with 32px and 16px favicon variants. The demo importer attempts to set it as the WordPress site icon if no site icon is already configured.

## Modern favicon / touch icon

This build includes a redesigned modern 63.lv favicon and WP admin upload icon. Use `assets/images/63lv/touch.png` or the standalone `63lv-modern-touch.png` for WordPress **Settings → General → Site Icon**.

## Editable BBuilder homepage

From v13, the front page template first renders the WordPress page content. After running **Appearance → 63.lv Demo Import**, the homepage is filled with WP BBuilder / Gutenberg blocks so sections are editable from the admin side. The original headless static preview remains only as a fallback if the page content is empty.

The demo importer creates editable sections using:
- `wpbb/section`
- `wpbb/row`
- `wpbb/column`
- `wpbb/booking-calendar`
- `wpbb/pricecards`
- `wpbb/dynamic-form`
- `wpbb/google-map`
- Gutenberg heading, paragraph and HTML blocks

If WP BBuilder is inactive or a block is disabled, the theme registers safe fallback `wpbb/*` blocks to prevent unsupported-block warnings.

## v15 repair note

The public homepage now always renders the approved premium headless/static design so the style and JavaScript functionality stay intact.

For admin editing with BBuilder, run **Appearance → 63.lv Demo Import**. The importer creates a separate editable page named **63.lv Builder Sections** using WP BBuilder / Gutenberg blocks. This avoids breaking the public design while still providing editable BBuilder sections in the admin.

The importer also enables all WP BBuilder blocks in `wpbb_settings`, including `wpbb/dynamic-form`, `wpbb/google-map`, `wpbb/booking-calendar`, rows, columns, price cards and Bootstrap blocks.

## v18 admin block cleanup

The imported admin homepage now uses core Gutenberg Heading, Paragraph and List/List Item blocks for text/list content instead of raw HTML where possible. This makes the pirts package list and service cards easier to edit in the WordPress block editor while keeping the public premium front page unchanged.

## v19 solarium admin cleanup

The solarium price grid now uses WP BBuilder Bootstrap Row/Column/Card blocks with core Paragraph blocks for the price and label text. This removes the raw Bootstrap HTML grid from the imported admin homepage and makes each price item editable as normal blocks.

## v20 Google Map editor support

The theme now enqueues editor-side fallback registrations for `wpbb/google-map` and `wpbb/dynamic-form`. If WP BBuilder has the block disabled or does not register it early enough, the editor will still recognize the block and show an editable preview instead of the unsupported-block warning. When the real BBuilder block is available, the fallback does not overwrite it.

## v21 native Gutenberg text blocks

The imported admin homepage no longer uses raw `wp:html` blocks in the BBuilder demo content. Headings use native WordPress Heading blocks, body text uses Paragraph blocks, and lists/details use native List/List Item blocks inside BBuilder Bootstrap Row/Column/Card containers.

## v22 Gutenberg native block validation

The admin importer now writes headings, paragraphs, lists and card text using Gutenberg-valid native block serialization. Text/card content is inside core Heading, Paragraph, List/List Item and Group blocks, while layout remains BBuilder Row/Column and BBuilder functional blocks. No `wp:html` blocks are used in the imported homepage content.

## v23 admin HTML wrapper cleanup

Core Group wrappers were removed from the imported homepage cards. Text blocks are now separated as native Heading, Paragraph and List/List Item blocks inside BBuilder Card containers. This avoids visible `wp-block-group` wrapper divs in the admin source while keeping the backend content editable and structured.

## v24 section block cleanup

The imported homepage no longer uses `wpbb/section`, because some WP BBuilder installs do not register that block in the editor. Section titles/leads are now native Gutenberg Heading/Paragraph blocks, while layout stays in BBuilder Row/Column/Card blocks. This removes the unsupported-block warning for `wpbb/section`.

## v25 admin price/contact cleanup

The imported admin homepage no longer uses `wpbb/pricecards` for web pricing, because that block can be problematic in some BBuilder installs. Pricing is now built from BBuilder Row/Column/Card blocks with native Gutenberg Heading, Paragraph and Button blocks.

The contact detail list was also replaced with separate BBuilder cards and native Paragraph blocks, so the editor shows clean independent blocks instead of a list/source-style contact block.

## v26 core-only admin homepage

The imported admin homepage now avoids BBuilder blocks completely for the editable demo content. It uses WordPress core blocks only:
- Group
- Columns / Column
- Heading
- Paragraph
- List / List Item
- Button
- Shortcode

Functional areas use shortcodes:
- `[sixtythree_booking_calendar]`
- `[sixtythree_contact_form]`
- `[sixtythree_google_map]`

This avoids unsupported custom-block warnings in the WordPress editor while keeping the approved public front page design unchanged.

## v63 homepage recovery

The public homepage is locked back to the approved premium headless/static template. `front-page.php` no longer renders the assigned WordPress page content inside the public homepage, and `home.php` includes a safety fallback so the site root cannot fall back to the generic posts archive when Reading settings route through `home.php`. Editable builder content remains available through the separate imported **63.lv Builder Sections** page.
