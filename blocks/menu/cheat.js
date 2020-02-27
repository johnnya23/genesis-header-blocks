(function(blocks, blockEditor, components, i18n, element, compose) {

    var el = wp.element.createElement
    var registerBlockType = wp.blocks.registerBlockType
    var RichText = wp.blockEditor.RichText
    var BlockControls = wp.blockEditor.BlockControls
    var AlignmentToolbar = wp.blockEditor.AlignmentToolbar
    var MediaUpload = wp.blockEditor.MediaUpload
    var InspectorControls = wp.blockEditor.InspectorControls
    var TextControl = components.TextControl
    var ColorPicker = components.ColorPicker
    var RadioControl = components.RadioControl

    registerBlockType('jma-ghb/menu-block', { // The name of our block. Must be a string with prefix. Example: my-plugin/my-custom-block.
        title: i18n.__('Insert Menu'), // The title of our block.
        description: i18n.__('A custom block for displaying WordPress Menus'), // The description of our block.
        icon: 'video-alt3', // Dashicon icon for our block. Custom icons can be added using inline SVGs.
        category: 'common', // The category of the block.

        edit: function(props) {
            var attributes = props.attributes

            var id = props.attributes.id,
                use_bg = props.attributes.use_bg,
                use_bg = props.attributes.use_bg,
                menu_bg = props.attributes.menu_bg,
                menu_font = props.attributes.menu_font,
                menu_bg_hover = props.attributes.menu_bg_hover,
                menu_font_hover = props.attributes.menu_font_hover,
                menu_bg_active = props.attributes.menu_bg_active,
                menu_font_active = props.attributes.menu_font_active

            var ServerSideRender = wp.components.ServerSideRender

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
                    el(components.PanelBody, {
                            title: i18n.__('Add a Menu'),
                            className: 'jma-ghb-values',
                            initialOpen: true
                        },
                        el('p', {}, i18n.__('Values for display of Menu(s).')),
                        // Video id text field option.
                        el(TextControl, {
                            type: 'text',
                            label: i18n.__('YouTube List ID'),
                            value: id,
                            onChange: function(new_id) {
                                props.setAttributes({
                                    id: new_id
                                })
                            }
                        }), el(
                            RadioControl, {
                                label: 'Radio Field',
                                selected: use_bg,
                                options: [{
                                        label: 'Yes',
                                        value: 'yes'
                                    },
                                    {
                                        label: 'No',
                                        value: 'no'
                                    }
                                ],
                                onChange: function(newValue) {
                                    props.setAttributes({
                                        use_bg: newValue
                                    });
                                }
                            }
                        ),
                        el('p', {}, i18n.__('Menu Background')),
                        el(ColorPicker, {
                            type: 'color',
                            color: menu_bg,
                            onChangeComplete: function(new_menu_bg) {
                                props.setAttributes({
                                    menu_bg: new_menu_bg.hex
                                })
                            },
                            disableAlpha: true
                        }),
                        el('p', {}, i18n.__('Menu Font')),
                        el(ColorPicker, {
                            type: 'color',
                            color: menu_font,
                            onChangeComplete: function(new_menu_font) {
                                props.setAttributes({
                                    menu_font: new_menu_font.hex
                                })
                            },
                            disableAlpha: true
                        }),
                        el('p', {}, i18n.__('Menu Background Hover')),
                        el(ColorPicker, {
                            type: 'color',
                            color: menu_bg_hover,
                            onChangeComplete: function(new_menu_bg_hover) {
                                props.setAttributes({
                                    menu_bg_hover: new_menu_bg_hover.hex
                                })
                            },
                            disableAlpha: true
                        }),
                        el('p', {}, i18n.__('Menu Font Hover')),
                        el(ColorPicker, {
                            type: 'color',
                            color: menu_font_hover,
                            onChangeComplete: function(new_menu_font_hover) {
                                props.setAttributes({
                                    menu_font_hover: new_menu_font_hover.hex
                                })
                            },
                            disableAlpha: true
                        }),
                        el('p', {}, i18n.__('Menu Background Active')),
                        el(ColorPicker, {
                            type: 'color',
                            color: menu_bg_active,
                            onChangeComplete: function(new_menu_bg_active) {
                                props.setAttributes({
                                    menu_bg_active: new_menu_bg_active.hex
                                })
                            },
                            disableAlpha: true
                        }),
                        el('p', {}, i18n.__('Menu Font Active')),
                        el(ColorPicker, {
                            type: 'color',
                            color: menu_font_active,
                            onChangeComplete: function(new_menu_font_active) {
                                props.setAttributes({
                                    menu_font_active: new_menu_font_active.hex
                                })
                            },
                            disableAlpha: true
                        })

                    )
                ),
                el(ServerSideRender, {
                    block: 'jma-ghb/menu-block',
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
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
)