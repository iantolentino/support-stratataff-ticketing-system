<?php
/**
 * Plugin Name: SupportCandy Light/Dark Toggle
 * Description: Adds a light/dark theme toggle to the SupportCandy ticketing UI. Pure front-end
 *              CSS/JS only — no database changes, no functional changes to the ticketing system.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer', 'wpsc_theme_toggle_output' );
add_action( 'admin_footer', 'wpsc_theme_toggle_output' );

function wpsc_theme_toggle_output() {
	?>
	<style id="wpsc-theme-toggle-styles">
	/* ---- Toggle button ---- */
	/* Placed inline in the navbar now (see script below) rather than floating fixed outside it,
	   per later feedback — colored solid black so it reads clearly against the white navbar. */
	.wpsc-theme-toggle-btn {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 32px;
		height: 32px;
		margin-right: 6px;
		border-radius: 8px;
		background: #f3f6f6;
		border: 1px solid #e4e7ec;
		cursor: pointer;
		color: #000000;
		flex-shrink: 0;
	}
	.wpsc-theme-toggle-btn:hover {
		background: #f3f6f6;
		color: #094bc1;
	}
	.wpsc-theme-toggle-btn svg {
		width: 16px;
		height: 16px;
	}
	html[data-wpsc-theme="dark"] .wpsc-theme-toggle-btn {
		color: #aeb8bc !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-theme-toggle-btn:hover {
		background: #20262a !important;
		color: #4d8fef !important;
	}

	/* ---- Dark theme overrides (applied when <html data-wpsc-theme="dark">) ---- */
	html[data-wpsc-theme="dark"] body {
		background-color: #14181b !important;
	}
	html[data-wpsc-theme="dark"] #wpsc-container {
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-shortcode-container {
		background-color: #1b2124 !important;
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-ticket-list-tbl {
		background-color: #1b2124 !important;
		border-color: #2c3438 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-ticket-list-tbl thead th {
		background-color: #20262a !important;
		color: #aeb8bc !important;
		border-color: #2c3438 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-ticket-list-tbl td,
	html[data-wpsc-theme="dark"] .wpsc-ticket-list-tbl th {
		border-color: #262c30 !important;
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-ticket-list-tbl tbody tr:nth-child(even) {
		background-color: #202629 !important;
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-ticket-list-tbl tbody tr:nth-child(odd) {
		background-color: #1b2124 !important;
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-ticket-list-tbl tbody tr:hover {
		background-color: #262e32 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-ticket-list-tbl tbody tr td {
		color: #e7ecee !important;
	}

	/* ---- Our custom collapsible panels (Assisting Ticket #, Canned Reply Templates) ---- */
	html[data-wpsc-theme="dark"] .wpsc-helper-panel details,
	html[data-wpsc-theme="dark"] .tdp-panel,
	html[data-wpsc-theme="dark"] .wpsc-templates-panel {
		background-color: #1b2124 !important;
		border-color: #2c3438 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-helper-panel summary,
	html[data-wpsc-theme="dark"] .tdp-panel summary,
	html[data-wpsc-theme="dark"] .wpsc-templates-panel summary {
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-helper-body,
	html[data-wpsc-theme="dark"] .tdp-body,
	html[data-wpsc-theme="dark"] .wpsc-tpl-body {
		color: #c4ccce !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-template-block,
	html[data-wpsc-theme="dark"] .wpsc-tpl-block {
		border-color: #2c3438 !important;
	}
	html[data-wpsc-theme="dark"] .tdp-row input[type="text"],
	html[data-wpsc-theme="dark"] .tdp-row select,
	html[data-wpsc-theme="dark"] .tdp-row input[type="submit"] {
		background-color: #20262a !important;
		border-color: #333c41 !important;
		color: #e7ecee !important;
	}

	/* ---- Sidebar column background (Elementor inline background) ---- */
	html[data-wpsc-theme="dark"] .elementor-element-0d2c8e0 {
		background-color: #14181b !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-search .search-field,
	html[data-wpsc-theme="dark"] #wpsc-container input[type="text"],
	html[data-wpsc-theme="dark"] #wpsc-container input[type="password"],
	html[data-wpsc-theme="dark"] #wpsc-container select,
	html[data-wpsc-theme="dark"] #wpsc-container textarea,
	html[data-wpsc-theme="dark"] .wpsc-modal input[type="text"],
	html[data-wpsc-theme="dark"] .wpsc-modal select,
	html[data-wpsc-theme="dark"] .wpsc-modal textarea {
		background-color: #20262a !important;
		border-color: #333c41 !important;
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-create-ticket {
		background-color: #1b2124 !important;
		border-color: #2c3438 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-it-widget .wpsc-widget-body {
		background-color: #1b2124 !important;
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-modal .modal,
	html[data-wpsc-theme="dark"] .wpsc-modal-body,
	html[data-wpsc-theme="dark"] .wpsc-modal-footer {
		background-color: #1b2124 !important;
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-button.secondary {
		background-color: #20262a !important;
		color: #e7ecee !important;
		border-color: #333c41 !important;
	}
	</style>

	<script id="wpsc-theme-toggle-script">
	(function () {
		var STORAGE_KEY = 'wpsc-theme-preference';
		var root = document.documentElement;

		function applyTheme(theme) {
			root.setAttribute('data-wpsc-theme', theme);
		}

		function currentPreference() {
			var stored = localStorage.getItem(STORAGE_KEY);
			if (stored === 'light' || stored === 'dark') return stored;
			return 'light';
		}

		applyTheme(currentPreference());

		function iconFor(theme) {
			return theme === 'dark'
				? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>'
				: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>';
		}

		function insertToggle() {
			var navbar = document.querySelector('.wpsc-header');
			if (!navbar || document.querySelector('.wpsc-theme-toggle-btn')) return;

			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'wpsc-theme-toggle-btn';
			btn.title = 'Toggle light / dark theme';
			btn.innerHTML = iconFor(currentPreference());

			btn.addEventListener('click', function () {
				var next = root.getAttribute('data-wpsc-theme') === 'dark' ? 'light' : 'dark';
				applyTheme(next);
				localStorage.setItem(STORAGE_KEY, next);
				btn.innerHTML = iconFor(next);
			});

			// Placed inside the navbar itself, immediately before Logout (superseding an earlier
			// version that floated it fixed outside the navbar — moved back in per later feedback).
			var logout = navbar.querySelector('.log-out');
			if (logout && logout.parentNode) {
				logout.parentNode.insertBefore(btn, logout);
			} else {
				navbar.appendChild(btn);
			}
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', insertToggle);
		} else {
			insertToggle();
		}
		// SupportCandy renders its header via AJAX after initial load — keep checking briefly.
		var attempts = 0;
		var interval = setInterval(function () {
			insertToggle();
			attempts++;
			if (attempts > 20) clearInterval(interval);
		}, 500);
	})();
	</script>
	<?php
}
