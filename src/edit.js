import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import {
	Panel,
	PanelBody,
	PanelRow,
	RangeControl,
} from '@wordpress/components';

import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<Panel>
					<PanelBody>
						<PanelRow>
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
						</PanelRow>
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
