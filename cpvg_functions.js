jQuery.cpvg_data = {
    field_ids : [] ,
    field_data : [],
    type_options : jQuery.parseJSON(htmlspecialchars_decode(server_data.type_options)),
    siteurl: server_data.wpurl
};



function send_data(send_data_action){
	jQuery.cpvg_data.field_data = [];

	jQuery.each(jQuery.cpvg_data.field_ids,function(index, id_number) {
			jQuery.cpvg_data.field_data[jQuery.cpvg_data.field_data.length] = { 'name':jQuery('#cpvg-fieldlist-name-'+id_number).text(),
																				'label':jQuery('#cpvg-fieldlist-label-'+id_number).val(),
																				'type':jQuery('#cpvg-fieldlist-type-'+id_number).val(),
																				'options1':jQuery('#cpvg-fieldlist-output-1-'+id_number).val(),
																				'options2':jQuery('#cpvg-fieldlist-output-2-'+id_number).val(),
																				'options3':jQuery('#cpvg-fieldlist-output-3-'+id_number).val(),
																				'options4':jQuery('#cpvg-fieldlist-output-4-'+id_number).val()}; });

	jQuery.post(jQuery.cpvg_data.siteurl+"/wp-admin/admin-ajax.php",
				{ action:send_data_action,
				 'cookie': encodeURIComponent(document.cookie),
				 'template' : jQuery('#cpvg-template-select').val(),
				 'fields': jQuery.cpvg_data.field_data,
				 'post-type':jQuery('#cpvg-posttype-select').val() },
			   function(response){
				   if(send_data_action === "save_layout" || send_data_action === "delete_layout"){
					   jQuery('#action-message').html(response).show(1000).delay(2000).hide(1000);
				   }

				   if(send_data_action === "generate_preview"){
					   jQuery('#cpvg-posttype-preview-content').html(response);
				   }
			   }
	);
}

function get_post_type_data(){
	jQuery.post(jQuery.cpvg_data.siteurl+"/wp-admin/admin-ajax.php",
				{ action:'get_post_type_data',
				 'cookie': encodeURIComponent(document.cookie),
				 'post-type':jQuery('#cpvg-posttype-select').val() },
				 function(response){
					if(response != 0){

						jQuery.cpvg_data.field_data = jQuery.parseJSON(response.slice(0,response.length-1));

						jQuery('#cpvg-template-select').val(jQuery.cpvg_data.field_data['template_file']);
						jQuery.cpvg_data.field_data = jQuery.cpvg_data.field_data['fields'];
						jQuery.each(jQuery.cpvg_data.field_data,function(index, val) {
							generate_option(jQuery('#cpvg-fieldlist'),{'field_id':val.name,
																	  'field_label':val.label,
																	  'field_type':val.type,
																	  'field_options': [val.options1,val.options2,val.options3,val.options4]
																	  });
						});
					}
			    }
	);
}

function generate_type_options(list_item_id,selected_value){
	var type_options = jQuery('<select>').attr({'class':'cpvg-fieldlist-type','id':'cpvg-fieldlist-type-'+list_item_id})
										 .css('min-width','120px');

	var options_list = {};
	for (var object_type in jQuery.cpvg_data.type_options){
		type_options.append(jQuery('<option>').attr('value',object_type).append(jQuery.cpvg_data.type_options[object_type].label));

		if(jQuery.cpvg_data.type_options[object_type].options != undefined){
			options_list[object_type] = jQuery.cpvg_data.type_options[object_type].options;
		}
	}

	type_options.change(function() {
		jQuery('#cpvg-fieldlist-output-label-'+list_item_id).hide();

		for (var i=1;i<5;i++){
			jQuery('#cpvg-fieldlist-output-'+i+'-'+list_item_id).hide().empty();
		}

		if (jQuery(this).val() in options_list){
			jQuery('#cpvg-fieldlist-output-label-'+list_item_id).show();

			for (var i=1;i<5;i++){
				if(options_list[jQuery(this).val()][i-1] != undefined){
					var cpvg_fieldlist_option = jQuery('#cpvg-fieldlist-output-'+i+'-'+list_item_id);

					for (var option_id in options_list[jQuery(this).val()][i-1]){
						cpvg_fieldlist_option.append(jQuery('<option>').attr('value',option_id).append(options_list[jQuery(this).val()][i-1][option_id]));
					}

					cpvg_fieldlist_option.show();
				}
			}
		}
	});

	if(selected_value != 'field'){
		type_options.val(selected_value);
	}

	return type_options;
}


