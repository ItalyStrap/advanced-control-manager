function wlcms_menus() {
	// Set initial open/close
	jQuery('#menus > li').each(function() {
  		var submenu = jQuery('ul',this);
  		
  		if(jQuery(submenu).length>0) {
			jQuery(submenu).slideDown(500);
			jQuery("em",this).text("↓");
		}
  			
  		if(jQuery('input:eq(0)',this).is(':checked')) {
  			if(jQuery(submenu).length>0) {
  				jQuery(submenu).slideDown(500);
  				jQuery("em",this).text("↑");
  			}
  		} else {
  			if(jQuery(submenu).length>0) {
  				jQuery(submenu).hide();
  				jQuery("em",this).text("↓");
  			}
  		}
  	});
  	
  	// On click
	jQuery('#menus span').click(function() {
  		var parent = jQuery(this).parent().parent();
  		var submenu = jQuery('ul',parent);
  		jQuery(submenu).slideToggle(500,function() {
  			var parent = jQuery(this).parent();
			jQuery("em",parent).text(jQuery(submenu).is(':visible') ? "↑" : "↓");
  		});
  	});
  	
	// On click
	jQuery('#menus input').click(function() {
  		var parent = jQuery(this).parent().parent();
  		var submenu = jQuery('ul',parent);
  		if(jQuery(this).is(':checked')) {
  			if(jQuery(submenu).length>0) {
  				jQuery(submenu).slideDown(500);
  				jQuery('li',submenu).each(function() {
  					jQuery('input',this).attr('checked','checked');
  				});
  			}
  		} else {
  			if(jQuery(submenu).length>0) {
  				jQuery(submenu).slideUp(500);
  				jQuery('li',submenu).each(function() {
  					jQuery('input',this).removeAttr('checked');
  				});
  			}
  		}
  	});
}

