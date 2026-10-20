/**
 * Project panels in the block editor sidebar.
 *
 * "Display on the site" (the order number and the home-page flag)
 * and "Project details" (client, industry, role …). Both read and write post meta, which
 * the theme registers for the REST API. The field list and every label come from PHP
 * (window.rashidProjectPanel), so there is a single definition of the fields.
 * Written with plain wp.* globals, so it needs no build tool.
 */
( function ( wp, config ) {
	'use strict';

	var el = wp.element.createElement;
	var components = wp.components;
	var Panel = ( wp.editor && wp.editor.PluginDocumentSettingPanel ) || wp.editPost.PluginDocumentSettingPanel;

	/** Post meta of the project being edited, with a setter that merges one key. */
	function useMeta() {
		var entity = wp.coreData.useEntityProp( 'postType', config.postType, 'meta' );
		var meta = entity[ 0 ] || {};
		var setMeta = entity[ 1 ];

		return [
			meta,
			function ( key, value ) {
				var change = {};
				change[ key ] = value;
				setMeta( Object.assign( {}, meta, change ) );
			},
		];
	}

	/** The order number: the post's own menu_order, lower comes first. */
	function useOrder() {
		return wp.coreData.useEntityProp( 'postType', config.postType, 'menu_order' );
	}

	function DisplayPanel() {
		var state = useMeta();
		var order = useOrder();

		return el(
			Panel,
			{ name: 'rashid-project-display', title: config.labels.display },
			el( components.TextControl, {
				label: config.labels.order,
				help: config.labels.orderHelp,
				type: 'number',
				min: 0,
				step: 1,
				value: order[ 0 ] === undefined ? '' : String( order[ 0 ] ),
				onChange: function ( value ) {
					order[ 1 ]( Math.max( 0, parseInt( value, 10 ) || 0 ) );
				},
			} ),
			el( components.CheckboxControl, {
				label: config.labels.home,
				checked: state[ 0 ][ config.homeKey ] === '1',
				onChange: function ( checked ) {
					state[ 1 ]( config.homeKey, checked ? '1' : '' );
				},
			} )
		);
	}

	function DetailsPanel() {
		var state = useMeta();

		return el(
			Panel,
			{ name: 'rashid-project-details', title: config.labels.details },
			config.fields.map( function ( field ) {
				return el( components.TextControl, {
					key: field.key,
					label: field.label,
					type: field.input === 'url' ? 'url' : 'text',
					value: state[ 0 ][ field.key ] || '',
					onChange: function ( value ) {
						state[ 1 ]( field.key, value );
					},
				} );
			} )
		);
	}

	wp.plugins.registerPlugin( 'rashid-project-panels', {
		render: function () {
			return el( wp.element.Fragment, null, el( DisplayPanel ), el( DetailsPanel ) );
		},
	} );
} )( window.wp, window.rashidProjectPanel );
