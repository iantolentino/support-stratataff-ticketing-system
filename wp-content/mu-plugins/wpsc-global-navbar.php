<?php
/**
 * Plugin Name: SupportCandy Global Site Navbar
 * Description: Scope: Home/Support page ONLY, unified with the Tickets page. Forms and IT Forms
 *              are explicitly out of scope and untouched by this file.
 *              Extends the Tickets page's navbar look (reuses the existing .wpsc-header /
 *              .wpsc-tickets-nav classes and colors already loaded site-wide by SupportCandy's own
 *              framework/style.css) to the Home page. The logo is inserted inside .wpsc-header via
 *              JS on BOTH pages identically (the Tickets page's original separate logo Elementor
 *              widget, which sat beside the navbar instead of inside it, is hidden in favor of this
 *              single shared mechanism) so the two pages place it in exactly the same spot.
 *              The Home page's live "Shift Assignments" display (the `[text_display]` shortcode
 *              from Multi-Input-Text-Display-Plugi — reads tdp_display_text_N / tdp_shift_schedule_N
 *              from wp_options in real time, the same options "Assisting Ticket #" writes to) is
 *              RELOCATED — never cloned or re-typed — from its original spot further down the page
 *              to directly below the navbar, and only shown while the ticket-list section is
 *              active. An earlier version of this file made the mistake of hardcoding a JS copy of
 *              that data instead of relocating the live element, which silently broke the
 *              connection to real edits (see fixes/fix_log.md F002). Never do that again — always
 *              relocate/restyle the actual live DOM node, never re-type its content into JS.
 *              Pure front-end CSS/JS — no database or backend changes, nothing removed, only
 *              hidden/relocated in the DOM. The navbar is rendered `position: fixed` (rather than
 *              inserted in normal document flow) specifically so it can never interact with the
 *              page's own layout (Elementor canvas flex/float rules, etc.) — it only overlays on
 *              top, with `padding-top` added to <body> to make room.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer', 'wpsc_global_navbar_output' );

function wpsc_global_navbar_output() {

	// Scope: Home/Support (2281) and Tickets (5422) ONLY. Forms (2102) and IT Forms (2156) are
	// explicitly out of scope — do not add them back here without a new user request.
	if ( ! is_page( array( 2281, 5422 ) ) ) {
		return;
	}

	$logo_url    = esc_js( content_url( '/uploads/2025/01/Strata-Staff-Primary-Logo.png' ) );
	$home_url    = esc_js( home_url( '/' ) );
	$tickets_url = esc_js( home_url( '/tickets/' ) );
	$current_id  = (int) get_queried_object_id();
	?>
	<style id="wpsc-global-navbar-styles">
	.wpsc-global-header {
		position: fixed !important;
		top: 0;
		left: 0;
		right: 0;
		z-index: 9000;
		float: none !important;
		justify-content: space-between;
		box-sizing: border-box;
	}
	body.admin-bar .wpsc-global-header {
		top: 32px;
	}
	.wpsc-global-header .wpsc-global-logo,
	.wpsc-real-logo {
		display: flex;
		align-items: center;
		margin-right: 16px;
		text-decoration: none;
	}
	.wpsc-global-header .wpsc-global-logo img,
	.wpsc-real-logo img {
		width: 160px;
		height: auto;
		display: block;
	}
	.wpsc-global-header .wpsc-global-links {
		display: flex;
		align-items: center;
		flex: 1;
	}
	.wpsc-global-header .wpsc-menu-list {
		text-decoration: none;
	}

	/* Hide the duplicate hero logo/heading on the Home page — the navbar now carries a logo too */
	body.page-id-2281 .elementor-element-5a19956,
	body.page-id-2281 .elementor-element-0218fa6 {
		display: none !important;
	}

	/* Hide the Tickets page's separate small-logo Elementor widget (sat beside the navbar, not in
	   it) — the logo is now inserted inside .wpsc-header itself instead, same as the Home page,
	   so both pages place it identically rather than via two different mechanisms. */
	body.page-id-5422 .elementor-element-3921805 {
		display: none !important;
	}

	/* The live Shift Assignments widget is relocated (via JS) from here to directly below the
	   navbar — this just hides its now-empty original wrapper, not the content itself. */
	body.page-id-2281 .elementor-element-1e9b1cd {
		display: none !important;
	}

	/* Pre-existing Elementor design on the Home page: the widget wrapping the embedded ticket app
	   has its own solid blue border (element 826fd30, configured directly in the page's Elementor
	   data, unrelated to SupportCandy or this plugin) — strip it so the page reads clean. */
	body.page-id-2281 .elementor-element-826fd30 > .elementor-widget-container {
		border: none !important;
	}

	/* The Home page's inner section wrapping the ticket app (839b455) is configured "boxed" in
	   Elementor while its parent (e88a5f5) is "full width" — same mismatch T009d already fixed on
	   the Tickets page. Strip the boxed max-width/gutter so the ticket app goes edge-to-edge here
	   too, instead of sitting in a narrower centered container. */
	body.page-id-2281 .elementor-element-839b455 > .elementor-container {
		max-width: 100% !important;
		padding-left: 0 !important;
		padding-right: 0 !important;
	}

	/* Card wrapper for the relocated live Shift Assignments display (Home page, ticket-list only).
	   SupportCandy's own .wpsc-header is `float: left` (framework/style.css) — without `clear`,
	   a normal-flow sibling like this renders UNDER/behind the floated header instead of below
	   it, since the container never grows to account for the float's height. */
	.wpsc-shift-section {
		clear: both !important;
		border: 1px solid #e4e7ec;
		border-radius: 10px;
		background: #fff;
		padding: 14px 18px;
		margin-top: 80px;
	}
	.wpsc-shift-section .wpsc-shift-section-label {
		font-size: 12.5px;
		font-weight: 700;
		letter-spacing: 0.02em;
		text-transform: uppercase;
		color: #c0202a;
		margin: 0 0 10px;
	}
	.wpsc-shift-section .wpsc-shift-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
		gap: 16px;
	}
	.wpsc-shift-section .wpsc-shift-cell {
		text-align: left;
	}
	.wpsc-shift-section .wpsc-shift-name {
		font-size: 13px;
		font-weight: 700;
		margin: 0 0 6px;
		color: inherit;
	}
	.wpsc-shift-section .wpsc-shift-label {
		font-size: 11px;
		font-weight: 600;
		letter-spacing: 0.02em;
		text-transform: uppercase;
		color: #5b6b6a;
		margin: 0 0 4px;
	}
	.wpsc-shift-section .wpsc-shift-badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 12.5px;
		font-weight: 700;
		color: #c0202a;
		background: #fdeceb;
		border-radius: 6px;
		padding: 4px 10px;
		margin: 0 0 10px;
	}
	.wpsc-shift-section .wpsc-shift-value {
		font-size: 12.5px;
		font-weight: 700;
		color: #c0202a;
		margin: 0;
	}
	html[data-wpsc-theme="dark"] .wpsc-shift-section {
		background: #1b2124 !important;
		border-color: #2c3438 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-shift-section .wpsc-shift-label {
		color: #aeb8bc;
	}
	html[data-wpsc-theme="dark"] .wpsc-shift-section .wpsc-shift-badge {
		background: #2a1b1b;
		color: #ff6b6b;
	}
	html[data-wpsc-theme="dark"] .wpsc-shift-section .wpsc-shift-value {
		color: #ff6b6b;
	}
	</style>

	<script id="wpsc-global-navbar-script">
	(function () {
		try {
			var CURRENT_ID = <?php echo $current_id; ?>;
			var LINKS = [
				{ id: 2281, label: 'Home', url: '<?php echo $home_url; ?>' },
				{ id: 5422, label: 'Tickets', url: '<?php echo $tickets_url; ?>' }
			];

			function buildNavbar() {
				var header = document.createElement('div');
				header.className = 'wpsc-header wpsc-global-header';

				var logoLink = document.createElement('a');
				logoLink.className = 'wpsc-global-logo';
				logoLink.href = '<?php echo $home_url; ?>';
				var img = document.createElement('img');
				img.src = '<?php echo $logo_url; ?>';
				img.alt = 'Strata Staff';
				logoLink.appendChild(img);
				header.appendChild(logoLink);

				var links = document.createElement('div');
				links.className = 'wpsc-global-links';
				LINKS.forEach(function (link) {
					var a = document.createElement('a');
					a.className = 'wpsc-menu-list wpsc-tickets-nav' + (link.id === CURRENT_ID ? ' active' : '');
					a.href = link.url;
					var label = document.createElement('label');
					label.textContent = link.label;
					label.style.cursor = 'pointer';
					a.appendChild(label);
					links.appendChild(a);
				});
				header.appendChild(links);

				return header;
			}

			function adjustBodySpacing(header) {
				var adminBarHeight = document.body.classList.contains('admin-bar') ? 32 : 0;
				document.body.style.paddingTop = ( header.offsetHeight + adminBarHeight ) + 'px';
			}

			function insertNavbar() {
				var existing = document.querySelector('.wpsc-global-header');
				var realHeader = document.querySelector('.wpsc-header:not(.wpsc-global-header)');
				var realHeaderVisible = realHeader && realHeader.offsetParent !== null;

				// The real SupportCandy header appeared (e.g. AJAX login on the Tickets page) —
				// remove our fallback and the spacing we added for it, so they don't stack.
				if (realHeaderVisible && existing) {
					existing.remove();
					document.body.style.paddingTop = '';
					return;
				}
				if (realHeaderVisible || existing) return;

				var header = buildNavbar();
				document.body.appendChild(header);
				adjustBodySpacing(header);
			}

			// The real .wpsc-header SupportCandy renders has no logo built in. On the Tickets page
			// there used to be a separate small-logo Elementor widget sitting beside the navbar
			// instead of inside it (hidden via CSS above); this puts the SAME in-navbar logo on
			// both pages so they're placed identically rather than two different mechanisms.
			function ensureLogo() {
				var header = document.querySelector('.wpsc-header:not(.wpsc-global-header)');
				if (!header || header.querySelector('.wpsc-real-logo')) return;

				var logoLink = document.createElement('a');
				logoLink.className = 'wpsc-real-logo';
				logoLink.href = '<?php echo $home_url; ?>';
				var img = document.createElement('img');
				img.src = '<?php echo $logo_url; ?>';
				img.alt = 'Strata Staff';
				logoLink.appendChild(img);
				header.insertBefore(logoLink, header.firstChild);
			}

			function getCurrentSection() {
				if (window.supportcandy && window.supportcandy.current_section) {
					return window.supportcandy.current_section;
				}
				var params = new URLSearchParams(window.location.search);
				return params.get('wpsc-section') || 'ticket-list';
			}

			// Reshapes the plugin's own raw markup (inline-styled <p>/<span> tags) into our card
			// grid — reading each cell's CURRENT live text/value and rebuilding clean markup around
			// it, never hardcoding a value. Runs once per page load (guarded by a data attribute);
			// safe to re-run after that since it's idempotent, but the guard keeps it cheap.
			function restyleShiftWidget(widget) {
				if (widget.dataset.wpscRestyled) return;

				var row = widget.querySelector(':scope > div') || widget.firstElementChild;
				if (!row) return;
				widget.dataset.wpscRestyled = '1';

				row.removeAttribute('style');
				row.className = 'wpsc-shift-grid';

				Array.prototype.forEach.call(row.children, function (cell) {
					var paragraphs = cell.querySelectorAll('p');
					if (paragraphs.length < 3) return;

					var name = paragraphs[0].textContent.trim();
					var ticketSpan = paragraphs[1].querySelector('span');
					var ticketValue = ticketSpan ? ticketSpan.textContent.trim() : paragraphs[1].textContent.trim();
					var shiftSpan = paragraphs[2].querySelector('span');
					var shiftValue = shiftSpan ? shiftSpan.textContent.trim() : paragraphs[2].textContent.trim();

					cell.removeAttribute('style');
					cell.className = 'wpsc-shift-cell';
					cell.innerHTML = '';

					var nameEl = document.createElement('p');
					nameEl.className = 'wpsc-shift-name';
					nameEl.textContent = name;

					var ticketLabel = document.createElement('p');
					ticketLabel.className = 'wpsc-shift-label';
					ticketLabel.textContent = 'Assisting Ticket #';

					var ticketBadge = document.createElement('div');
					ticketBadge.className = 'wpsc-shift-badge';
					ticketBadge.textContent = ticketValue;

					var shiftLabel = document.createElement('p');
					shiftLabel.className = 'wpsc-shift-label';
					shiftLabel.textContent = 'Shift Schedule';

					var shiftValueEl = document.createElement('p');
					shiftValueEl.className = 'wpsc-shift-value';
					shiftValueEl.textContent = shiftValue;

					cell.appendChild(nameEl);
					cell.appendChild(ticketLabel);
					cell.appendChild(ticketBadge);
					cell.appendChild(shiftLabel);
					cell.appendChild(shiftValueEl);
				});
			}

			// Home page only: relocate (never clone) the real, live Shift Assignments widget from
			// its original spot in the page content to directly below the navbar, and only show it
			// on the ticket-list section — SupportCandy is a single-page app once the real header
			// loads, so this has to keep checking for as long as the page stays open.
			function relocateShiftWidget() {
				if (CURRENT_ID !== 2281) return;
				var header = document.querySelector('.wpsc-header:not(.wpsc-global-header)');
				if (!header || !header.offsetParent) return;

				var widget = document.querySelector('.elementor-element-1e9b1cd .elementor-shortcode');
				if (!widget) return;

				var section = document.querySelector('.wpsc-shift-section');
				if (!section) {
					section = document.createElement('div');
					section.className = 'wpsc-shift-section';
					var label = document.createElement('p');
					label.className = 'wpsc-shift-section-label';
					label.textContent = 'Shift Assignments';
					section.appendChild(label);
					section.appendChild(widget);
					header.parentNode.insertBefore(section, header.nextSibling);
				}

				restyleShiftWidget(widget);
				section.style.display = ( getCurrentSection() === 'ticket-list' ) ? '' : 'none';
			}

			document.addEventListener('click', function (e) {
				// Same click-outside fix as wpsc-navbar-redesign.php: don't close a panel because
				// of a click on its own contents.
				if (e.target.closest('.wpsc-nav-dropdown')) return;
				document.querySelectorAll('.wpsc-nav-dropdown-panel').forEach(function (p) {
					p.classList.remove('open');
				});
			});

			function run() {
				insertNavbar();
				ensureLogo();
				relocateShiftWidget();
			}

			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', run);
			} else {
				run();
			}
			// SupportCandy renders its own header via AJAX after initial load, and the user can
			// switch sections at any time without a reload — keep checking indefinitely rather
			// than stopping after a fixed number of attempts.
			setInterval(run, 500);
		} catch (e) {
			// Never let this cosmetic navbar break the rest of the page.
			if (window.console && console.error) console.error('wpsc-global-navbar:', e);
		}
	})();
	</script>
	<?php
}
