/**
 * Editor side of the "Featured projects" block.
 *
 * The block is rendered by PHP (render.php), so the editor shows the same preview
 * through ServerSideRender and only adds the settings panel. Written with plain
 * wp.* globals, so it needs no build tool.
 */
( function ( wp ) {
	'use strict';

	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var components = wp.components;

	/** Category choices: all, plus every project category from the REST API. */
	function useCategoryOptions() {
		var categories = wp.data.useSelect( function ( select ) {
			return select( 'core' ).getEntityRecords( 'taxonomy', 'project-cat', { per_page: -1 } );
		}, [] );

		var options = ( categories || [] ).map( function ( category ) {
			return { label: category.name, value: category.slug };
		} );

		return [ { label: __( 'All categories', 'rashid' ), value: '' } ].concat( options );
	}

	/** The settings shown in the sidebar. */
	function settingsPanel( attributes, setAttributes, categoryOptions ) {
		return el(
			wp.blockEditor.InspectorControls,
			null,
			el(
				components.PanelBody,
				{ title: __( 'Projects', 'rashid' ) },
				el( components.RangeControl, {
					label: __( 'How many projects', 'rashid' ),
					value: attributes.count,
					min: 1,
					max: 12,
					onChange: function ( count ) {
						setAttributes( { count: count } );
					},
				} ),
				el( components.SelectControl, {
					label: __( 'Category', 'rashid' ),
					value: attributes.category,
					options: categoryOptions,
					onChange: function ( category ) {
						setAttributes( { category: category } );
					},
				} ),
				el( components.ToggleControl, {
					label: __( 'Only projects marked "Show on the home page"', 'rashid' ),
					help: __( 'When none are marked, the newest projects are shown.', 'rashid' ),
					checked: attributes.onlyHome,
					onChange: function ( onlyHome ) {
						setAttributes( { onlyHome: onlyHome } );
					},
				} )
			)
		);
	}

	wp.blocks.registerBlockType( 'rashid/featured-projects', {
		edit: function ( props ) {
			var categoryOptions = useCategoryOptions();

			return el(
				'div',
				wp.blockEditor.useBlockProps(),
				settingsPanel( props.attributes, props.setAttributes, categoryOptions ),
				el( wp.serverSideRender, { block: 'rashid/featured-projects', attributes: props.attributes } )
			);
		},
		save: function () {
			return null; // Rendered by PHP.
		},
	} );
} )( window.wp );
