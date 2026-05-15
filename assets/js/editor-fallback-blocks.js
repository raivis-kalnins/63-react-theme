(function(wp){
  if (!wp || !wp.blocks || !wp.element || !wp.blockEditor || !wp.components) return;

  var el = wp.element.createElement;
  var registerBlockType = wp.blocks.registerBlockType;
  var getBlockType = wp.blocks.getBlockType;
  var useBlockProps = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var TextControl = wp.components.TextControl;
  var RangeControl = wp.components.RangeControl;
  var PanelBody = wp.components.PanelBody;

  function registerGoogleMapFallback(){
    if (getBlockType('wpbb/google-map')) return;

    registerBlockType('wpbb/google-map', {
      title: 'Google Map',
      icon: 'location-alt',
      category: 'wpbb',
      attributes: {
        address: { type: 'string', default: '' },
        zoom: { type: 'number', default: 14 },
        height: { type: 'string', default: '380px' },
        embedUrl: { type: 'string', default: '' },
        overlayColor: { type: 'string', default: '' },
        overlayOpacity: { type: 'number', default: 0.2 },
        mapFilter: { type: 'string', default: '' }
      },
      edit: function(props){
        var attrs = props.attributes || {};
        var address = attrs.address || 'Bauskas iela 63, Rīga';
        var zoom = Number(attrs.zoom || 14);
        var height = attrs.height || '380px';
        var src = attrs.embedUrl || ('https://maps.google.com/maps?q=' + encodeURIComponent(address) + '&t=&z=' + zoom + '&ie=UTF8&iwloc=&output=embed');

        return el(wp.element.Fragment, {},
          el(InspectorControls, {},
            el(PanelBody, { title: 'Google Map settings', initialOpen: true }, [
              el(TextControl, {
                key: 'address',
                label: 'Address',
                value: address,
                onChange: function(v){ props.setAttributes({ address: v }); }
              }),
              el(RangeControl, {
                key: 'zoom',
                label: 'Zoom',
                value: zoom,
                min: 1,
                max: 21,
                onChange: function(v){ props.setAttributes({ zoom: Number(v || 14) }); }
              }),
              el(TextControl, {
                key: 'height',
                label: 'Height',
                value: height,
                onChange: function(v){ props.setAttributes({ height: v }); }
              }),
              el(TextControl, {
                key: 'embedUrl',
                label: 'Embed URL (optional)',
                value: attrs.embedUrl || '',
                onChange: function(v){ props.setAttributes({ embedUrl: v }); }
              })
            ])
          ),
          el('div', useBlockProps({ className: 'wpbb-google-map-editor' }), [
            el('div', { className: 'wpbb-editor-label' }, 'WP BBuilder Google Map'),
            el('iframe', {
              title: 'Google Map',
              src: src,
              style: { width: '100%', height: height, border: 0, borderRadius: '16px' },
              loading: 'lazy'
            })
          ])
        );
      },
      save: function(){ return null; }
    });
  }

  function registerDynamicFormFallback(){
    if (getBlockType('wpbb/dynamic-form')) return;

    registerBlockType('wpbb/dynamic-form', {
      title: 'Dynamic Form',
      icon: 'feedback',
      category: 'wpbb',
      attributes: {
        formTitle: { type: 'string', default: 'Contact form' },
        recipient: { type: 'string', default: '' },
        emailSubject: { type: 'string', default: 'New form submission' },
        successMessage: { type: 'string', default: 'Thank you for your submission!' },
        submitText: { type: 'string', default: 'Submit' },
        showTitle: { type: 'boolean', default: true },
        formClass: { type: 'string', default: 'wpbb-form' },
        buttonClass: { type: 'string', default: 'btn btn-primary' },
        fieldsJson: { type: 'string', default: '' }
      },
      edit: function(props){
        var attrs = props.attributes || {};
        return el(wp.element.Fragment, {},
          el(InspectorControls, {},
            el(PanelBody, { title: 'Form settings', initialOpen: true }, [
              el(TextControl, { label: 'Form title', value: attrs.formTitle || '', onChange: function(v){ props.setAttributes({ formTitle: v }); } }),
              el(TextControl, { label: 'Recipient', value: attrs.recipient || '', onChange: function(v){ props.setAttributes({ recipient: v }); } }),
              el(TextControl, { label: 'Submit text', value: attrs.submitText || '', onChange: function(v){ props.setAttributes({ submitText: v }); } })
            ])
          ),
          el('div', useBlockProps({ className: 'wpbb-dynamic-form-editor' }), [
            attrs.showTitle ? el('h3', {}, attrs.formTitle || 'Contact form') : null,
            el('p', {}, 'WP BBuilder Dynamic Form'),
            el('div', { className: 'wpbb-form-preview-row' }, [
              el('input', { disabled: true, placeholder: 'Name' }),
              el('input', { disabled: true, placeholder: 'Email' })
            ]),
            el('textarea', { disabled: true, placeholder: 'Message' }),
            el('button', { type: 'button', className: attrs.buttonClass || 'btn btn-primary' }, attrs.submitText || 'Submit')
          ])
        );
      },
      save: function(){ return null; }
    });
  }

  function registerFallbacks(){
    registerGoogleMapFallback();
    registerDynamicFormFallback();
  }

  if (wp.domReady) {
    wp.domReady(registerFallbacks);
    wp.domReady(function(){ setTimeout(registerFallbacks, 600); });
  } else {
    registerFallbacks();
    setTimeout(registerFallbacks, 600);
  }
})(window.wp);
