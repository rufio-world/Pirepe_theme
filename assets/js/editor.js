/**
 * Pirepe editor helpers: layout presets, grid spans, visibility toggles, and preview device shortcuts.
 * Adds attributes to Group/Columns/Column, renders inspector controls, and applies classes on save.
 */
( function ( wp ) {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createHigherOrderComponent } = wp.compose;
	const { InspectorControls, BlockControls } = wp.blockEditor || wp.editor;
	const { PanelBody, SelectControl, RangeControl, ToggleControl, Button, ButtonGroup, Dropdown, ToolbarGroup, ToolbarDropdownMenu } = wp.components;
	const { Fragment } = wp.element;
	const { useDispatch, useSelect } = wp.data;

	const SUPPORTED_BLOCKS = [ 'core/group', 'core/columns', 'core/column' ];

	const PRESETS = [
		{ label: __( 'None', 'twentytwentyfive' ), value: '' },
		{ label: __( 'Full (12/12)', 'twentytwentyfive' ), value: 'full' },
		{ label: __( 'Halves (6/6)', 'twentytwentyfive' ), value: 'halves' },
		{ label: __( '1/3 + 2/3', 'twentytwentyfive' ), value: 'third-two-third' },
		{ label: __( '2/3 + 1/3', 'twentytwentyfive' ), value: 'two-third-one-third' },
		{ label: __( '1/4 + 3/4', 'twentytwentyfive' ), value: 'quarter-three-quarter' },
		{ label: __( '3/4 + 1/4', 'twentytwentyfive' ), value: 'three-quarter-quarter' },
		{ label: __( 'Quarters (1/4 x 4)', 'twentytwentyfive' ), value: 'quarters' },
	];

	// Extend attributes.
	addFilter(
		'blocks.registerBlockType',
		'pirepe/layout-attributes',
		( settings, name ) => {
			if ( ! SUPPORTED_BLOCKS.includes( name ) ) {
				return settings;
			}

			const extra = {
				pirepeLayoutPreset: { type: 'string', default: '' },
				pirepeSpan: { type: 'number' },
				pirepeSpanDesktop: { type: 'number' },
				pirepeSpanTablet: { type: 'number' },
				pirepeSpanMobile: { type: 'number' },
				pirepeGapDesktop: { type: 'string' },
				pirepeGapTablet: { type: 'string' },
				pirepeGapMobile: { type: 'string' },
				pirepePadDesktop: { type: 'string' },
				pirepePadTablet: { type: 'string' },
				pirepePadMobile: { type: 'string' },
				pirepeStackMobile: { type: 'boolean', default: false },
				pirepeHideDesktop: { type: 'boolean', default: false },
				pirepeHideTablet: { type: 'boolean', default: false },
				pirepeHideMobile: { type: 'boolean', default: false },
			};

			settings.attributes = { ...( settings.attributes || {} ), ...extra };
			return settings;
		}
	);

	// Apply classes on save.
	addFilter(
		'blocks.getSaveContent.extraProps',
		'pirepe/apply-classes',
		( extraProps, blockType, attributes ) => {
			if ( ! SUPPORTED_BLOCKS.includes( blockType.name ) ) {
				return extraProps;
			}

			const classes = extraProps.className ? [ extraProps.className ] : [];

			if ( attributes.pirepeLayoutPreset && ( blockType.name === 'core/group' || blockType.name === 'core/columns' ) ) {
				classes.push( `pirepe-layout-${ attributes.pirepeLayoutPreset }` );
			}

			if ( blockType.name === 'core/column' && attributes.pirepeSpan ) {
				classes.push( `pirepe-col-${ attributes.pirepeSpan }` );
			}
			if ( blockType.name === 'core/column' && attributes.pirepeSpanDesktop ) {
				classes.push( `pirepe-span-d-${ attributes.pirepeSpanDesktop }` );
			}
			if ( blockType.name === 'core/column' && attributes.pirepeSpanTablet ) {
				classes.push( `pirepe-span-t-${ attributes.pirepeSpanTablet }` );
			}
			if ( blockType.name === 'core/column' && attributes.pirepeSpanMobile ) {
				classes.push( `pirepe-span-m-${ attributes.pirepeSpanMobile }` );
			}

			if ( isContainer ) {
				if ( attributes.pirepeGapDesktop ) {
					extraProps.style = extraProps.style || {};
					extraProps.style.setProperty?.( '--pirepe-gap-d', attributes.pirepeGapDesktop );
				}
				if ( attributes.pirepeGapTablet ) {
					extraProps.style = extraProps.style || {};
					extraProps.style.setProperty?.( '--pirepe-gap-t', attributes.pirepeGapTablet );
				}
				if ( attributes.pirepeGapMobile ) {
					extraProps.style = extraProps.style || {};
					extraProps.style.setProperty?.( '--pirepe-gap-m', attributes.pirepeGapMobile );
				}
				if ( attributes.pirepePadDesktop ) {
					extraProps.style = extraProps.style || {};
					extraProps.style.setProperty?.( '--pirepe-pad-d', attributes.pirepePadDesktop );
				}
				if ( attributes.pirepePadTablet ) {
					extraProps.style = extraProps.style || {};
					extraProps.style.setProperty?.( '--pirepe-pad-t', attributes.pirepePadTablet );
				}
				if ( attributes.pirepePadMobile ) {
					extraProps.style = extraProps.style || {};
					extraProps.style.setProperty?.( '--pirepe-pad-m', attributes.pirepePadMobile );
				}
			}

			if ( attributes.pirepeStackMobile ) {
				classes.push( 'stack-sm' );
			}

			if ( attributes.pirepeHideDesktop ) {
				classes.push( 'pirepe-hide-desktop' );
			}
			if ( attributes.pirepeHideTablet ) {
				classes.push( 'pirepe-hide-tablet' );
			}
			if ( attributes.pirepeHideMobile ) {
				classes.push( 'pirepe-hide-mobile' );
			}

			extraProps.className = classes.join( ' ' );
			return extraProps;
		}
	);

	// Inspector controls.
	const withPirepeInspector = createHigherOrderComponent(
		( BlockEdit ) => ( props ) => {
			if ( ! SUPPORTED_BLOCKS.includes( props.name ) ) {
				return <BlockEdit { ...props } />;
			}

			const { attributes, setAttributes, name } = props;
			const {
				pirepeLayoutPreset,
				pirepeSpan,
				pirepeSpanDesktop,
				pirepeSpanTablet,
				pirepeSpanMobile,
				pirepeGapDesktop,
				pirepeGapTablet,
				pirepeGapMobile,
				pirepePadDesktop,
				pirepePadTablet,
				pirepePadMobile,
				pirepeStackMobile,
				pirepeHideDesktop,
				pirepeHideTablet,
				pirepeHideMobile,
			} = attributes;

			const isContainer = name === 'core/group' || name === 'core/columns';
			const isColumn = name === 'core/column';

			const previewDevice = useSelect(
				( select ) =>
					select( 'core/edit-post' )?.__experimentalGetPreviewDeviceType
						? select( 'core/edit-post' ).__experimentalGetPreviewDeviceType()
						: 'Desktop',
				[]
			);
			const { __experimentalSetPreviewDeviceType: setPreviewDevice } =
				useDispatch( 'core/edit-post' );

			const setPreset = ( value ) => setAttributes( { pirepeLayoutPreset: value } );
			const setSpan = ( value ) => setAttributes( { pirepeSpan: value || undefined } );
			const setSpanDesktop = ( value ) => setAttributes( { pirepeSpanDesktop: value || undefined } );
			const setSpanTablet = ( value ) => setAttributes( { pirepeSpanTablet: value || undefined } );
			const setSpanMobile = ( value ) => setAttributes( { pirepeSpanMobile: value || undefined } );
			const setGapDesktop = ( value ) => setAttributes( { pirepeGapDesktop: value || undefined } );
			const setGapTablet = ( value ) => setAttributes( { pirepeGapTablet: value || undefined } );
			const setGapMobile = ( value ) => setAttributes( { pirepeGapMobile: value || undefined } );
			const setPadDesktop = ( value ) => setAttributes( { pirepePadDesktop: value || undefined } );
			const setPadTablet = ( value ) => setAttributes( { pirepePadTablet: value || undefined } );
			const setPadMobile = ( value ) => setAttributes( { pirepePadMobile: value || undefined } );

			return (
				<Fragment>
					<BlockEdit { ...props } />
					<InspectorControls>
						<PanelBody title={ __( 'Pirepe Layout', 'twentytwentyfive' ) } initialOpen={ true }>
							{ isContainer && (
								<SelectControl
									label={ __( 'Layout preset', 'twentytwentyfive' ) }
									value={ pirepeLayoutPreset }
									options={ PRESETS }
									onChange={ setPreset }
									help={ __(
										'Applies grid-based widths (12-col) to this group/section.',
										'twentytwentyfive'
									) }
								/>
							) }
							{ isColumn && (
								<RangeControl
									label={ __( 'Grid span (columns)', 'twentytwentyfive' ) }
									value={ pirepeSpan }
									onChange={ setSpan }
									min={ 1 }
									max={ 12 }
									allowReset
									help={ __(
										'Snap to 12-col grid for this column.',
										'twentytwentyfive'
									) }
								/>
							) }
							<ToggleControl
								label={ __( 'Stack on mobile', 'twentytwentyfive' ) }
								checked={ !! pirepeStackMobile }
								onChange={ ( value ) => setAttributes( { pirepeStackMobile: value } ) }
							/>
						</PanelBody>

						<PanelBody title={ __( 'Device visibility', 'twentytwentyfive' ) } initialOpen={ false }>
							<ToggleControl
								label={ __( 'Hide on desktop', 'twentytwentyfive' ) }
								checked={ !! pirepeHideDesktop }
								onChange={ ( value ) => setAttributes( { pirepeHideDesktop: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide on tablet', 'twentytwentyfive' ) }
								checked={ !! pirepeHideTablet }
								onChange={ ( value ) => setAttributes( { pirepeHideTablet: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide on mobile', 'twentytwentyfive' ) }
								checked={ !! pirepeHideMobile }
								onChange={ ( value ) => setAttributes( { pirepeHideMobile: value } ) }
							/>
							<ButtonGroup style={ { marginTop: '12px' } }>
								{ [ 'Desktop', 'Tablet', 'Mobile' ].map( ( device ) => (
									<Button
										key={ device }
										isPrimary={ previewDevice === device }
										isSecondary={ previewDevice !== device }
										onClick={ () => setPreviewDevice?.( device ) }
									>
										{ device }
									</Button>
								) ) }
							</ButtonGroup>
						</PanelBody>

						{ isColumn && (
							<PanelBody title={ __( 'Responsive spans', 'twentytwentyfive' ) } initialOpen={ false }>
								<RangeControl
									label={ __( 'Desktop span (≥961px)', 'twentytwentyfive' ) }
									value={ pirepeSpanDesktop || pirepeSpan || 12 }
									onChange={ setSpanDesktop }
									min={ 1 }
									max={ 12 }
									allowReset
								/>
								<RangeControl
									label={ __( 'Tablet span (661-960px)', 'twentytwentyfive' ) }
									value={ pirepeSpanTablet || pirepeSpan || 12 }
									onChange={ setSpanTablet }
									min={ 1 }
									max={ 12 }
									allowReset
								/>
								<RangeControl
									label={ __( 'Mobile span (≤660px)', 'twentytwentyfive' ) }
									value={ pirepeSpanMobile || 12 }
									onChange={ setSpanMobile }
									min={ 1 }
									max={ 12 }
									allowReset
								/>
							</PanelBody>
						) }

						{ isContainer && (
							<PanelBody title={ __( 'Responsive gap & padding', 'twentytwentyfive' ) } initialOpen={ false }>
								<SelectControl
									label={ __( 'Desktop gap', 'twentytwentyfive' ) }
									value={ pirepeGapDesktop || '' }
									options={ [
										{ label: __( 'Default', 'twentytwentyfive' ), value: '' },
										{ label: __( 'Tight', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--20)' },
										{ label: __( 'Regular', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--40)' },
										{ label: __( 'Relaxed', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--60)' },
									] }
									onChange={ setGapDesktop }
								/>
								<SelectControl
									label={ __( 'Tablet gap', 'twentytwentyfive' ) }
									value={ pirepeGapTablet || '' }
									options={ [
										{ label: __( 'Default', 'twentytwentyfive' ), value: '' },
										{ label: __( 'Tight', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--20)' },
										{ label: __( 'Regular', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--40)' },
										{ label: __( 'Relaxed', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--60)' },
									] }
									onChange={ setGapTablet }
								/>
								<SelectControl
									label={ __( 'Mobile gap', 'twentytwentyfive' ) }
									value={ pirepeGapMobile || '' }
									options={ [
										{ label: __( 'Default', 'twentytwentyfive' ), value: '' },
										{ label: __( 'Tight', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--20)' },
										{ label: __( 'Regular', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--40)' },
										{ label: __( 'Relaxed', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--60)' },
									] }
									onChange={ setGapMobile }
								/>
								<SelectControl
									label={ __( 'Desktop padding (all sides)', 'twentytwentyfive' ) }
									value={ pirepePadDesktop || '' }
									options={ [
										{ label: __( 'Default', 'twentytwentyfive' ), value: '' },
										{ label: __( 'Compact', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--30)' },
										{ label: __( 'Comfortable', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--50)' },
										{ label: __( 'Spacious', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--70)' },
									] }
									onChange={ setPadDesktop }
								/>
								<SelectControl
									label={ __( 'Tablet padding (all sides)', 'twentytwentyfive' ) }
									value={ pirepePadTablet || '' }
									options={ [
										{ label: __( 'Default', 'twentytwentyfive' ), value: '' },
										{ label: __( 'Compact', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--30)' },
										{ label: __( 'Comfortable', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--50)' },
										{ label: __( 'Spacious', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--70)' },
									] }
									onChange={ setPadTablet }
								/>
								<SelectControl
									label={ __( 'Mobile padding (all sides)', 'twentytwentyfive' ) }
									value={ pirepePadMobile || '' }
									options={ [
										{ label: __( 'Default', 'twentytwentyfive' ), value: '' },
										{ label: __( 'Compact', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--20)' },
										{ label: __( 'Comfortable', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--40)' },
										{ label: __( 'Spacious', 'twentytwentyfive' ), value: 'var(--wp--preset--spacing--60)' },
									] }
									onChange={ setPadMobile }
								/>
							</PanelBody>
						) }
					</InspectorControls>
				</Fragment>
			);
		},
		'pirepe-with-inspector'
	);

	addFilter( 'editor.BlockEdit', 'pirepe/layout-controls', withPirepeInspector );

	// Toolbar helpers for quick presets and span.
	const withPirepeToolbar = createHigherOrderComponent(
		( BlockEdit ) => ( props ) => {
			if ( ! SUPPORTED_BLOCKS.includes( props.name ) ) {
				return <BlockEdit { ...props } />;
			}

			const { attributes, setAttributes, name } = props;
			const { pirepeLayoutPreset, pirepeSpan } = attributes;
			const isContainer = name === 'core/group' || name === 'core/columns';
			const isColumn = name === 'core/column';

			const setPreset = ( value ) => setAttributes( { pirepeLayoutPreset: value } );
			const setSpan = ( value ) => setAttributes( { pirepeSpan: value || undefined } );

			const presetControls = PRESETS.map( ( item ) => ( {
				title: item.label,
				onClick: () => setPreset( item.value ),
				isActive: pirepeLayoutPreset === item.value,
			} ) );

			return (
				<Fragment>
					<BlockControls>
						<ToolbarGroup>
							{ isContainer && (
								<ToolbarDropdownMenu
									label={ __( 'Layout preset', 'twentytwentyfive' ) }
									icon="layout"
									controls={ presetControls }
								/>
							) }
							{ isColumn && (
								<Dropdown
									renderToggle={ ( { isOpen, onToggle } ) => (
										<Button
											onClick={ onToggle }
											aria-expanded={ isOpen }
											icon="feedback"
											label={ __( 'Column span', 'twentytwentyfive' ) }
											isPressed={ isOpen }
										/>
									) }
									renderContent={ () => (
										<div style={ { minWidth: '220px', padding: '8px 12px' } }>
											<RangeControl
												label={ __( 'Grid span (columns)', 'twentytwentyfive' ) }
												value={ pirepeSpan || 12 }
												onChange={ setSpan }
												min={ 1 }
												max={ 12 }
												allowReset
											/>
										</div>
									) }
								/>
							) }
						</ToolbarGroup>
					</BlockControls>
					<BlockEdit { ...props } />
				</Fragment>
			);
		},
		'pirepe-with-toolbar'
	);

	addFilter( 'editor.BlockEdit', 'pirepe/layout-toolbar', withPirepeToolbar );
} )( window.wp );
