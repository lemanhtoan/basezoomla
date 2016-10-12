//There is no editor on start-up...
PDG.htmlEditorInst=null;

//////////////////////////////////////////////////////////////////////
/*editTextAreaEx
*/
//////////////////////////////////////////////////////////////////////
PDG.editTextAreaEx = function(vWindow,vTrigger,vTextElement,vTitle,region)
{
	if(PDG.htmlEditorInst==null)
	{
		PDG.htmlEditorInst=new PDG.widget.html_editor();
		PDG.htmlEditorInst.init(vWindow,vTrigger,vTextElement,vTitle,region);
		//document.body.style.cursor='wait';
		vTrigger.style.cursor='wait';
	}
	else
	{
		vTrigger.style.cursor='pointer';
		//document.body.style.cursor='default';
		PDG.htmlEditorInst.editAreaEx(vTextElement,vTrigger,vTitle,region);
		PDG.htmlEditorInst.setTitleText(vTitle);
	}
}
//////////////////////////////////////////////////////////////////////
/*PDG.widget.html_editor definition
*/
//////////////////////////////////////////////////////////////////////
PDG.widget.html_editor=function()
{
	this.state='off';
	this.editor=null;
	this.editing=null;
	this.config=null;
	this.titleText=null;
	this.fullscreen=false;
	this.default_width=0;
	this.default_height=0;
}
//////////////////////////////////////////////////////////////////////
/*editAreaEx
*/
//////////////////////////////////////////////////////////////////////
PDG.widget.html_editor.prototype.editAreaEx=function(tar,trigger,vTitle,region)
{
	var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event
		myEditor=this.editor;
	if (this.editing !== null) {		
		this.editor.saveHTML();
		this.editing.value = this.editor.get('element').value;
	}
	
	//var xy = Dom.getXY(tar);	
	this.editor.setEditorHTML(tar.value);

	this.editor.hide(); 

	var w=530;
	var h=350;
	var wregion = YAHOO.util.Dom.getRegion(window.document.body);
	var x=((wregion.right - wregion.left)-w)/2;
	var y=((wregion.bottom - wregion.top)-h)/2;
	Dom.setXY(this.editor.get('element_cont').get('element'),[x,y]);
	this.editing = tar;		
	this.updateOverlay();
	$('#main-editor-overlay').show();
	this.editor.show();
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.expandcollapse
*/
//////////////////////////////////////////////////////////////////////
PDG.widget.html_editor.prototype.updateOverlay=function()
{
	var Dom = YAHOO.util.Dom;
	var mOverlay=Dom.get('main-editor-overlay');	
	var region = YAHOO.util.Dom.getRegion(window.document.body);
	Dom.setStyle(mOverlay, 'width',((region.right - region.left)-4)+'px');
	Dom.setStyle(mOverlay, 'height',((region.bottom - region.top)-4)+'px');
//	Dom.setStyle(mOverlay, 'top',(region.top-22)+ 'px');
//	Dom.setStyle(mOverlay, 'left',(region.left-22)+ 'px');
}
//////////////////////////////////////////////////////////////////////
/*setTitleText
*/
//////////////////////////////////////////////////////////////////////
PDG.widget.html_editor.prototype.setTitleText=function(sTitle)
{
	if(this.titleText==null)
	{
		var vt=this.editor.toolbar._titlebar;
		if(vt.childNodes.length>=2)
		{
			if(vt.childNodes[0].childNodes.length==1)
				this.titleText=vt.childNodes[0].childNodes[0];
		}
	}

	if(this.titleText!=null)
		this.titleText.innerHTML=sTitle;
}
//////////////////////////////////////////////////////////////////////
/*init
*/
//////////////////////////////////////////////////////////////////////
PDG.widget.html_editor.prototype.init=function(vWindow,vTrigger,vTextElement,vTitle,region)
{
	this.initConfig();
	
	var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event
		myEditor=null;
	var _this=this;
		
    YAHOO.log('Setup a stripped down config for the editor', 'info', 'example');

    YAHOO.log('Override the prototype of the toolbar to use a different string for the collapse button', 'info', 'example');
    YAHOO.widget.Toolbar.prototype.STR_COLLAPSE = 'Click to close the editor.';
    YAHOO.log('Create the Editor..', 'info', 'example');
    myEditor = new YAHOO.widget.Editor('editor',this.config);
    myEditor.on('toolbarLoaded', function() {
        YAHOO.log('Toolbar is loaded, add a listener for the toolbarCollapsed event', 'info', 'example');
		
        this.toolbar.on('toolbarCollapsed', function() {
            YAHOO.log('Toolbar was collapsed, reposition and save the editors data', 'info', 'example');
            Dom.setXY(this.get('element_cont').get('element'), [-99999, -99999]);
            Dom.removeClass(this.toolbar.get('cont').parentNode, 'yui-toolbar-container-collapsed');
            myEditor.saveHTML();
            _this.editing.value = myEditor.get('element').value;
            _this.editing = null;
			$('#main-editor-overlay').hide();
        }, myEditor, true);
		
        var codeConfig = {
            type: 'push', label: 'Edit HTML Code', value: 'editcode'
        };
		
        YAHOO.log('Create the (editcode) Button', 'info', 'example');
        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');
        
        this.toolbar.on('editcodeClick', function() {
			var vEditor=Dom.get('editor');
            var ta = this.get('element'),
                iframe = this.get('iframe').get('element');

            if (_this.state == 'on') {
                _this.state = 'off';
                this.toolbar.set('disabled', false);
                YAHOO.log('Show the Editor', 'info', 'example');
                YAHOO.log('Inject the HTML from the textarea into the editor', 'info', 'example');
                this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
				vEditor.style.display='none';
                this.show();
                this._focusWindow();
            } else {
                _this.state = 'on';
                YAHOO.log('Show the Code Editor', 'info', 'example');
                this.cleanHTML();
                YAHOO.log('Save the Editors HTML', 'info', 'example');
                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();
				vEditor.style.display='block';
            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            YAHOO.log('cleanHTML callback fired..', 'info', 'example');
            this.get('element').value = ev.html;
        }, this, true);
        
        this.on('afterRender', function() {
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
			
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');
            this.addClass('editor-hidden');
			_this.default_width=_this.editor.get('width');
			_this.default_height=_this.editor.get('height');
			
        }, this, true);					
		
		this.on('editorContentLoaded', function() {
			PDG.editTextAreaEx(vWindow,vTrigger,vTextElement,vTitle,region);
        }, this, true);					
		
    }, myEditor, true);
	
	this.editor=myEditor;
	
	
	$('#main-editor-overlay').click(function(){
            Dom.setXY(myEditor.get('element_cont').get('element'), [-99999, -99999]);
            Dom.removeClass(myEditor.toolbar.get('cont').parentNode, 'yui-toolbar-container-collapsed');
            myEditor.saveHTML();
            _this.editing.value = myEditor.get('element').value;
            _this.editing = null;
			$('#main-editor-overlay').hide();											 
	});
	
	myEditor.render();
}
//////////////////////////////////////////////////////////////////////
/*initConfig
*/
//////////////////////////////////////////////////////////////////////
PDG.widget.html_editor.prototype.initConfig=function()
{
    this.config= {
        height: '350px',
        width: '530px',
        animate: false,
        dompath: true,
        focusAtStart: true,
		toolbar: {
					collapse: true,
					close:true,  
					titlebar: 'Editing: description goes here',
					draggable: false,
					buttons: [
						{ group: 'fontstyle', label: 'Font Name and Size',
							buttons: [
								{ type: 'select', label: 'Arial', value: 'fontname', disabled: true,
									menu: [
										{ text: 'Arial', checked: true },
										{ text: 'Arial Black' },
										{ text: 'Comic Sans MS' },
										{ text: 'Courier New' },
										{ text: 'Lucida Console' },
										{ text: 'Tahoma' },
										{ text: 'Times New Roman' },
										{ text: 'Trebuchet MS' },
										{ text: 'Verdana' }
									]
								},
								{ type: 'spin', label: '13', value: 'fontsize', range: [ 9, 75 ], disabled: true }
							]
						},
						{ type: 'separator' },
						{ group: 'textstyle', label: 'Font Style',
							buttons: [
								{ type: 'push', label: 'Bold CTRL + SHIFT + B', value: 'bold' },
								{ type: 'push', label: 'Italic CTRL + SHIFT + I', value: 'italic' },
								{ type: 'push', label: 'Underline CTRL + SHIFT + U', value: 'underline' },
								{ type: 'separator' },
								{ type: 'push', label: 'Subscript', value: 'subscript', disabled: true },
								{ type: 'push', label: 'Superscript', value: 'superscript', disabled: true },
								{ type: 'separator' },
								{ type: 'color', label: 'Font Color', value: 'forecolor', disabled: true },
								{ type: 'color', label: 'Background Color', value: 'backcolor', disabled: true },
								{ type: 'separator' },
								{ type: 'push', label: 'Remove Formatting', value: 'removeformat', disabled: true },
								{ type: 'push', label: 'Show/Hide Hidden Elements', value: 'hiddenelements' }
							]
						},
						{ type: 'separator' },
						{ group: 'alignment', label: 'Alignment',
							buttons: [
								{ type: 'push', label: 'Align Left CTRL + SHIFT + [', value: 'justifyleft' },
								{ type: 'push', label: 'Align Center CTRL + SHIFT + |', value: 'justifycenter' },
								{ type: 'push', label: 'Align Right CTRL + SHIFT + ]', value: 'justifyright' },
								{ type: 'push', label: 'Justify', value: 'justifyfull' }
							]
						},
						{ type: 'separator' },
						{ group: 'parastyle', label: 'Paragraph Style',
							buttons: [
							{ type: 'select', label: 'Normal', value: 'heading', disabled: true,
								menu: [
									{ text: 'Normal', value: 'none', checked: true },
									{ text: 'Header 1', value: 'h1' },
									{ text: 'Header 2', value: 'h2' },
									{ text: 'Header 3', value: 'h3' },
									{ text: 'Header 4', value: 'h4' },
									{ text: 'Header 5', value: 'h5' },
									{ text: 'Header 6', value: 'h6' }
								]
							}
							]
						},
						{ type: 'separator' },
						{ group: 'indentlist', label: 'Indenting and Lists',
							buttons: [
								{ type: 'push', label: 'Indent', value: 'indent', disabled: true },
								{ type: 'push', label: 'Outdent', value: 'outdent', disabled: true },
								{ type: 'push', label: 'Create an Unordered List', value: 'insertunorderedlist' },
								{ type: 'push', label: 'Create an Ordered List', value: 'insertorderedlist' }
							]
						},
						{ type: 'separator' },
						{ group: 'insertitem', label: 'Insert Item',
							buttons: [
								{ type: 'push', label: 'HTML Link CTRL + SHIFT + L', value: 'createlink', disabled: true },
								{ type: 'push', label: 'Insert Image', value: 'insertimage' }
							]
						}
					]

				}
    };
}