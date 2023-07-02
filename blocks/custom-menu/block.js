(function (blocks, components, element ) {
    console.log(components)
    console.log(element)
  var el = element.createElement;
  var registerBlockType = blocks.registerBlockType;
  var SelectControl = components.SelectControl;
  var ServerSideRender = components.ServerSideRender;
  var withSelect = wp.data.withSelect;
  console.log(SelectControl)
  console.log(ServerSideRender)
  

  registerBlockType('jma-menu-block/menu-block', {
      title: 'JMA Custom Menu Block',
      icon: 'menu',
      category: 'common',
      attributes: {
          selectedMenu: {
              type: 'string'
          },
          options: {
              type: 'string'
          },
      },
      edit: withSelect(function (select) {
          var menus = select('core').getEntityRecords('taxonomy', 'nav_menu');

          var options = [
              { value: '', label: 'Select a menu' },
              // Add logic here to dynamically populate the options based on the available menus
              // Example: menus.map(menu => ({ value: menu.id, label: menu.name })),
          ];

          return {
              selectedMenu: select('core/editor').getEditedPostAttribute('meta')['selectedMenu'] || '',
              menus: menus,
              options: options,
          };
      })
      (function (props) {
        //var ServerSideRender = wp.components.ServerSideRender;
          var selectedMenu = props.selectedMenu;
          var options = props.options;
          function onChangeMenu(newMenu) {
              props.setAttributes({ selectedMenu: newMenu });
          }
          

          return [el('div', { className: 'jma-menu-block' }, [
             /* el(SelectControl, {
                  label: 'Select Menu',
                  value: selectedMenu,
                  options: options,
                  onChange: onChangeMenu,
              }),*/
             /*el(ServerSideRender, {
                  block: 'jma-menu-block/menu-block',
                  attributes: props.attributes,
              }),*/
          ])];
      }),

      save: function () {
          // Rendering is handled server-side, so we don't need to output anything here
          return null;
      },
  });
})(
  window.wp.blocks,
  window.wp.element,
  window.wp.components,
  window.wp.data
);