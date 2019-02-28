/**
 * BLOCK: bs-customer-opinions
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

import './style.scss';
import './editor.scss';

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {TextControl} = wp.components;
registerBlockType('bonseo/block-bs-customer-opinions', {
	title: __('Opiniones de Clientes'),
	icon: 'editor-quote',
	category: 'bonseo-blocks',
	keywords: [
		__('customer-opinions'),
		__('BonSeo'),
		__('BonSeo Block'),
	],
	edit: function ({posts, className, attributes, setAttributes}) {
		return (
			<div>
				<h2> Opiniones de Clientes</h2>
				<TextControl
					className={`${className}__title`}
					label={__('Encabezado del bloque:')}
					value={attributes.title}
					onChange={title => setAttributes({title})}
				/>
				<TextControl
					className={`${className}__length`}
					type="number"
					label={__('Cuantas opiniones queire mostrar:')}
					value={attributes.max_comments}
					onChange={max_comments => setAttributes({max_comments})}
				/>
			</div>
		);
	},
	save: function () {
		return null;
	}
})
;
