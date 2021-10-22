import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, BlockControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { RangeControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
 
import { SelectControl, ToolbarGroup, ToolbarButton, ToggleControl, Panel, PanelBody, PanelRow } from '@wordpress/components';



registerBlockType('rcpb/list', {
  title: __('Recent Commented Posts', 'rcpb'),
  category: 'layout',
  icon: 'editor-ul',
  keywords: [ __( 'RCPB' ), __( 'Latest Comments' ) ],
  attributes: {
		max_level: {
			type: 'integer',
      default: 5
		},
    updated: {
      type: 'number',
      default: 0
    }
	},
  edit: function(props) {
    return (
    <>
    <InspectorControls>
      <Panel>
        <PanelBody>
          <PanelRow>
            <RangeControl
              label={__('Number of posts', 'rcpb')}
              value={ props.attributes.max_level }
              onChange={ ( level ) => props.setAttributes( { max_level: Number(level) } ) }
              min={ 2 }
              max={ 10 }
            />
          </PanelRow>
        </PanelBody>
      </Panel>
    </InspectorControls>
  <ServerSideRender block={props.name} attributes={props.attributes} />
  </>
  )
  },
  save: props => {
    return null;
  },
});
