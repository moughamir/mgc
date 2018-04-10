/* Custom View for YP Accordion */
!function( $ ) {

  if ( typeof window.vc === 'undefined' ) {
    return;
  }

  window.YPAccordionView = vc.shortcode_view.extend( {
    adding_new_tab: false,
    events: {
      'click .add_tab': 'addTab',
      'click > .vc_controls .column_delete, > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
      'click > .vc_controls .column_edit, > .vc_controls .vc_control-btn-edit': 'editElement',
      'click > .vc_controls .column_clone,> .vc_controls .vc_control-btn-clone': 'clone'
    },
    render: function () {
      window.YPAccordionView.__super__.render.call( this );
      // check user role to add controls
      if ( ! this.hasUserAccess() ) {
        return this;
      }
      this.$content.sortable( {
        axis: "y",
        handle: "h3",
        stop: function ( event, ui ) {
          // IE doesn't register the blur when sorting
          // so trigger focusout handlers to remove .ui-state-focus
          ui.item.prev().triggerHandler( "focusout" );
          $( this ).find( '> .wpb_sortable' ).each( function () {
            var shortcode = $( this ).data( 'model' );
            shortcode.save( { 'order': $( this ).index() } ); // Optimize
          } );
        }
      } );
      return this;
    },
    changeShortcodeParams: function ( model ) {
      var params, collapsible;

      window.YPAccordionView.__super__.changeShortcodeParams.call( this, model );
      params = model.get( 'params' );
      collapsible = _.isString( params.collapsible ) && params.collapsible === 'yes' ? true : false;
      if ( this.$content.hasClass( 'ui-accordion' ) ) {
        this.$content.accordion( "option", "collapsible", collapsible );
      }
    },
    changedContent: function ( view ) {
      if ( this.$content.hasClass( 'ui-accordion' ) ) {
        this.$content.accordion( 'destroy' );
      }
      var collapsible = _.isString( this.model.get( 'params' ).collapsible ) && this.model.get( 'params' ).collapsible === 'yes' ? true : false;
      this.$content.accordion( {
        header: "h3",
        navigation: false,
        autoHeight: true,
        heightStyle: "content",
        collapsible: collapsible,
        active: this.adding_new_tab === false && view.model.get( 'cloned' ) !== true ? 0 : view.$el.index()
      } );
      this.adding_new_tab = false;
    },
    addTab: function ( e ) {
      e.preventDefault();
      // check user role to add controls
      if ( ! this.hasUserAccess() ) {
        return false;
      }
      this.adding_new_tab = true;
      vc.shortcodes.create( {
        shortcode: 'yp_accordion_tab',
        params: { title: window.i18nLocale.section },
        parent_id: this.model.id
      } );
    },
    _loadDefaults: function () {
      window.YPAccordionView.__super__._loadDefaults.call( this );
    }
  } );

  window.YPAccordionTabView = window.VcColumnView.extend( {
    events: {
      'click > [data-element_type] > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
      'click > [data-element_type] > .vc_controls .vc_control-btn-prepend': 'addElement',
      'click > [data-element_type] > .vc_controls .vc_control-btn-edit': 'editElement',
      'click > [data-element_type] > .vc_controls .vc_control-btn-clone': 'clone',
      'click > [data-element_type] > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
    },
    setContent: function () {
      this.$content = this.$el.find( '> [data-element_type] > .wpb_element_wrapper > .vc_container_for_children' );
    },
    changeShortcodeParams: function ( model ) {
      var params;

      window.YPAccordionTabView.__super__.changeShortcodeParams.call( this, model );
      params = model.get( 'params' );
      if ( _.isObject( params ) && _.isString( params.title ) ) {
        this.$el.find( '> h3 .tab-label' ).text( params.title );
      }
    },
    setEmpty: function () {
      $( '> [data-element_type]', this.$el ).addClass( 'vc_empty-column' );
      this.$content.addClass( 'vc_empty-container' );
    },
    unsetEmpty: function () {
      $( '> [data-element_type]', this.$el ).removeClass( 'vc_empty-column' );
      this.$content.removeClass( 'vc_empty-container' );
    }
  } );

}( jQuery );