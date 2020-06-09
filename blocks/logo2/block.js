(function(blocks, editor, components, i18n, element) {
    var __ = i18n.__,
        el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        RichText = editor.RichText,
        BlockControls = editor.BlockControls,
        AlignmentToolbar = editor.AlignmentToolbar,
        MediaUpload = editor.MediaUpload,
        InspectorControls = editor.InspectorControls,
        PanelBody = components.PanelBody,
        TextControl = components.TextControl,
        RadioControl = components.RadioControl

    registerBlockType('jma-ghb/logo-block', { // The name of our block. Must be a string with prefix. Example: my-plugin/my-custom-block.
        title: __('Logo'), // The title of our block.
        description: __('A custom block for displaying Logo.'), // The description of our block.
        icon: 'megaphone', // Dashicon icon for our block. Custom icons can be added using inline SVGs.
        category: 'common', // The category of the block.
        supports: {
            align: true,
            alignWide: true
        },
        attributes: { // Necessary for saving block content.
            mediaID: {
                type: 'number'
            },
            mediaURL: {
                type: 'string'
            },
            mediaID2x: {
                type: 'number'
            },
            mediaURL2x: {
                type: 'string'
            },
            content_type: {
                type: 'string'
            },
            custom_headline: {
                type: 'string'
            },
            custom_sub: {
                type: 'string'
            }
        },

        edit: function(props) {
            var attributes = props.attributes,
                content_type = props.attributes.content_type,
                custom_headline = props.attributes.custom_headline,
                custom_sub = props.attributes.custom_sub,
                mediaURL = props.attributes.mediaURL,
                mediaID = props.attributes.mediaID,
                mediaURL2x = props.attributes.mediaURL2x,
                mediaID2x = props.attributes.mediaID2x,
                ServerSideRender = wp.components.ServerSideRender;

            var onSelectImage = function(media) {
                props.setAttributes({
                    mediaURL: media.url,
                    mediaID: media.id
                })
            }
            var onSelectImage2x = function(media) {
                props.setAttributes({
                    mediaURL2x: media.url,
                    mediaID2x: media.id
                })
            }

            var image_el = el('div', {
                    className: attributes.mediaID ? 'organic-profile-image image-active' : 'organic-profile-image image-inactive'
                },
                el(MediaUpload, {
                    onSelect: onSelectImage,
                    type: 'image',
                    value: attributes.mediaID,
                    render: function(obj) {
                        return el(components.Button, {
                                className: attributes.mediaID ? 'image-button' : 'button button-large',
                                onClick: obj.open
                            },
                            !attributes.mediaID ? __('Upload Image') : el('img', {
                                src: attributes.mediaURL
                            })
                        )
                    }
                }),
                el(MediaUpload, {
                    onSelect: onSelectImage2x,
                    type: 'image',
                    value: attributes.mediaID2x,
                    render: function(obj) {
                        return el(components.Button, {
                                className: attributes.mediaID2x ? 'image-button' : 'button button-large',
                                onClick: obj.open
                            },
                            !attributes.mediaID2x ? __('Upload Image') : el('img', {
                                src: attributes.mediaURL2x
                            })
                        )
                    }
                })
            );

            var headline_els = [
                el(
                    TextControl, {
                        label: 'Custom Headline',
                        help: 'req',
                        value: custom_headline,
                        onChange: function(newValue) {
                            props.setAttributes({
                                custom_headline: newValue
                            });
                        }
                    }
                ),
                el(
                    TextControl, {
                        label: 'Custom subtitle',
                        help: 'optional',
                        value: custom_sub,
                        onChange: function(newValue) {
                            props.setAttributes({
                                custom_sub: newValue
                            });
                        }
                    }
                )
            ];

            return [
                el(BlockControls, {
                        key: 'controls'
                    }, // Display controls when the block is clicked on.
                    el('div', {
                            className: 'components-toolbar'
                        },
                        el(MediaUpload, {
                            onSelect: onSelectImage,
                            type: 'image',
                            render: function(obj) {
                                return el(components.Button, {
                                        className: 'components-icon-button components-toolbar__control',
                                        onClick: obj.open
                                    },
                                    // Add Dashicon for media upload button.
                                    el('svg', {
                                            className: 'dashicon dashicons-edit',
                                            width: '20',
                                            height: '20'
                                        },
                                        el('path', {
                                            d: 'M2.25 1h15.5c.69 0 1.25.56 1.25 1.25v15.5c0 .69-.56 1.25-1.25 1.25H2.25C1.56 19 1 18.44 1 17.75V2.25C1 1.56 1.56 1 2.25 1zM17 17V3H3v14h14zM10 6c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2zm3 5s0-6 3-6v10c0 .55-.45 1-1 1H5c-.55 0-1-.45-1-1V8c2 0 3 4 3 4s1-3 3-3 3 2 3 2z'
                                        })
                                    ))
                            }
                        })
                    )
                ),
                el(InspectorControls,
                    null,
                    el(
                        RadioControl, {
                            label: 'Content Type',
                            selected: content_type,
                            options: [{
                                    label: 'Page Title',
                                    value: '4'
                                },
                                {
                                    label: 'Image',
                                    value: '3'
                                },
                                {
                                    label: 'Site Title',
                                    value: '2'
                                },
                                {
                                    label: 'Site Title and Tagline',
                                    value: '1'
                                },
                                {
                                    label: 'Custom',
                                    value: '0'
                                }
                            ],
                            onChange: function(newValue) {
                                props.setAttributes({
                                    content_type: newValue
                                });
                            }
                        }
                    ),

                    el('div', {
                            className: props.className
                        },
                        attributes.content_type == '3' ? image_el : attributes.content_type == '0' ? headline_els : ''

                    )
                ),
                el(ServerSideRender, {
                    block: 'jma-ghb/logo-block',
                    attributes: props.attributes,
                })
            ]
        },

        save: function() {
            return null;
        },
    })
})(
    window.wp.blocks,
    window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
);