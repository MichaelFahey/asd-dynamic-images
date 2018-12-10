var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType;

registerBlockType( 'gutenberg-boilerplate-es5/dynamic-images', {
    title: 'Hello World',

    icon: 'universal-access-alt',

    category: 'layout',

    edit: function( props ) {
        return el( 'p', { className: props.className }, 'Dynamic Image' );
    },

    save: function( props ) {
        return el( 'p', { className: props.className }, 'Dynamic Image Content.' );
    },
} );


