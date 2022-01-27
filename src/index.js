import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

 /**
  * Internal dependencies
  */
  import Edit from './edit';
  import save from './save';
 

registerBlockType('lastcommentedposts/list', {
	/**
	 * @see ./edit.js
	 */
	 edit: Edit,

	 /**
	  * @see ./save.js
	  */
	 save,
 });