function generate_option(append_object,data){
	var list_item_id = generate_random_id(jQuery.cpvg_data.field_ids);
	jQuery.cpvg_data.field_ids[jQuery.cpvg_data.field_ids.length] = list_item_id;

	if(data.field_type === 'content-editor'){ data.field_id = 'Content Editor'; }

	var list_item_content = jQuery("<span>").append(jQuery("<span>").append(data.field_id)
																	.attr({'class':'cpvg-fieldlist-name','id':'cpvg-fieldlist-name-'+list_item_id }))
											.append(jQuery('<span>').append(jQuery('<a>').attr('href','#')
																	.text('Remove')
																	.click(function(){
																		jQuery('#cpvg-fieldlist-item-'+list_item_id).remove();
																		jQuery.cpvg_data.field_ids = jQuery.grep(jQuery.cpvg_data.field_ids, function(value) { return value != list_item_id; });
																	})))
											.append('<br/>');

	list_item_content.append(jQuery('<span>').attr('class','cpvg-input-span')
											 .append('<label for="cpvg-fieldlist-label-'+list_item_id+'">Label:</label>')
											 .append(jQuery('<input>').attr({'type':'text','class':'cpvg-fieldlist-label','id':'cpvg-fieldlist-label-'+list_item_id })
																	  .val(data.field_label)));

	if(data.field_type === 'content-editor'){
		list_item_content.append(jQuery('<span>').attr('class','cpvg-input-span ')
												 .append(jQuery('<input>').attr({'class':'cpvg-fieldlist-type','id':'cpvg-fieldlist-type-'+list_item_id,
																			 'type':'hidden','value':'content-editor'})));
	}else{
		var list_item_options = jQuery('<select>').attr('class','cpvg-fieldlist-options')
												  .css('min-width','120px');

		list_item_content.append(jQuery('<span>').attr('class','cpvg-input-span')
												 .append('<label for="cpvg-fieldlist-type-'+list_item_id+'">Type:</label>')
												 .append(generate_type_options(list_item_id,data.field_type)));

		list_item_content.append(jQuery('<span>').attr('class','cpvg-input-span')
												 .append('<br />')
												 .append('<label id="cpvg-fieldlist-output-label-'+list_item_id+'">Output:</label>')
												 .append(list_item_options.clone().attr('id','cpvg-fieldlist-output-1-'+list_item_id))
												 .append(list_item_options.clone().attr('id','cpvg-fieldlist-output-2-'+list_item_id))
												 .append(list_item_options.clone().attr('id','cpvg-fieldlist-output-3-'+list_item_id))
												 .append(list_item_options.clone().attr('id','cpvg-fieldlist-output-4-'+list_item_id)));
	}

	jQuery("<li></li>").attr({ 'class':'cpvg-fieldlist-item','id':'cpvg-fieldlist-item-'+list_item_id })
					   .append(list_item_content)
					   .appendTo(append_object);

	if(data.field_type=="field"){
		jQuery('#cpvg-fieldlist-type-'+list_item_id).val('cpvg_text');
	}

    jQuery('#cpvg-fieldlist-type-'+list_item_id).trigger('change');

    if(data.field_options != undefined){
		for (var i=1;i<5;i++){
			 jQuery('#cpvg-fieldlist-output-'+i+'-'+list_item_id).val(data.field_options[i-1]);
		}
	}
}

function generate_random_id(existing_ids){
	var random_id = Math.floor(Math.random()*501);

	while(jQuery.inArray(random_id, existing_ids) > -1){
		random_id = Math.floor(Math.random()*101);
	}

	return random_id;
}

jQuery(document).ready(function(){
	jQuery('#cpvg-posttype-select').change(function(){
		jQuery('.cpvg-posttype-fieldgroup').hide();
	    jQuery('#'+jQuery(this).val() +'-fieldgroup').show();
	    jQuery('#cpvg-fieldlist').html('');
		jQuery('#cpvg-posttype-preview-content').html("");
	    jQuery.cpvg_data.field_ids= [];
	    get_post_type_data();
	});

	jQuery('#cpvg-template-select').change(function(){  });

	jQuery('#cpvg-preview').click(function(){ send_data("generate_preview"); });
	jQuery('#cpvg-save-layout').click(function(){ send_data("save_layout"); });
	jQuery('#cpvg-delete-layout').click(function(){
		send_data("delete_layout");
		jQuery('#cpvg-posttype-select').val("");
		jQuery('#cpvg-posttype-preview-content').html("");
		jQuery('#cpvg-posttype-select').trigger('change');
	});

	jQuery(".cpvg-field-draggable").draggable({
		appendTo: "body",
		helper: "clone"
	});

	jQuery("#cpvg-fieldlist").droppable({
		activeClass: "ui-state-default",
		hoverClass: "ui-state-hover",
		accept: ":not(.ui-sortable-helper)",
		drop: function( event, ui ){
			var field_type = 'field';

			if(ui.draggable.hasClass('cpvg-post-type-editor')){	field_type = 'content-editor'; }

			generate_option(this,{'field_id':ui.draggable.attr('id'),'field_label':ui.draggable.text(),'field_type':field_type});
		}
	}).sortable({
		items: "li:not(.placeholder)",
		sort: function(){
			jQuery(this).removeClass( "ui-state-default" );
		},
		update : function(){
			jQuery.cpvg_data.field_ids = jQuery(this).sortable('toArray').map(function(val,index) {
				return val.replace(/cpvg-fieldlist-item-/ig, '');
			});
		}
	});

	jQuery('#cpvg-posttype-select').trigger('change');
});

//FUNCTION FROM PHP.JS,
//Works better than convertEntities() function
function htmlspecialchars_decode (string, quote_style) {
    var optTemp = 0,
        i = 0,
        noquotes = false;
    if (typeof quote_style === 'undefined') {
        quote_style = 2;
    }
    string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE': 1,
        'ENT_HTML_QUOTE_DOUBLE': 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE': 4
    };
    if (quote_style === 0) {
        noquotes = true;
    }
    if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
        quote_style = [].concat(quote_style);
        for (i = 0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;
            } else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }
        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
        // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
    }
    if (!noquotes) {
        string = string.replace(/&quot;/g, '"');
    }
    // Put this in last place to avoid escape being double-decoded
    string = string.replace(/&amp;/g, '&');

    return string;
}
