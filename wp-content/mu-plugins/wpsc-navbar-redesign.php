<?php
/**
 * Plugin Name: SupportCandy Navbar & Shell Redesign
 * Description: (1) Removes the "boxed card" look so the ticketing app is a true full-bleed
 *              dashboard shell. (2) Replaces the flat solid-blue navbar with a modern neutral
 *              dark bar. (3) Relocates the "Assisting Ticket #" and "Canned Reply Templates"
 *              sidebar panels into navbar dropdowns (moves the existing DOM nodes, doesn't
 *              duplicate content/logic) and lets the ticket table use the freed-up width.
 *              Pure front-end CSS/JS — no database or backend changes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer', 'wpsc_navbar_redesign_output', 30 );
add_action( 'admin_footer', 'wpsc_navbar_redesign_output', 30 );

function wpsc_navbar_redesign_output() {
	?>
	<style id="wpsc-navbar-redesign-styles">
	/* ---- Full-bleed shell: remove the "boxed card" look ---- */
	.wpsc-shortcode-container {
		border-radius: 0 !important;
		box-shadow: none !important;
		margin: 0 !important;
	}
	/* Tighten the gap between the logo header and the navbar below it */
	.elementor-element-6901aa1 {
		margin-bottom: -20px !important;
	}
	.elementor-element-b4f5b78,
	.elementor-element-9f79aab {
		max-width: 100% !important;
	}
	/* Sidebar column: its content is relocated into the navbar by JS, so collapse it */
	.elementor-element-0d2c8e0 {
		display: none !important;
	}
	.elementor-element-279585d {
		width: 100% !important;
	}

	/* ---- Modern navbar: clean white app-header, matching the "Modern Table" mockup ---- */
	.wpsc-header {
		background-color: #ffffff !important;
		border-bottom: 1px solid #e4e7ec !important;
	}
	.wpsc-tickets-nav,
	.wpsc-header-nav {
		color: #5b6b6a !important;
	}
	.wpsc-tickets-nav svg,
	.wpsc-header-nav svg,
	.wpsc-humbargar,
	.wpsc-humbargar-title {
		color: #5b6b6a !important;
		fill: #5b6b6a !important;
	}
	.wpsc-tickets-nav.active,
	.wpsc-header-nav.active {
		background-color: #eef2f6 !important;
		color: #094bc1 !important;
	}
	.wpsc-tickets-nav.active svg,
	.wpsc-header-nav.active svg {
		color: #094bc1 !important;
		fill: #094bc1 !important;
	}
	.wpsc-tickets-nav:hover,
	.wpsc-header-nav:hover {
		background-color: #f3f6f6 !important;
	}
	.wpsc-header .log-out {
		color: #5b6b6a !important;
	}
	.wpsc-header .log-out svg {
		color: #5b6b6a !important;
		fill: #5b6b6a !important;
	}

	/* ---- Navbar dropdowns (relocated sidebar panels) ---- */
	.wpsc-nav-dropdown {
		position: relative;
		margin-right: 6px;
	}
	.wpsc-nav-dropdown-trigger {
		display: flex;
		align-items: center;
		gap: 6px;
		padding: 8px 14px;
		border-radius: 8px;
		background: transparent;
		border: none;
		color: #5b6b6a;
		font-family: inherit;
		font-size: 13.5px;
		font-weight: 600;
		cursor: pointer;
	}
	.wpsc-nav-dropdown-trigger:hover {
		background: #f3f6f6;
		color: #094bc1;
	}
	.wpsc-nav-dropdown-panel {
		display: none;
		position: absolute;
		top: calc(100% + 8px);
		left: 0;
		min-width: 320px;
		max-width: 420px;
		max-height: 70vh;
		overflow-y: auto;
		background: #fff;
		border: 1px solid #e4e7ec;
		border-radius: 10px;
		box-shadow: 0 8px 24px rgba(16, 24, 40, 0.16);
		padding: 14px;
		z-index: 400;
		color: #1c2b2b;
	}
	.wpsc-nav-dropdown-panel.open {
		display: block;
	}
	.wpsc-nav-dropdown-panel details {
		border: none !important;
		box-shadow: none !important;
	}
	.wpsc-nav-dropdown-panel summary {
		display: none !important;
	}
	.wpsc-nav-dropdown-panel .wpsc-helper-body,
	.wpsc-nav-dropdown-panel .tdp-body,
	.wpsc-nav-dropdown-panel .wpsc-tpl-body {
		display: block !important;
	}

	html[data-wpsc-theme="dark"] .wpsc-nav-dropdown-panel {
		background: #1b2124 !important;
		border-color: #2c3438 !important;
		color: #e7ecee !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-header {
		background-color: #1b2124 !important;
		border-bottom-color: #2c3438 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-tickets-nav,
	html[data-wpsc-theme="dark"] .wpsc-header-nav,
	html[data-wpsc-theme="dark"] .wpsc-header .log-out,
	html[data-wpsc-theme="dark"] .wpsc-nav-dropdown-trigger {
		color: #aeb8bc !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-tickets-nav svg,
	html[data-wpsc-theme="dark"] .wpsc-header-nav svg,
	html[data-wpsc-theme="dark"] .wpsc-header .log-out svg {
		color: #aeb8bc !important;
		fill: #aeb8bc !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-tickets-nav.active,
	html[data-wpsc-theme="dark"] .wpsc-header-nav.active {
		background-color: #20262a !important;
		color: #4d8fef !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-tickets-nav:hover,
	html[data-wpsc-theme="dark"] .wpsc-header-nav:hover,
	html[data-wpsc-theme="dark"] .wpsc-nav-dropdown-trigger:hover {
		background-color: #20262a !important;
	}
	</style>

	<script id="wpsc-navbar-redesign-script">
	(function () {
		var moved = false;

		function closeAllDropdowns(except) {
			document.querySelectorAll('.wpsc-nav-dropdown-panel').forEach(function (p) {
				if (p !== except) p.classList.remove('open');
			});
		}

		function makeDropdown(triggerLabel, contentEl) {
			var wrap = document.createElement('div');
			wrap.className = 'wpsc-nav-dropdown';

			var trigger = document.createElement('button');
			trigger.type = 'button';
			trigger.className = 'wpsc-nav-dropdown-trigger';
			trigger.textContent = triggerLabel;

			var panel = document.createElement('div');
			panel.className = 'wpsc-nav-dropdown-panel';
			panel.appendChild(contentEl);

			trigger.addEventListener('click', function (e) {
				e.stopPropagation();
				var willOpen = !panel.classList.contains('open');
				closeAllDropdowns();
				if (willOpen) panel.classList.add('open');
			});

			wrap.appendChild(trigger);
			wrap.appendChild(panel);
			return wrap;
		}

		function relocate() {
			if (moved) return;
			var navContainer = document.querySelector('.wpsc-header nav') || document.querySelector('.wpsc-header');
			if (!navContainer) return;

			// Find the two Elementor widgets that hold our panels (still in the now-hidden sidebar column).
			var helperPanel = document.querySelector('.tdp-panel'); // "Assisting Ticket #"
			var templatesPanel = document.querySelector('.wpsc-templates-panel'); // "Canned Reply Templates"

			if (!helperPanel && !templatesPanel) return; // not rendered yet, try again later

			var logout = document.querySelector('.wpsc-header .log-out');
			if (!logout) return;

			if (helperPanel) {
				if (helperPanel.tagName === 'DETAILS') helperPanel.open = true;
				var d1 = makeDropdown('Assisting Ticket #', helperPanel);
				logout.parentNode.insertBefore(d1, logout);
			}
			if (templatesPanel) {
				if (templatesPanel.tagName === 'DETAILS') templatesPanel.open = true;
				var d2 = makeDropdown('Templates', templatesPanel);
				logout.parentNode.insertBefore(d2, logout);
			}

			moved = true;
		}

		document.addEventListener('click', function () { closeAllDropdowns(); });

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', relocate);
		} else {
			relocate();
		}
		var attempts = 0;
		var interval = setInterval(function () {
			relocate();
			attempts++;
			if (moved || attempts > 30) clearInterval(interval);
		}, 400);
	})();
	</script>
	<?php
}
