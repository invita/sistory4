si4.widget.si4Input = function(args)
{
    // Init
    var _p = this;

    this._cons = si4.widget.si4Element;
    this._cons({ parent: args.parent, hint: args.hint, style: args.style, tagClass: args.tagClass });
    this.selector.addClass("si4InputDiv");

    this._eventb = si4.object.si4EventBase;
    this._eventb();

    // Settings
    this.type = si4.getArg(args, "type", "text");
    this.inputType = this.type;
    switch(this.type){
        case "textarea":case "codemirror": this.inputTagName = "textarea"; break;
        case "select": this.inputTagName = "select"; break;
        case "flat": this.inputTagName = "div"; this.inputType = null; break;
        default: this.inputTagName = "input"; break;
    }

    this.name = si4.getArg(args, "name", null);
    this.value = si4.getArg(args, "value", "");
    this.values = si4.getArg(args, "values", {});
    this.addEmptyOption = si4.getArg(args, "addEmptyOption", false);
    this.placeholder = si4.getArg(args, "placeholder", "");
    this.placeholder2 = si4.getArg(args, "placeholder2", "");
    this.withCode = si4.getArg(args, "withCode", null);
    this.readOnly = si4.getArg(args, "readOnly", false);
    this.disabled = si4.getArg(args, "disabled", false);
    this.focus = si4.getArg(args, "focus", false);
    this.gradient = si4.getArg(args, "gradient", null);
    this.caption = si4.getArg(args, "caption", null);
    this.captionWidth = si4.getArg(args, "captionWidth", null);
    this.showModified = si4.getArg(args, "showModified", true);
    this.lookup = si4.getArg(args, "lookup", null);
    this.form = si4.getArg(args, "form", null);
    this.inputArray = si4.getArg(args, "inputArray", null);
    this.inputClass = si4.getArg(args, "inputClass", "");
    this.autoComplete = si4.getArg(args, "autoComplete", null);
    this.accept = si4.getArg(args, "accept", null);
    this.secondInput = si4.getArg(args, "secondInput", false);
    this.secondInputName = si4.getArg(args, "secondInputName", "value2");
    this.halfWidth = si4.getArg(args, "halfWidth", false);

    // Events
    this.onKeyDown = function(f) { _p.subscribe("onKeyDown", f); };
    this.onKeyPressed = function(f) { _p.subscribe("onKeyPressed", f); };
    this.onKeyUp = function(f) { _p.subscribe("onKeyUp", f); };
    this.onEnterPressed = function(f) { _p.subscribe("onEnterPressed", f); };
    this.onModified = function(f) { _p.subscribe("onModified", f); };

    this.onPaste = function(f) { _p.subscribe("onPaste", f); };

    this.inputs = [];

    // Create elements
    if (this.secondInput) {
        this.input2 = new si4.widget.si4Element({ parent:this.selector, tagName:"input", tagClass: "si4Input", attr: { type: "text" } });
        if (this.halfWidth) this.input2.selector.addClass("half");
        this.inputs.push(this.input2);
    }

    this.input = new si4.widget.si4Element({ parent:this.selector, tagName:this.inputTagName, tagClass:this.inputClass });
    this.input.selector.addClass("si4Input");
    if (this.inputType == "codemirror")
        this.input.selector.addClass("si4CodeMirror");

    if (this.inputType == "hidden")
        this.selector.hide();

    if (this.halfWidth) this.input.selector.addClass("half");

    this.inputs.push(this.input);

    // Implementation
    if (!this.name) this.name = si4.widget._nextInputId();

    if (this.inputType != "textarea" && this.inputType != "codemirror")
        this.input.selector.attr("type", this.inputType);

    if (this.name)
        this.input.selector.attr("name", this.name);

    if (this.accept)
        this.input.selector.attr("accept", this.accept);

    if (this.type == "button" && !this.value)
        this.value = this.name;

    if (this.type == "flat") {
        this.input.selector.addClass("flat");
        this.selector.addClass("inline");
    }

    if (this.type == "select") {
        if (this.addEmptyOption)
            this.input.selector.append($('<option>', {value: "", text: ""}));
        for (var i in this.values)
            this.input.selector.append($('<option>', {value: i, text: this.values[i]}));
        this.input.selector.change(function(){
            if (_p.showModified) _p.calcModified();
        });

    }

    if (this.readOnly)
        this.input.selector.attr("readonly", true);

    if (this.disabled)
        this.input.selector.attr("disabled", true);

    if (this.focus)
        this.input.selector.focus();

    if (this.lookup) {
        this.lookupButton = new si4.widget.si4Element({ parent:this.selector, tagName:"div" });
        this.lookupButton.selector.addClass("inputButton lookupButton");
        this.lookupButton.lookupImg = new si4.widget.si4Element({ parent:this.lookupButton.selector,
            tagName:"img", attr: { src: "/img/icon/lookup.png" } });
        this.lookupButton.selector.click(function(e){
            _p.lookup(_p);
        });

        /*
        var placeHolder = "";
        if (_p.lookup.resolve && _p.lookup.resolve.emptyValue) placeHolder = _p.lookup.resolve.emptyValue;
        this.input.selector.addClass("lookupKey");
        this.lookupInput = new si4.widget.si4Element({ parent:this.selector, tagName:this.inputTagName,
            attr: { type: "text", readOnly: true, tabindex: "-1", name: this.name+"_lookup", placeholder: placeHolder } });
        this.lookupInput.selector.addClass("si4Input lookupValue");

        this.editButton = new si4.widget.si4Element({ parent:this.selector, tagName:"div" });
        this.editButton.selector.addClass("inputButton editButton");
        this.editButton.lookupImg = new si4.widget.si4Element({ parent:this.editButton.selector,
            tagName:"img", attr: { src: "/img/icon/edit.png" } });
        this.editButton.displayNone();


        // ... Lookup Resolve
        this.lookupResolve = function(){
            var resolveArgs = si4.mergeObjects({ aSync: true }, _p.lookup.resolve);
            var name = _p.inputArray ? _p.inputArray.name : _p.name;
            var value = _p.getValue();

            if (value && parseInt(value)) {
                var targetFieldName = name;
                if (_p.lookup.fieldMap && _p.lookup.fieldMap[name]) targetFieldName = _p.lookup.fieldMap[name];
                resolveArgs[targetFieldName] = value;

                si4.callMethod(resolveArgs, function(resp){
                    _p.lookupInput.selector.val(resp.resolveValue);
                });
            } else {
                _p.lookupInput.selector.val('');
            }
        };


        // ... Do Lookup
        this.lookupLookup = function() {
            var lookupArgs = si4.mergeObjects(_p.lookup.lookup);
            lookupArgs[_p.name] = _p.getValue();
            if (_p.form) {
                var formData = _p.form.getValue();
                if (_p.lookup.fieldMap){
                    for (var origKey in _p.lookup.fieldMap) {
                        var renamedKey = _p.lookup.fieldMap[origKey]
                        if (_p.inputArray && renamedKey == _p.inputArray.name)
                            renamedKey = _p.name;
                        lookupArgs[renamedKey] = formData[origKey];
                    }
                }
                lookupArgs.formData = formData;
            }

            lookupArgs.selectCallback = function(cbArgs) {
                var targetFieldName = _p.name;
                var name = _p.inputArray ? _p.inputArray.name : _p.name;
                if (_p.lookup.fieldMap && _p.lookup.fieldMap[name]) targetFieldName = _p.lookup.fieldMap[name];
                var row = cbArgs.row.getValue();
                _p.setValue(row[targetFieldName]);
            };

            si4.loadModule(lookupArgs);
        };


        // ... Lookup Edit
        this.lookupEdit = function() {
            var targetFieldName = _p.name;
            var name = _p.inputArray ? _p.inputArray.name : _p.name;
            if (_p.lookup.fieldMap && _p.lookup.fieldMap[name]) targetFieldName = _p.lookup.fieldMap[name];
            var editArgs = si4.mergeObjects(_p.lookup.edit);
            editArgs[targetFieldName] = _p.getValue();

            si4.loadModule(editArgs);
        };



        this.editButton.selector.click(function(e){
            _p.lookupEdit();
        });

        this.lookupButton = new si4.widget.si4Element({ parent:this.selector, tagName:"div" });
        this.lookupButton.selector.addClass("inputButton lookupButton");
        this.lookupButton.lookupImg = new si4.widget.si4Element({ parent:this.lookupButton.selector,
            tagName:"img", attr: { src: "/img/icon/lookup.png" } });
        this.lookupButton.selector.click(function(e){
            _p.lookupLookup();
        });

        this.input.selector.blur(function(e){
            _p.lookupResolve();
        });

        this.inputs.push(this.lookupInput);
        */
    }

    this.setPlaceholder = function(newPlaceholder){
        if (!newPlaceholder) {
            _p.placeholder = "";
            _p.input.selector.removeAttr("placeholder");
        } else {
            _p.placeholder = newPlaceholder;
            _p.input.selector.attr("placeholder", _p.placeholder);
        }
    };

    this.setPlaceholder2 = function(newPlaceholder2){
        if (!newPlaceholder2) {
            _p.placeholder2 = "";
            _p.input2.selector.removeAttr("placeholder");
        } else {
            _p.placeholder2 = newPlaceholder2;
            _p.input2.selector.attr("placeholder", _p.placeholder2);
        }
    };


    this.getValue = function() {
        var val = _p.input.selector.val();
        if (_p.type == "checkbox")
            val = _p.input.selector.prop("checked");
        else if (_p.type == "flat")
            val = _p.input.selector.html();
        else if (_p.type == "file")
            val = _p.input.selector[0].files[0];
        else if (_p.type == "codemirror") val = _p.codemirror.getValue();

        if (_p.withCode) {
            return { codeId: _p.getCodeId(), value: val };
        } else if (_p.secondInput) {
            var result = { value: val };
            result[this.secondInputName] = _p.getSecondValue();
            return result;
        } else {
            return val;
        }
    };

    this.setValue = function(value){
        if (_p.withCode && value.codeId) {
            _p.setCodeId(value.codeId);
            value = value.value;
        } else if (_p.secondInput) {
            _p.setSecondValue(value[_p.secondInputName]);
            value = value.value;
        }

        if (_p.type == "checkbox") {
            value = value ? true : false;
            _p.input.selector.prop("checked", value);
        } else if (_p.type == "codemirror") {
            _p.codemirror.setValue(value);
            _p.codemirror.refresh();
        } else if (_p.type == "flat") {
            _p.input.selector.html(value);
        } else if (_p.type == "file") {
            if (!value) _p.input.selector.val("");
        } else {
            _p.input.selector.val(value);
        }
        _p.origValue = value;
        _p._onChange();
        //if (_p.lookup) _p.lookupResolve();
    };

    this.clear = function() {
        if (_p.type == "checkbox")
            _p.input.selector.prop('checked', false);
        else if (_p.type == "flat")
            _p.input.selector.html("");
        else
            _p.input.selector.val('');

        _p.origValue = '';

        if (_p.type == "codemirror") _p.codemirror.refresh();
    };

    this.getCodeId = function(){
        if (!_p.withCode || !_p.codeSelect) return 0;
        return _p.codeSelect.selector.val();
    };

    this.setCodeId = function(codeId){
        if (!_p.withCode || !_p.codeSelect) return;
        _p.codeSelect.selector.val(codeId);
    };

    this.getSecondValue = function(){
        if (!_p.secondInput) return "";
        return _p.input2.selector.val();
    };

    this.setSecondValue = function(value2){
        if (!_p.secondInput) return;
        _p.input2.selector.val(value2);
    };

    this.calcModified = function(){
        var modified;

        if (_p.withCode)
            modified = _p.getValue().value != _p.origValue;
        else
            modified = _p.getValue() != _p.origValue;

        if (_p.modified == modified) return;

        _p.trigger('onModified', {modified:modified, input: _p});

        _p.modified = modified;
        if (_p.modified) {
            _p.selector.addClass("modified");
        } else {
            _p.selector.removeClass("modified");
        }
    };

    this.isButton = function(){
        return _p.type == "button" || _p.type == "submit";
    };

    if (this.isButton()) {
        if (this.type == "submit" && !this.gradient) this.gradient = si4.defaults.submitGrad
        if (this.type == "button" && !this.gradient) this.gradient = si4.defaults.buttonGrad;
        this.selector.css('display', 'inline-table');
    }

    this._onChange = function() {
        if (_p.lookup) {
            /*
            var value = _p.getValue();
            if (jQuery.isNumeric(value)) value = parseInt(value);
            if (value)
                _p.editButton.display();
            else
                _p.editButton.displayNone();
            */
        }
    };

    // Internal events
    this._onKeyDown = function(e) {
        e.si4Input = _p;

        if (_p.autoCompleteTimeout)
            clearTimeout(_p.autoCompleteTimeout);

        _p.trigger('onKeyDown', e);
    };
    this._onKeyPressed = function(e) {
        e.si4Input = _p;
        if (e.which == 13) _p.trigger('onEnterPressed', e);
        _p.trigger('onKeyPressed', e);
    };
    this._onKeyUp = function(e) {
        // We don't want the Arrow keys to trigger auto complete
        if (e.which >= 37 && e.which <= 40) return;
        // Same with escape key or enter key
        if (e.which == 27 || e.which == 13) return;

        e.si4Input = _p;
        if (_p.showModified) _p.calcModified();
        _p._onChange();
        _p.trigger('onKeyUp', e);

        if (_p.autoComplete) {
            if (_p.autoCompleteTimeout) clearTimeout(_p.autoCompleteTimeout);
            _p.autoCompleteTimeout = setTimeout(function() {
                si4.callMethod(si4.mergeObjects(_p.autoComplete, {typed: _p.getValue()}), function(cbArgs) {

                    if (_p.lastAutoComplete) _p.lastAutoComplete.hide();

                    if (!cbArgs || !cbArgs.length) {
                        _p.lastAutoComplete = null;
                        return;
                    }

                    var si4AutoComplete = new si4.widget.si4AutoComplete({ lines: cbArgs, typed:_p.getValue(), inputSelector:_p.input.selector });
                    var position = _p.input.getAbsolutePosition();
                    position.top += 25;
                    si4AutoComplete.moveToPoint(position);
                    si4AutoComplete.show();

                    _p.lastAutoComplete = si4AutoComplete;

                    //si4.dump(cbArgs);
                });
            }, si4.defaults.autoCompleteDelay);
        }
    };
    this._onFocus = function(e) {
    };

    this._onBlur = function(e) {
        if (_p.lastAutoComplete) _p.lastAutoComplete.hide();
    };

    this._onPaste = function(e) {
        e.si4Input = _p;
        _p.trigger('onPaste', e);
    };

    this.input.selector.keydown(_p._onKeyDown);
    this.input.selector.keypress(_p._onKeyPressed);
    this.input.selector.keyup(_p._onKeyUp);
    this.input.selector.focus(_p._onFocus);
    this.input.selector.blur(_p._onBlur);
    this.input.selector.on("paste", _p._onPaste);

    if (!this.isButton() && this.caption === null)
        this.caption = si4.captionize(this.name);


    this.captionDiv = new si4.widget.si4Element({ parent:this.selector, insertAtTop:true, tagName:"div" });
    this.captionDiv.selector.addClass("si4InputCaption");
    if (_p.captionWidth) this.captionDiv.selector.css("width", _p.captionWidth);
    if (this.caption && !this.withCode)
        this.captionDiv.selector.html(this.caption);

    if (this.withCode) {
        this.codeSelect = new si4.widget.si4Element({parent:this.captionDiv.selector, tagName:"select", tagClass:"codeSelect"});
        for (var idx in this.withCode) {
            var optionLabel = this.caption ? (this.caption+" - "+this.withCode[idx]) : this.withCode[idx];
            //console.log("optionLabel", optionLabel);
            this.codeSelect.selector.append('<option value="'+idx+'">'+optionLabel+'</option>');
        }
    }

    if (!this.withCode && (typeof(this.caption) != "string" || !this.caption))
        this.captionDiv.displayNone();

    if (this.placeholder) this.setPlaceholder(this.placeholder);
    if (this.secondInput && this.placeholder2) this.setPlaceholder2(this.placeholder2);
    if (this.gradient) this.input.setGradient(this.gradient);

    if (this.type == "codemirror") {

        this.codeMirrorCompleteAfter = function(cm, pred) {
            var cur = cm.getCursor();
            if (!pred || pred()) setTimeout(function() {
                if (!cm.state.completionActive)
                    cm.showHint({completeSingle: false});
            }, 100);
            return CodeMirror.Pass;
        };

        this.codeMirrorCompleteIfAfterLt = function(cm) {
            return _p.codeMirrorCompleteAfter(cm, function() {
                var cur = cm.getCursor();
                return cm.getRange(CodeMirror.Pos(cur.line, cur.ch - 1), cur) == "<";
            });
        };

        this.codeMirrorCompleteIfInTag = function(cm) {
            return _p.codeMirrorCompleteAfter(cm, function() {
                var tok = cm.getTokenAt(cm.getCursor());
                if (tok.type == "string" && (!/['"]/.test(tok.string.charAt(tok.string.length - 1)) || tok.string.length == 1)) return false;
                var inner = CodeMirror.innerMode(cm.getMode(), tok.state).state;
                return inner.tagName;
            });
        };

        this.codemirror = CodeMirror.fromTextArea(this.input.selector[0], {
            lineNumbers: true,
            mode: "xml",
            //mode: "text/html",
            lineWrapping: true,
            matchBrackets: true,
            indentUnit: 3,
            tabSize: 3,
            extraKeys: {
                "'<'": this.codeMirrorCompleteAfter,
                "'/'": this.codeMirrorCompleteIfAfterLt,
                "' '": this.codeMirrorCompleteIfInTag,
                "'='": this.codeMirrorCompleteIfInTag,
                "Ctrl-Space": "autocomplete",
                "F11": function(cm) { cm.setOption("fullScreen", !cm.getOption("fullScreen")); },
                "Esc": function(cm) { if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false); },
            },
            hintOptions: { schemaInfo: codemirrorMets.autoCompleteConfig }
        });
    }

    this.setValue(this.value);
};

// Id Generator
si4.widget._lastInputId = 0;
si4.widget._nextInputId = function(){
    si4.widget._lastInputId += 1;
    return "input"+si4.widget._lastInputId;
};
