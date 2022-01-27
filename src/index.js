import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

 /**
  * Internal dependencies
  */
  import Edit from './edit';
  import save from './save';
 
   /**
  * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
  * All files containing `style` keyword are bundled together. The code used
  * gets applied both to the front of your site and to the editor.
  *
  * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
  */
 import './style.scss';

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
