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
            display_height_fallback: {
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
            post_id: {
                type: 'number'
            },
            yt_id: {
                type: 'string'
            },
        },
        edit: function edit(props) {
            var attributes = props.attributes,
                display_width = props.attributes.display_width,
                display_height = props.attributes.display_height,
                display_height_fallback = props.attributes.display_height_fallback,
                vertical_alignment = props.attributes.vertical_alignment,
                yt_id = props.attributes.yt_id,
                post_id = props.attributes.post_id,
                mediaURL = props.attributes.mediaURL,
                mediaID = props.attributes.mediaID,
                ServerSideRender = wp.components.ServerSideRender;

            var onSelectImage = function(media) {
                props.setAttributes({
                    mediaURL: media.url,
                    mediaID: media.id
                });
            };
            var onDeSelectImage = function(media) {
                props.setAttributes({
                    mediaURL: '',
                    mediaID: ''
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
                    }),
                    el(MediaUpload, {
                        //onSelect: onDeSelectImage,
                        type: 'image',
                        render: function(obj) {
                            return el(components.Button, {
                                className: 'components-icon-button components-toolbar__control',
                                onClick: onDeSelectImage
                            }, el('svg', {
                                    className: 'dashicon dashicons-edit',
                                    width: '20',
                                    height: '20'
                                },
                                el('path', {
                                    d: 'M 19.695312 16.097656 L 13.597656 10 L 19.695312 3.902344 C 20.101562 3.492188 20.101562 2.835938 19.695312 2.425781 L 17.574219 0.304688 C 17.164062 -0.101562 16.507812 -0.101562 16.097656 0.304688 L 10 6.402344 L 3.902344 0.304688 C 3.492188 -0.101562 2.835938 -0.101562 2.425781 0.304688 L 0.304688 2.425781 C -0.101562 2.835938 -0.101562 3.492188 0.304688 3.902344 L 6.402344 10 L 0.304688 16.097656 C -0.101562 16.507812 -0.101562 17.164062 0.304688 17.574219 L 2.425781 19.695312 C 2.835938 20.101562 3.492188 20.101562 3.902344 19.695312 L 10 13.597656 L 16.097656 19.695312 C 16.507812 20.101562 17.164062 20.101562 17.574219 19.695312 L 19.695312 17.574219 C 20.101562 17.164062 20.101562 16.507812 19.695312 16.097656 Z M 19.695312 16.097656'
                                })
                            ))
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
                        help: 'use px, em, etc OR maybe calc(100vh - 100px)',
                        value: display_height,
                        onChange: function(newValue) {
                            props.setAttributes({
                                display_height: newValue
                            });
                        }
                    }),

                    el(TextControl, {
                        label: 'Height Fallback for calc(optional)',
                        help: 'a fallback for browsers that don\'t support calc',
                        value: display_height_fallback,
                        onChange: function(newValue) {
                            props.setAttributes({
                                display_height_fallback: newValue
                            });
                        }
                    }),
                    el(RadioControl, {
                        label: 'Vertical Alignment',
                        selected: vertical_alignment,
                        options: [{
                                label: 'Top',
                                value: 'flex-start'
                            },
                            {
                                label: 'Center',
                                value: 'center'
                            },
                            {
                                label: 'Fill',
                                value: 'space-between'
                            },
                            {
                                label: 'Bottom',
                                value: 'flex-end'
                            }
                        ],
                        onChange: function(newValue) {
                            props.setAttributes({
                                vertical_alignment: newValue
                            });
                        }
                    }),

                    el(TextControl, {
                        label: 'Youtube Video Id(optional)',
                        help: 'a video to load under the image',
                        value: yt_id,
                        onChange: function(newValue) {
                            props.setAttributes({
                                yt_id: newValue
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