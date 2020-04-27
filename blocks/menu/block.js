(function(blocks, editor, components, i18n, element) {
    var el = element.createElement,
        Fragment = element.Fragment,
        registerBlockType = blocks.registerBlockType,
        InspectorControls = editor.InspectorControls,
        RadioControl = components.RadioControl;
    //ColorPicker = wp.components.ColorPicker;

    registerBlockType('jma-ghb/menu-block', {
        title: 'Menu Block',
        icon: 'menu',

        category: 'layout',
        supports: {
            align: true,
            alignWide: true
        },

        attributes: {
            nav_val: {
                type: 'string'
            },
        },

        edit: function(props) {
            var content = props.attributes.content,
                nav_val = props.attributes.nav_val,
                ServerSideRender = wp.components.ServerSideRender;


            return [
                el(
                    InspectorControls,
                    null,
                    el(
                        RadioControl, {
                            label: 'Nav Location',
                            selected: nav_val,
                            options: [{
                                    label: 'Primary',
                                    value: 'primary'
                                },
                                {
                                    label: 'Secondary',
                                    value: 'second'
                                }
                            ],
                            onChange: function(newValue) {
                                props.setAttributes({
                                    nav_val: newValue
                                });
                            }
                        }
                    )
                ),
                el(ServerSideRender, {
                    block: 'jma-ghb/menu-block',
                    attributes: props.attributes,
                })
            ];
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