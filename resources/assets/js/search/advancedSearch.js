$(document).ready(function() {
    $(".advancedSearchFields").each(function() {
        new AdvancedSearchFields(this);
    });
});

function AdvancedSearchFields(mainEl) {
    var _p = this;

    /*
    this.defaultFieldNameOpts = [
        { value: "title", text: "Title" },
        { value: "creator", text: "Creator" },
    ];
    this.defaultOperatorOpts = [
        { value: "and", text: "And" },
        { value: "or", text: "Or" },
    ];
    */
    this.defaultFieldNameOpts = jsData["advancedSearch"]["fieldNames"];
    this.defaultOperatorOpts = jsData["advancedSearch"]["operators"];


    this.mainEl = mainEl;
    this.inputs = [];

    this.init = function() {
        /*
         <div class="flexRow">
             <select class="fieldName">
                 <option value="title">Title</option>
                 <option value="creator">Creator</option>
             </select>
             <input class="fieldValue" type="text" name="primary"
                 value="{{ $primary or "" }}" placeholder="..." autocomplete="off" />
             <select class="operator">
                 <option value="and">And</option>
                 <option value="or">Or</option>
             </select>
         </div>

         <div class="textAlignRight">
             <input class="submit" type="submit" value="Search">
         </div>
        */

        // Create fields element wrap
        _p.fieldsEl = document.createElement("div");
        _p.mainEl.appendChild(_p.fieldsEl);

        // Create controls element wrap
        _p.controlsEl = document.createElement("div");
        _p.mainEl.appendChild(_p.controlsEl);

        //_p.addInput();
        _p.setValueFromUrl();

        _p.submitWrap = document.createElement("div");
        _p.submitWrap.className = "textAlignRight";

        _p.addInputButton = document.createElement("input");
        _p.addInputButton.className = "addInputButton";
        _p.addInputButton.type = "button";
        _p.addInputButton.value = "+";
        $(_p.addInputButton).click(_p.addInput);

        _p.submitButton = document.createElement("input");
        _p.submitButton.className = "submit";
        _p.submitButton.type = "submit";
        _p.submitButton.value = "Search";

        _p.submitWrap.appendChild(_p.addInputButton);
        _p.submitWrap.appendChild(_p.submitButton);
        _p.controlsEl.appendChild(_p.submitWrap);
    };

    this.addInput = function() {
        var input = {};

        // row wrap
        input.wrap = document.createElement("div");
        input.wrap.className = "flexRow";

        // fieldName select
        input.fieldName = document.createElement("select");
        input.fieldName.className = "fieldName";
        for (var i in _p.defaultFieldNameOpts) {
            var option = document.createElement("option");
            option.value = _p.defaultFieldNameOpts[i].value;
            option.innerHTML = _p.defaultFieldNameOpts[i].text;
            input.fieldName.appendChild(option);
        }
        input.wrap.appendChild(input.fieldName);

        // fieldValue input
        input.fieldValue = document.createElement("input");
        input.fieldValue.className = "fieldValue";
        input.fieldValue.type = "text";
        input.wrap.appendChild(input.fieldValue);

        // operator select
        input.operator = document.createElement("select");
        input.operator.className = "operator";
        for (var i in _p.defaultOperatorOpts) {
            var option = document.createElement("option");
            option.value = _p.defaultOperatorOpts[i].value;
            option.innerHTML = _p.defaultOperatorOpts[i].text;
            input.operator.appendChild(option);
        }
        input.wrap.appendChild(input.operator);

        // Set input name function
        input.setName = function() {
            input.fieldValue.name = $(input.operator).val()+"-"+$(input.fieldName).val();
        };
        $(input.fieldName).change(input.setName);
        $(input.operator).change(input.setName);
        input.setName();

        // Append wrap to el
        _p.inputs.push(input);
        _p.fieldsEl.appendChild(input.wrap);

        return input;
    };

    this.setValueFromUrl = function() {
        var params = location.search.replace("?", "").split("&");

        var validOperators = _p.defaultOperatorOpts.map(function(x) { return x.value; });

        for (var i in params) {
            var kv = params[i].split("=");
            if (kv.length != 2) continue;
            var operName = kv[0].split("-");

            var operator = operName[0];
            if (validOperators.indexOf(operator) === -1) continue;

            var name = operName[1];
            var value = decodeURIComponent(kv[1]);

            //if (!name || !operator || !value) continue;

            var input = _p.addInput();
            $(input.operator).val(operator);
            $(input.fieldName).val(name);
            $(input.fieldValue).val(value);
            input.setName();
        }

        // If no inputs created, at least create one
        if (!_p.inputs.length) {
            _p.addInput();
        }
    };

    this.setValue = function(value) {

    };

    this.init();
}


// TODO: autocomplete creator/title/...
/*
var termTemplate = "<span class=\"searchAutocompleteTerm\">%s</span>";
$("#searchInput").autocomplete({
    source: "/ajax/searchSuggest",
    open: function(e,ui) {
        var acData = $(this).data('ui-autocomplete');
        var styledTerm = termTemplate.replace('%s', acData.term);

        console.log(acData.menu);
        acData.menu.element.find('.ui-menu-item-wrapper').each(function() {
            var me = $(this);
            console.log(me.text());
            me.html(me.text().replace(acData.term, styledTerm));
        });
    }
});
*/