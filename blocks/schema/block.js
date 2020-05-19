(function(blocks, editor, components, i18n, element) {
    var __ = i18n.__
    var el = element.createElement
    var registerBlockType = blocks.registerBlockType
    var RichText = editor.RichText
    var BlockControls = editor.BlockControls
    var AlignmentToolbar = editor.AlignmentToolbar
    var MediaUpload = editor.MediaUpload
    var InspectorControls = editor.InspectorControls
    var PanelBody = components.PanelBody
    var ColorPicker = components.ColorPicker
    var TextControl = components.TextControl

    registerBlockType('jma-ghb/schema-block', { // The name of our block. Must be a string with prefix. Example: my-plugin/my-custom-block.
        title: __('Schema'), // The title of our block.
        description: __('A custom block for displaying site schema.'), // The description of our block.
        icon: 'carrot', // Dashicon icon for our block. Custom icons can be added using inline SVGs.
        category: 'common', // The category of the block.
        supports: {
            align: true,
            alignWide: true
        },
        attributes: { // Necessary for saving block content.
            alignment: {
                type: 'string'
            },
            site_url: {
                type: 'string'
            },
            title: {
                type: 'string'
            },
            address: {
                type: 'string'
            },
            po_box: {
                type: 'string'
            },
            city: {
                type: 'string'
            },
            state: {
                type: 'string'
            },
            zip: {
                type: 'string'
            },
            country: {
                type: 'string'
            },
            email: {
                type: 'string'
            },
            phone: {
                type: 'string'
            },
            font_color: {
                type: 'string'
            }
        },

        edit: function(props) {
            var attributes = props.attributes

            function onChangeAlignment(newAlignment) {
                props.setAttributes({
                    alignment: newAlignment
                })
            }

            return [
                el(BlockControls, {
                    key: 'controls'
                }),
                el(InspectorControls, {
                        key: 'inspector'
                    }, // Display the block options in the inspector panel.
                    // Facebook social media text field option.
                    el(TextControl, {
                        label: __('Site URL'),
                        value: attributes.site_url,
                        onChange: function(newvalue) {
                            props.setAttributes({
                                site_url: newvalue
                            })
                        }
                    }),

                    el('p', {}, i18n.__('Font Color')),
                    el(ColorPicker, {
                        type: 'color',
                        color: attributes.font_color,
                        onChangeComplete: function(new_menu_bg) {
                            props.setAttributes({
                                font_color: new_menu_bg.hex
                            })
                        },
                        disableAlpha: true
                    })
                ),
                el('div', {
                        className: props.className
                    },

                    el('div', {
                            className: 'jma-gbh-schema'
                        },
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  title…',
                            value: attributes.title,
                            onChange: function(value) {
                                props.setAttributes({
                                    title: value
                                });
                            },
                        }),
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  address…',
                            value: attributes.address,
                            onChange: function(value) {
                                props.setAttributes({
                                    address: value
                                });
                            },
                        }),
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  PO box…',
                            value: attributes.po_box,
                            onChange: function(value) {
                                props.setAttributes({
                                    po_box: value
                                });
                            },
                        }),
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  city…',
                            value: attributes.city,
                            onChange: function(value) {
                                props.setAttributes({
                                    city: value
                                });
                            },
                        }),
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  state…',
                            value: attributes.state,
                            onChange: function(value) {
                                props.setAttributes({
                                    state: value
                                });
                            },
                        }),
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  zip…',
                            value: attributes.zip,
                            onChange: function(value) {
                                props.setAttributes({
                                    zip: value
                                });
                            },
                        }),
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  country…',
                            value: attributes.country,
                            onChange: function(value) {
                                props.setAttributes({
                                    country: value
                                });
                            },
                        }),
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  email…',
                            value: attributes.email,
                            onChange: function(value) {
                                props.setAttributes({
                                    email: value
                                });
                            },
                        }),
                        el(RichText, {
                            tagName: 'div',
                            placeholder: 'Site  phone…',
                            value: attributes.phone,
                            onChange: function(value) {
                                props.setAttributes({
                                    phone: value
                                });
                            },
                        })
                    )
                )
            ];
        },

        save: function(props) {
            var attributes = props.attributes;
            var alignment = props.attributes.alignment;
            var d = new Date();
            var year = d.getFullYear();
            var style_color = 'color:' + attributes.font_color + ';';


            return (
                el('div', {
                        className: props.className,
                        style: style_color
                    },

                    el('div', {
                            className: 'jma-gbh-schema',
                            itemscope: '',
                            itemtype: 'http://schema.org/Organization',
                        },
                        el('div', {
                                className: 'jma-gbh-schema-item jma-gbh-schema-divider'
                            },
                            el(
                                RichText.Content, {
                                    tagName: 'span',
                                    value: '&copy;' + year + '&nbsp;'
                                }
                            ),
                            attributes.site_url && el('a', {
                                    className: 'jma-gbh-schema-title',
                                    href: attributes.site_url,
                                    itemprop: 'url',
                                    style: style_color
                                },
                                el(
                                    RichText.Content, {
                                        tagName: 'span',
                                        itemprop: 'name',
                                        value: attributes.title
                                    }
                                )
                            )
                        ), el('div', {
                                className: 'jma-gbh-schema-divider',
                                itemscope: '',
                                itemtype: 'http://schema.org/PostalAddress',
                            },
                            attributes.address && el(
                                RichText.Content, {
                                    tagName: 'span',
                                    itemprop: 'streetAddress',
                                    className: 'jma-gbh-schema-item jma-gbh-schema-address',
                                    value: attributes.address + '&nbsp;'
                                }
                            ),
                            attributes.po_box && el(
                                RichText.Content, {
                                    tagName: 'span',
                                    itemprop: 'postOfficeBoxNumber',
                                    className: 'jma-gbh-schema-item jma-gbh-schema-po_box',
                                    value: attributes.po_box + '&nbsp;'
                                }
                            ),
                            attributes.city && el(
                                RichText.Content, {
                                    tagName: 'span',
                                    itemprop: 'addressLocality',
                                    className: 'jma-gbh-schema-item jma-gbh-schema-city',
                                    value: attributes.city + ',&nbsp;'
                                }
                            ),
                            attributes.state && el(
                                RichText.Content, {
                                    tagName: 'span',
                                    itemprop: 'addressRegion',
                                    className: 'jma-gbh-schema-item jma-gbh-schema-state',
                                    value: attributes.state
                                }
                            ),
                            attributes.zip && el(
                                RichText.Content, {
                                    tagName: 'span',
                                    itemprop: 'postalCode',
                                    className: 'jma-gbh-schema-item jma-gbh-schema-zip',
                                    value: '&nbsp;&nbsp;' + attributes.zip
                                }
                            ),
                            attributes.country && el(
                                RichText.Content, {
                                    tagName: 'meta',
                                    itemprop: 'addressCountry',
                                    content: attributes.country
                                }
                            )
                        ),
                        el('div', {
                                className: 'jma-gbh-schema-item jma-gbh-schema-divider'
                            },
                            attributes.email && el('a', {
                                    className: 'jma-gbh-schema-email',
                                    href: 'mailto:' + attributes.email,
                                    itemprop: 'email',
                                    style: style_color
                                },
                                el(
                                    RichText.Content, {
                                        tagName: 'span',
                                        value: attributes.email
                                    }
                                )
                            )
                        ),
                        attributes.phone && el(
                            RichText.Content, {
                                tagName: 'div',
                                itemprop: 'telephone',
                                className: 'jma-gbh-schema-item jma-gbh-schema-phone jma-gbh-schema-divider',
                                value: 'Phone:&nbsp;' + attributes.phone
                            }
                        )
                    )
                )
            )
        }
    })
})(
    window.wp.blocks,
    window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
)