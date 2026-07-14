<?php
/**
 * Plugin Name: SupportCandy Modern Table Redesign
 * Description: Visually redesigns the ticket list table (stat tiles, avatar circles, status
 *              dot+pill) to match the "Modern Table" direction the user picked. Pure front-end
 *              CSS/JS — decorates existing DOM elements only, never removes/replaces them, so
 *              all existing click handlers, sorting, filtering, and pagination keep working
 *              exactly as before. No database or backend changes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer', 'wpsc_modern_table_output' );
add_action( 'admin_footer', 'wpsc_modern_table_output' );

function wpsc_modern_table_output() {
	?>
	<style id="wpsc-modern-table-styles">
	.wpsc-stat-row {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 14px;
		margin-bottom: 16px;
	}
	.wpsc-stat-tile {
		background: #fff;
		border: 1px solid #e4e7ec;
		border-radius: 10px;
		padding: 12px 16px;
		box-shadow: 0 1px 3px rgba(16, 24, 40, 0.06);
	}
	.wpsc-stat-tile .wpsc-stat-label {
		font-size: 11.5px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.04em;
		color: #667085;
	}
	.wpsc-stat-tile .wpsc-stat-value {
		font-size: 24px;
		font-weight: 700;
		margin-top: 3px;
		letter-spacing: -0.01em;
		font-variant-numeric: tabular-nums;
	}
	.wpsc-stat-tile .wpsc-stat-bar {
		height: 3px;
		border-radius: 3px;
		margin-top: 9px;
	}
	html[data-wpsc-theme="dark"] .wpsc-stat-tile {
		background: #1b2124 !important;
		border-color: #2c3438 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-stat-tile .wpsc-stat-label {
		color: #8b9998 !important;
	}
	html[data-wpsc-theme="dark"] .wpsc-stat-tile .wpsc-stat-value {
		color: #e7ecee !important;
	}

	.wpsc-row-avatar {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 22px;
		height: 22px;
		border-radius: 50%;
		background: #094bc1;
		color: #fff;
		font-size: 9.5px;
		font-weight: 700;
		margin-right: 7px;
		flex-shrink: 0;
		vertical-align: middle;
	}
	.wpsc-name-cell-wrap {
		display: inline-flex;
		align-items: center;
	}

	.wpsc-status-dot {
		display: inline-block;
		width: 6px;
		height: 6px;
		border-radius: 50%;
		margin-right: 5px;
		background: currentColor;
		vertical-align: middle;
	}
	</style>

	<script id="wpsc-modern-table-script">
	(function () {
		var AVATAR_COLORS = ['#094bc1', '#128a4a', '#c9700a', '#7a4fc9', '#0f7ec0', '#d0342c'];

		function initials(name) {
			name = (name || '').trim();
			if (!name) return '?';
			var parts = name.split(/\s+/).filter(Boolean);
			if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
			return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
		}

		function colorFor(name) {
			var sum = 0;
			for (var i = 0; i < name.length; i++) sum += name.charCodeAt(i);
			return AVATAR_COLORS[sum % AVATAR_COLORS.length];
		}

		function headerIndex(table, label) {
			var ths = table.querySelectorAll('thead th');
			for (var i = 0; i < ths.length; i++) {
				if (ths[i].textContent.trim().toLowerCase() === label) return i;
			}
			return -1;
		}

		function bucketForStatus(text) {
			text = text.trim().toLowerCase();
			if (text === 'open') return 'open';
			if (text.indexOf('processing') !== -1) return 'processing';
			if (text.indexOf('closed') !== -1) return 'closed';
			return 'other';
		}

		function enhanceTable(table) {
			var nameIdx = headerIndex(table, 'name');
			var statusIdx = headerIndex(table, 'status');
			if (nameIdx === -1 && statusIdx === -1) return;

			var counts = { open: 0, processing: 0, closed: 0, other: 0 };
			var rows = table.querySelectorAll('tbody tr');

			rows.forEach(function (row) {
				var cells = row.children;

				if (nameIdx > -1 && cells[nameIdx] && !cells[nameIdx].dataset.wpscAvatar) {
					var cell = cells[nameIdx];
					var name = cell.textContent.trim();
					if (name) {
						var wrap = document.createElement('span');
						wrap.className = 'wpsc-name-cell-wrap';
						var avatar = document.createElement('span');
						avatar.className = 'wpsc-row-avatar';
						avatar.style.background = colorFor(name);
						avatar.textContent = initials(name);
						var textSpan = document.createElement('span');
						textSpan.textContent = name;
						wrap.appendChild(avatar);
						wrap.appendChild(textSpan);
						cell.innerHTML = '';
						cell.appendChild(wrap);
					}
					cell.dataset.wpscAvatar = '1';
				}

				if (statusIdx > -1 && cells[statusIdx]) {
					var statusText = cells[statusIdx].textContent.trim();
					if (statusText) counts[bucketForStatus(statusText)]++;

					if (!cells[statusIdx].dataset.wpscDot) {
						var badge = cells[statusIdx].querySelector('*');
						if (badge) {
							var dot = document.createElement('span');
							dot.className = 'wpsc-status-dot';
							badge.insertBefore(dot, badge.firstChild);
						}
						cells[statusIdx].dataset.wpscDot = '1';
					}
				}
			});

			if (statusIdx > -1) {
				renderStatRow(table, counts);
			}
		}

		function renderStatRow(table, counts) {
			var wrap = table.closest('.wpsc-shortcode-container') || table.parentElement;
			var existing = wrap.querySelector('.wpsc-stat-row');
			var tiles = [
				{ key: 'open', label: 'Open', color: '#d0342c' },
				{ key: 'processing', label: 'Processing', color: '#128a4a' },
				{ key: 'other', label: 'Awaiting / Hold', color: '#c9700a' },
				{ key: 'closed', label: 'Closed', color: '#1c2b2b' }
			];

			var html = '<div class="wpsc-stat-row">' + tiles.map(function (t) {
				return '<div class="wpsc-stat-tile">' +
					'<div class="wpsc-stat-label">' + t.label + '</div>' +
					'<div class="wpsc-stat-value">' + counts[t.key] + '</div>' +
					'<div class="wpsc-stat-bar" style="background:' + t.color + '"></div>' +
					'</div>';
			}).join('') + '</div>';

			if (existing) {
				existing.outerHTML = html;
			} else {
				table.insertAdjacentHTML('beforebegin', html);
			}
		}

		function scan() {
			document.querySelectorAll('.wpsc-ticket-list-tbl').forEach(enhanceTable);
		}

		var debounceTimer;
		function debouncedScan() {
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(scan, 200);
		}

		var observer = new MutationObserver(debouncedScan);
		observer.observe(document.body, { childList: true, subtree: true });

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', scan);
		} else {
			scan();
		}
	})();
	</script>
	<?php
}
