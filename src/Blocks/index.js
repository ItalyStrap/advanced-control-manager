/**
 * WordPress dependencies
 */
const {
	registerBlockType,
} = wp.blocks;

/**
 * Internal dependencies
 */
import * as posts from './posts';

/**
 * Register blocks
 */
const registerItalyStrapBlocks = () => {

	[
		posts,
	].forEach( ( { name, settings } ) => {
		registerBlockType( name, settings );
	} );

};

registerItalyStrapBlocks();