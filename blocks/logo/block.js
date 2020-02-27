var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    BlockControls = editor.BlockControls,
    AlignmentToolbar = editor.AlignmentToolbar,
    MediaUpload = editor.MediaUpload;
registerBlockType('jma-ghb/logo-block', {
    title: 'Logo',
    description: 'A custom block for image.',
    icon: 'dashicons-align-center',
    category: 'common',
    attributes: {
        // Necessary for saving block content.
        mediaID: {
            type: 'number'
        },
        mediaURL: {
            type: 'string',
            source: 'attribute',
            selector: 'img',
            attribute: 'src'
        },
        mediaTitle: {
            type: 'string'
        },
        mediaFileName: {
            type: 'string'
        },
        alignment: {
            type: 'string',
            default: 'none'
        },
        content: {
            type: 'array',
            source: 'children',
            selector: 'p'
        }
    },
    edit: function(props) {
        var ServerSideRender = wp.components.ServerSideRender;
        var content = props.attributes.content,
            alignment = props.attributes.alignment,
            mediaFileName = props.attributes.mediaFileName,
            mediaTitle = props.attributes.mediaTitle,
            mediaURL = props.attributes.mediaURL,
            mediaID = props.attributes.mediaID;



        return [
            el(BlockControls, {
                    key: 'controls'
                }, // Display controls when the block is clicked on.
                el('div', {
                        className: 'components-toolbar'
                    },
                    el(MediaUpload, {
                        onSelect: function(media) {
                            props.setAttributes({
                                mediaURL: media.url,
                                mediaID: media.id
                            });
                        },
                        type: 'image',
                        render: function(obj) {
                            el(components.Button, {
                                    className: 'components-icon-button components-toolbar__control',
                                    onClick: obj.open
                                }, // Add Dashicon for media upload button.
                                el('svg', {
                                    className: 'dashicon dashicons-edit',
                                    width: '20',
                                    height: '20'
                                }));
                        }
                    })
                ), // Display alignment toolbar within block controls.
                el(AlignmentToolbar, {
                    value: alignment,
                    onChange: function(newAlignment) {
                        props.setAttributes({
                            alignment: newAlignment
                        });
                    }
                })
            ),
            el(ServerSideRender, {
                block: 'jma-ghb/logo-block',
                attributes: props.attributes
            })
        ];
    },
    save: function() {
        return null;
    }
})(
    window.wp.blocks,
    window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
);