jQuery(document).ready(function($){
    wlcms_menus();
  	
    	
    $('#wlcms_o_edit_role').parent().css('borderTop','0');
    $('#roles_capabilities').parent().css('paddingLeft','30px').css('paddingRight','30px').css('paddingBottom','10px');
    
    jQuery('.edit_role_name').hide();
	jQuery('#wlcms_o_head_cap').hide();
	/**/
	jQuery('.wlcms_o_modify_sub_menu').hide();

/**/
    	/*jQuery('#footer-left').remove();*/
    
		jQuery('.wlcms_options').slideUp();
		
		jQuery('.video-h').hover(function() {
			jQuery(this).addClass('pretty-hover');
		}, function() {
			jQuery(this).removeClass('pretty-hover');
		});

		var showHideWelcome;
		var showHideAppearance;
		var formField;

                //Showhide RSS
                showHideRSS = jQuery('.wlcms_opts form #form-show-rss input:radio:checked').val();
		if(showHideRSS == 0) {
			jQuery('#vRSSSettings').hide();
		}

                jQuery('.wlcms_opts form #form-show-rss input:radio').click(function() {
			showHideRSS = jQuery('.wlcms_opts form #form-show-rss input:radio:checked').val();
			if(showHideRSS == 0) {
				jQuery('#vRSSSettings').hide();
			} else {
				jQuery('#vRSSSettings').show();
			}
		});

		// Showhide Welcome
		showHideWelcome = jQuery('.wlcms_opts form #form-show-welcome input:radio:checked').val();
		if(showHideWelcome == 0) {
			jQuery('#vWelcomePanelSettings').hide();
		}
		
		jQuery('.wlcms_opts form #form-show-welcome input:radio').click(function() {
			showHideWelcome = jQuery('.wlcms_opts form #form-show-welcome input:radio:checked').val();
			if(showHideWelcome == 0) {
				jQuery('#vWelcomePanelSettings').hide();
			} else {
				jQuery('#vWelcomePanelSettings').show();
			}
		});
		
		// Showhide Appearance Menu
		showHideAppearance = jQuery('.wlcms_opts form #form-show-template input:radio:checked').val();
		if(showHideAppearance == 0) {
			jQuery('#vTheAppearanceMenu').hide();
		}
		
		jQuery('.wlcms_opts form #form-show-template input:radio').click(function() {
			showHideWelcome = jQuery('.wlcms_opts form #form-show-template input:radio:checked').val();
			if(showHideWelcome == 0) {
				jQuery('#vTheAppearanceMenu').hide();
			} else {
				jQuery('#vTheAppearanceMenu').show();
			}
		});
		
  
		jQuery('.wlcms_section h3').click(function(){		
			if(jQuery(this).parent().next('.wlcms_options').css('display')=='none')
				{	jQuery(this).removeClass('inactive');
					jQuery(this).addClass('active');
					jQuery(this).children('img').removeClass('inactive');
					jQuery(this).children('img').addClass('active');
					
				}
			else
				{	jQuery(this).removeClass('active');
					jQuery(this).addClass('inactive');		
					jQuery(this).children('img').removeClass('active');			
					jQuery(this).children('img').addClass('inactive');
				}
				
			jQuery(this).parent().next('.wlcms_options').slideToggle('slow');	
		});
		
		jQuery('#radioWebsite').click(function() {
			jQuery('input[name=wlcms_o_hide_posts]').attr('checked', true);
			jQuery('input[name=wlcms_o_hide_media]').attr('checked', false);
			jQuery('input[name=wlcms_o_hide_links]').attr('checked', true);
			jQuery('input[name=wlcms_o_hide_pages]').attr('checked', false);			
			jQuery('input[name=wlcms_o_hide_comments]').attr('checked', true);
			jQuery('input[name=wlcms_o_hide_users]').attr('checked', true);			
			jQuery('input[name=wlcms_o_hide_tools]').attr('checked', true);
			jQuery('input[name=wlcms_o_hide_separator2]').attr('checked', true);		
			jQuery('input[name=wlcms_o_show_appearance]').attr('checked', false);		
			jQuery('input[name=wlcms_o_show_widgets]').attr('checked', false);					
		});

		jQuery('#radioBlog').click(function() {
			jQuery('input[name=wlcms_o_hide_posts]').attr('checked', false);
			jQuery('input[name=wlcms_o_hide_media]').attr('checked', false);
			jQuery('input[name=wlcms_o_hide_links]').attr('checked', true);
			jQuery('input[name=wlcms_o_hide_pages]').attr('checked', false);			
			jQuery('input[name=wlcms_o_hide_comments]').attr('checked', false);
			jQuery('input[name=wlcms_o_hide_users]').attr('checked', true);			
			jQuery('input[name=wlcms_o_hide_tools]').attr('checked', true);
			jQuery('input[name=wlcms_o_hide_separator2]').attr('checked', true);			
			jQuery('input[name=wlcms_o_show_appearance]').attr('checked', false);		
			jQuery('input[name=wlcms_o_show_widgets]').attr('checked', false);					
		});

		jQuery('.wlcms_input_local_video').click(function() {
			if(jQuery('label',this).hasClass('wlcms_o_parent_label_active')) {
				jQuery('.wlcms_o_parent_label_active',this).removeClass('wlcms_o_parent_label_active');
			} else {
				jQuery('.wlcms_o_parent_label',this).addClass('wlcms_o_parent_label_active');
			}
			
			jQuery(this).next('.wlcms_o_modify_sub_menu').slideToggle('slow');	
		});

		jQuery('#radioCustom').click(function() {
			if (jQuery('#wlcms_o_hide_posts').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_hide_posts]').attr('checked', true); } else { jQuery('input[name=wlcms_o_hide_posts]').attr('checked', false); }
			if (jQuery('#wlcms_o_hide_media').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_hide_media]').attr('checked', true); } else { jQuery('input[name=wlcms_o_hide_media]').attr('checked', false); }
			if (jQuery('#wlcms_o_hide_links').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_hide_links]').attr('checked', true); } else { jQuery('input[name=wlcms_o_hide_links]').attr('checked', false); }
			if (jQuery('#wlcms_o_hide_pages').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_hide_pages]').attr('checked', true); } else { jQuery('input[name=wlcms_o_hide_pages]').attr('checked', false); }
			if (jQuery('#wlcms_o_hide_comments').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_hide_comments]').attr('checked', true); } else { jQuery('input[name=wlcms_o_hide_comments]').attr('checked', false); }
			if (jQuery('#wlcms_o_hide_users').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_hide_users]').attr('checked', true); } else { jQuery('input[name=wlcms_o_hide_users]').attr('checked', false); }
			if (jQuery('#wlcms_o_hide_tools').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_hide_tools]').attr('checked', true); } else { jQuery('input[name=wlcms_o_hide_tools]').attr('checked', false); }			
			if (jQuery('#wlcms_o_hide_separator2').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_hide_separator2]').attr('checked', true); } else { jQuery('input[name=wlcms_o_hide_separator2]').attr('checked', false); }			
			if (jQuery('#wlcms_o_show_appearance').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_show_appearance]').attr('checked', true); } else { jQuery('input[name=wlcms_o_show_appearance]').attr('checked', false); }			
			if (jQuery('#wlcms_o_show_widgets').is('.wlcms_remChecked')) { jQuery('input[name=wlcms_o_show_widgets]').attr('checked', true); } else { jQuery('input[name=wlcms_o_show_widgets]').attr('checked', false); }			

		});
		
		
		// Set default value
		
		jQuery(".default-text").focus(function(srcc){
		    if (jQuery(this).val() == jQuery(this)[0].title){
		    	jQuery(this).val("");
		    }
		});

		jQuery(".default-text").blur(function(){
		    if (jQuery(this).val() == "" || isNaN(jQuery(this).val())){
		    	jQuery(this).val(jQuery(this)[0].title);
		    }
		});
/***********************************************************************************/
		jQuery(".add").click(function(){
		    if (jQuery("#wlcms_o_role_name").val() == ""){
		    	alert("must fill role name");
		    	return false;

		    }

		});

/*hide roles_capabilities*/
var roleName;


jQuery('#wlcms_o_edit_role').change(function() {
		jQuery('.edit_role_name').hide();
		jQuery('#wlcms_o_head_cap').show();
		roleName=jQuery("#wlcms_o_edit_role").val();
		if(roleName==0)
			jQuery('#wlcms_o_head_cap').hide();
		 	
		jQuery('#roles_'+roleName).show();
			
		 });

/**/

/***********************************************************************************/

		jQuery(".default-text").blur();
		
		
		// Add http with devoloper url if not exist
		
		jQuery("#wlcms_o_developer_url").blur(function() {
			  var input = jQuery(this);
			  var val = input.val();
			  if (val && !val.match(/^http([s]?):\/\/.*/)) {
			    input.val('http://' + val);
			  }
		});

		
	

    	

// Ajax function goes here


/*$.ajax({
  url: "wlcms-plugin.php",
  context: document.body,
  success: function update_function(){
alert("hai";
    $(this).addClass("done");
  }
});*/
////////////////////////////////////////////////
});
    