/**
 * "Search appearance" panel in the block editor sidebar: an optional title and description for
 * search engines, saved as post meta. Labels come from PHP (window.rashidSeoPanel).
 * Written with plain wp.* globals, so it needs no build tool.
 */
( function ( wp, config ) {
	'use strict';

	var el = wp.element.createElement;
	var components = wp.components;
	var Panel = ( wp.editor && wp.editor.PluginDocumentSettingPanel ) || wp.editPost.PluginDocumentSettingPanel;

	function SeoPanel() {
		var entity = wp.coreData.useEntityProp( 'postType', config.postType, 'meta' );
		var meta = entity[ 0 ] || {};
		var setMeta = entity[ 1 ];

		function field( Control, key, label, help ) {
			var value = meta[ key ] || '';
			return el( Control, {
				label: label,
				help: help + ' (' + value.length + ' ' + config.labels.characters + ')',
				value: value,
				onChange: function ( next ) {
					var change = {};
					change[ key ] = next;
					setMeta( Object.assign( {}, meta, change ) );
				},
			} );
		}

		return el(
			Panel,
			{ name: 'rashid-seo', title: config.labels.panel },
			field( components.TextControl, config.titleKey, config.labels.title, config.labels.titleHelp ),
			field( components.TextareaControl, config.descriptionKey, config.labels.description, config.labels.descHelp )
		);
	}

	wp.plugins.registerPlugin( 'rashid-seo', { render: SeoPanel } );
} )( window.wp, window.rashidSeoPanel );
