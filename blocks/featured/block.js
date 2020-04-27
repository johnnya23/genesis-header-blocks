(function(blocks, element, components, blockEditor) {
    var el = element.createElement,
        MediaUpload = blockEditor.MediaUpload,
        BlockControls = blockEditor.BlockControls,
        TextControl = components.TextControl,
        InspectorControls = blockEditor.InspectorControls,
        InnerBlocks = blockEditor.InnerBlocks,
        RadioControl = components.RadioControl;
    blocks.registerBlockType('jma-ghb/featued-block', {
        title: 'Featured Image',
        category: 'layout',
        supports: {
            alignWide: true
        },
        attributes: {
            display_height: {
                type: 'string'
            },
            display_width: {
                type: 'string'
            },
            mediaURL: {
                type: 'string'
            },
            mediaID: {
                type: 'number'
            },
            vertical_alignment: {
                type: 'string'
            },
        },
        edit: function edit(props) {
            var attributes = props.attributes,
                display_width = props.attributes.display_width,
                display_height = props.attributes.display_height,
                vertical_alignment = props.attributes.vertical_alignment,
                mediaURL = props.attributes.mediaURL,
                mediaID = props.attributes.mediaID,
                ServerSideRender = wp.components.ServerSideRender;

            var onSelectImage = function(media) {
                props.setAttributes({
                    mediaURL: media.url,
                    mediaID: media.id
                });
            };

            return [
                el(BlockControls, {
                    key: 'controls'
                }),
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
                                )
                            );
                        }
                    })
                ),
                el(InspectorControls,
                    null,
                    el(RadioControl, {
                        label: 'Width',
                        selected: display_width,
                        options: [{
                                label: 'Theme Width',
                                value: '0'
                            },
                            {
                                label: 'Full Page Width',
                                value: '1'
                            }
                        ],
                        onChange: function(newValue) {
                            props.setAttributes({
                                display_width: newValue
                            });
                        }
                    }),

                    el(TextControl, {
                        label: 'Height',
                        help: 'use px, em, etc OR something like calc(100vh - 100px)',
                        value: display_height,
                        onChange: function(newValue) {
                            props.setAttributes({
                                display_height: newValue
                            });
                        }
                    }),
                    el(RadioControl, {
                        label: 'Vertical Alignment',
                        selected: vertical_alignment,
                        options: [{
                                label: 'Top',
                                value: '1'
                            },
                            {
                                label: 'Middle',
                                value: '0'
                            },
                            {
                                label: 'Bottom',
                                value: '2'
                            }
                        ],
                        onChange: function(newValue) {
                            props.setAttributes({
                                vertical_alignment: newValue
                            });
                        }
                    })
                ),
                el('div', {
                        className: props.className
                    },
                    attributes.mediaURL &&
                    el(
                        'div', {
                            className: 'inner-visual'
                        },
                        el('img', {
                            src: attributes.mediaURL
                        })
                    ),
                    el('div', {
                        className: 'inner-content'
                    }, el(InnerBlocks))
                )
            ];
        },
        save: function save(props) {
            var attributes = props.attributes;
            return el(InnerBlocks.Content);
        }
    });
})(window.wp.blocks, window.wp.element,
    window.wp.components, window.wp.blockEditor);