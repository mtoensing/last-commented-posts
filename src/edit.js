import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import {
	Panel,
	PanelBody,
	RangeControl,
	SelectControl,
} from '@wordpress/components';

import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<Panel>
					<PanelBody>
						<RangeControl
							label={ __(
								'Number of posts',
								'lastcommentedposts'
							) }
							value={ attributes.max_level }
							onChange={ ( level ) =>
								setAttributes( {
									max_level: Number( level ),
								} )
							}
							min={ 1 }
							max={ 10 }
						/>
						<SelectControl
							label={ __( 'Cache', 'lastcommentedposts' ) }
							value={ String( attributes.cache_ttl ?? 3600 ) }
							options={ [
								{
									label: __( 'Off', 'lastcommentedposts' ),
									value: '0',
								},
								{
									label: __(
										'1 hour (default)',
										'lastcommentedposts'
									),
									value: '3600',
								},
								{
									label: __(
										'6 hours',
										'lastcommentedposts'
									),
									value: '21600',
								},
								{
									label: __( '1 day', 'lastcommentedposts' ),
									value: '86400',
								},
							] }
							onChange={ ( cacheTtl ) =>
								setAttributes( {
									cache_ttl: Number( cacheTtl ),
								} )
							}
						/>
					</PanelBody>
				</Panel>
			</InspectorControls>
			<ServerSideRender
				block="lastcommentedposts/list"
				attributes={ attributes }
			/>
		</div>
	);
}
