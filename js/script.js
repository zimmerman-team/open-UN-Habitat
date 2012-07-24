// JavaScript Document
$(document).ready(function() {
	
	//Overlay
	$(".overlay").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	//close
	$('#close').click(function(){
		$('.textblurb').fadeOut(400);
	});
	//Accordion
	$(".moredetail").click(function(){
		if($(this).hasClass('active')){
			$(this).parent().find('p.shortdescription').animate({ 
				"max-height": "100px"
			  }, 1500 );
			$(".moredetail").removeClass('active');
			var idnt = $(this).attr('id');
			$('.'+idnt).slideUp();
			
		}
		else{
			$(this).parent().find('p.shortdescription').animate({ 
				"max-height": "100%"
			  }, 1500 );
			$(".moredetail").removeClass('active');
			$(this).addClass('active');
			$(".resultdetail").slideUp();
			var idnt = $(this).attr('id');
			$('.'+idnt).slideDown();
			
		}
	});
	//Context Menu
	$('.sortby_b').jeegoocontext('contxmenu_b',{event:'click'});
	$('#contxmenu_b>li>a').click(function(){
		$('#contxmenu_b li').removeClass('active');
		$(this).parent().addClass('active');
		var order = $(this).attr('id');
		order = (order=='desc'?'-':'') + 'statistics__total_budget';
		processAjaxFilters(0, order);
	});
	$('.sortby_d').jeegoocontext('contxmenu_d',{event:'click'});
	
	$('#contxmenu_d>li>a').click(function(){
		$('#contxmenu_d li').removeClass('active');
		$(this).parent().addClass('active');
		var order = $(this).attr('id');
		order = (order=='desc'?'-':'') + 'start_actual';
		processAjaxFilters(0, order);
	});
	/*$('.sortby_det').jeegoocontext('contxmenu_det',{
		event:'click',
		 onSelect: function(e, context){
			 $('.sortby_det').trigger('click');
			 return false;
		 }
		});*/
	$('.sortby_det').click(function(){
		if($("#contxmenu_det2").hasClass('activep')){
			$("#contxmenu_det2").removeClass('activep');
			$("#contxmenu_det2").hide();
		}
		else{
			$("#contxmenu_det2").addClass('activep');
			$("#contxmenu_det2").fadeIn(200);
		}
	});
	$('.sortby_c').jeegoocontext('contxmenu_c',{event:'click'});
	/*$('.sortby').jeegoocontext('contxmenu');*/
	
	//Submit Button 
	$("#submitButton").click(function(){
		$("#detailForm").trigger('submit');
	});
	$("#cancelButton").click(function(){
		$("#contxmenu_det2").hide();
	});
	$(".sortby").click(function(){
		$("#contxmenu_det2").hide();
	});
	
	$(".filterbox .filtercontent input[name=countries]").click(function(){
		var val = $(this).val();
		if(val!='All') {
			$(".filterbox .filtercontent input[name=countries]:first").attr('checked', false);
		}
		if($(this).is(':checked')) {
			if(val=='All') {
				$(".filterbox .filtercontent input[name=countries]").attr('checked', false);
				$(".filterbox .filtercontent input[name=countries]:first").attr('checked', true);
			}
			$('#country_popup input[type=checkbox]').each(function(){
				if($(this).val()==val) $(this).attr('checked', true);
			});
		} else {
			$('#country_popup input[type=checkbox]:checked').each(function(){
				if($(this).val()==val) $(this).attr('checked', false);
			});
		}
		processAjaxFilters(0);
	});	
	
	$(".filterbox .filtercontent input[name=regions]").click(function(){
		var val = $(this).val();
		if(val!='All') {
			$(".filterbox .filtercontent input[name=regions]:first").attr('checked', false);
		}
		
		if($(this).is(':checked')) {
			if(val=='All') {
				$(".filterbox .filtercontent input[name=regions]").attr('checked', false);
				$(".filterbox .filtercontent input[name=regions]:first").attr('checked', true);
			}
			$('#region_popup input[type=checkbox]').each(function(){
				if($(this).val()==val) $(this).attr('checked', true);
			});
		} else {
			$('#region_popup input[type=checkbox]:checked').each(function(){
				if($(this).val()==val) $(this).attr('checked', false);
			});
		}
		processAjaxFilters(0);
	});	
	
	$(".filterbox .filtercontent input[name=sectors]").click(function(){
		var val = $(this).val();
		if(val!='All') {
			$(".filterbox .filtercontent input[name=sectors]:first").attr('checked', false);
		}
		
		if($(this).is(':checked')) {
			if(val=='All') {
				$(".filterbox .filtercontent input[name=sectors]").attr('checked', false);
				$(".filterbox .filtercontent input[name=sectors]:first").attr('checked', true);
			}
			$('#sector_popup input[type=checkbox]').each(function(){
				if($(this).val()==val) $(this).attr('checked', true);
			});
		} else {
			$('#sector_popup input[type=checkbox]:checked').each(function(){
				if($(this).val()==val) $(this).attr('checked', false);
			});
		}
		processAjaxFilters(0);
	});	
	
	$(".filterbox .filtercontent input[name=budgets]").click(function(){
		var val = $(this).val();
		if(val!='All') {
			$(".filterbox .filtercontent input[name=budgets]:first").attr('checked', false);
		}
		
		if($(this).is(':checked')) {
			if(val=='All') {
				$(".filterbox .filtercontent input[name=budgets]").attr('checked', false);
				$(".filterbox .filtercontent input[name=budgets]:first").attr('checked', true);
			}
			$('#budget_popup input[type=checkbox]').each(function(){
				if($(this).val()==val) $(this).attr('checked', true);
			});
		} else {
			$('#budget_popup input[type=checkbox]:checked').each(function(){
				if($(this).val()==val) $(this).attr('checked', false);
			});
		}
		processAjaxFilters(0);
	});	
	
	$('#popupsubmtBtn>a').click(function(){
		var baseUrl = top.location.pathname.toString();
		var country_fltr = '', region_fltr = '', sector_fltr = '', budget_fltr = '';
		var sep = "";
		var urlSep = "?";
		
		var classId = $(this).parent().next().attr('class');
		
		$('.' +classId+ ' input:checked').each(function(){
			var control_name = $(this).attr('name');
			var key = $(this).val();
			switch(control_name) {
				case 'countries':
					if(country_fltr.length==0) sep = '';
					country_fltr += sep + key;
					sep = "|";
					break;
				case 'regions':
					if(region_fltr.length==0) sep = '';
					region_fltr += sep + key;
					sep = "|";
					break;
				case 'sectors':
					if(sector_fltr.length==0) sep = '';
					sector_fltr += sep + key;
					sep = "|";
					break;
				case 'budgets':
					if(budget_fltr.length==0) sep = '';
					budget_fltr += sep + key;
					sep = "|";
					break;
				
				
			}
		});
		
		country_fltr = country_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
		region_fltr = region_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
		sector_fltr = sector_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
		budget_fltr = budget_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
		
		var keyword = jQuery('#s').val();
		baseUrl +=  urlSep + "s="
		if(keyword) {
			baseUrl += encodeURI(keyword);
			urlSep = "&";
		} else {
			baseUrl += "";
		}
		urlSep = "&";
		
		if(country_fltr.length>0) {
			baseUrl +=  urlSep + "countries=" + country_fltr;
			urlSep = "&";
		}
		
		if(region_fltr.length>0) {
			baseUrl +=  urlSep + "regions=" + region_fltr;
			urlSep = "&";
		}
		
		if(sector_fltr.length>0) {
			baseUrl +=  urlSep + "sectors=" + sector_fltr;
			urlSep = "&";
		}
		
		if(budget_fltr.length>0) {
			baseUrl +=  urlSep + "budgets=" + budget_fltr;
			urlSep = "&";
		}

		window.location = baseUrl;
		return false;
	});
	
	$('#clearSearchBtn').click(function(){
		$(".filterbox .filtercontent input[name=countries]").attr('checked', false);
		$(".filterbox .filtercontent input[name=countries]:first").attr('checked', true);
		$(".filterbox .filtercontent input[name=regions]").attr('checked', false);
		$(".filterbox .filtercontent input[name=regions]:first").attr('checked', true);
		$(".filterbox .filtercontent input[name=sectors]").attr('checked', false);
		$(".filterbox .filtercontent input[name=sectors]:first").attr('checked', true);
		$(".filterbox .filtercontent input[name=budgets]").attr('checked', false);
		$(".filterbox .filtercontent input[name=budgets]:first").attr('checked', true);
		$(this).parent().parent().empty().hide();
		processAjaxFilters(0);
	});
	
	initPager();
	
});

//init the paging links
function initPager() {
	
	var total_results = $('#total_results').val();
	var total_pages = Math.ceil(total_results/20); //default limit is 20
	$('#pagination > ul > li > a').click(function(){
		var className = $(this).attr('class');
		var cur_page = parseInt($('#cur_page').html());
		var per_page = 20;
		if(className=='limitstart') {
			var offset = (cur_page-2)*per_page;
			if(cur_page==1) return false;
		} else if(className=='endmilit') {
			var offset = (cur_page)*per_page;
			if(cur_page==total_pages) return false;
		} else if(className=='end') {
			var offset = (total_pages-1)*per_page;
		} else if(className=='start') {
			var offset = 0;
		} else {
			var offset = parseInt($(this).children()[0].innerHTML) - 1;
			offset = offset*per_page;
		}
		processAjaxFilters(offset);
		return false;
	});
}

function processAjaxFilters(offset, sort_by) {
	
	var baseUrl = top.location.pathname.toString();
	$('#resultsContainer').empty();
	
	var html = "<center><p>" +
			"<img src='"+baseUrl+"wp-content/themes/openunh/images/ajax-loader.gif' alt='Loading results' />" +
			"</p></center>";
			
	
	$('#resultsContainer').html(html);
	
	var url =  baseUrl + "wp-content/themes/openunh/ajax_search.php",
		urlSep = "?", country_fltr = '', region_fltr = '', sector_fltr = '', budget_fltr = '',
		sep = "", selectedFltrs = [], isFilter = false;
	
	$('.filterbox input[type=checkbox]:checked').each(function(){
		var control_name = $(this).attr('name');
		var key = $(this).val();
		switch(control_name) {
			case 'countries':
				if(!selectedFltrs['countries']) selectedFltrs['countries'] = [];
				if(country_fltr.length==0) sep = '';
				country_fltr += sep + key;
				sep = "|";
				var lbl = $(this).parent().text();
				if(key!='All') selectedFltrs['countries'][key] = lbl;
				break;
			case 'regions':
				if(!selectedFltrs['regions']) selectedFltrs['regions'] = [];
				if(region_fltr.length==0) sep = '';
				region_fltr += sep + key;
				sep = "|";
				var lbl = $(this).parent().text();
				if(key!='All') selectedFltrs['regions'][key] = lbl;
				break;
			case 'sectors':
				if(!selectedFltrs['sectors']) selectedFltrs['sectors'] = [];
				if(sector_fltr.length==0) sep = '';
				sector_fltr += sep + key;
				sep = "|";
				var lbl = $(this).parent().text();
				if(key!='All') selectedFltrs['sectors'][key] = lbl;
				break;
			case 'budgets':
				if(!selectedFltrs['budgets']) selectedFltrs['budgets'] = [];
				if(budget_fltr.length==0) sep = '';
				budget_fltr += sep + key;
				sep = "|";
				var lbl = $(this).parent().text();
				if(key!='All') selectedFltrs['budgets'][key] = lbl;
				break;
		}
	});
	
	country_fltr = country_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
	region_fltr = region_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
	sector_fltr = sector_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
	budget_fltr = budget_fltr.replace(/(All\|)|(\|All)|(All)/g, '');
	
	
	var keyword = jQuery('#s').val();
	if(keyword) {
		url +=  urlSep + "query=" + encodeURI(keyword);
		urlSep = "&";
	}
	
	
	if(country_fltr.length>0) {
		url +=  urlSep + "countries=" + country_fltr;
		urlSep = "&";
		isFilter = true;
	}
	if(region_fltr.length>0) {
		url +=  urlSep + "regions=" + region_fltr;
		urlSep = "&";
		isFilter = true;
	}
	if(sector_fltr.length>0) {
		url +=  urlSep + "sectors=" + sector_fltr;
		urlSep = "&";
		isFilter = true;
	}
	if(budget_fltr.length>0) {
		url +=  urlSep + "budgets=" + budget_fltr;
		urlSep = "&";
		isFilter = true;
	}
	
	var per_page = 20; //No per page selector
	
	url +=  urlSep + "limit=" + per_page;
	urlSep = "&";
	
	url +=  urlSep + "offset=" + offset;
	urlSep = "&";
	
	if(sort_by != null && sort_by.length>0) {
		url += urlSep + "order_by=" + sort_by;
	}
	
	jQuery.ajax({
		url: url,
		type: "GET",
		dataType: "json",
		success: function(data){
			
			var meta = data.meta;
			var objects = data.objects;
			
			applyResults(meta, objects);
			
		},
		error: function(msg){
			alert('AJAX error!' + msg);
			return false;
		}
	});
	
	if(isFilter) {
		applyFilterHTML(selectedFltrs);
	} else {
		$('.searchcriteria').empty().hide();
	}
}

function applyFilterHTML(selected) {
	$('.searchcriteria').empty();
	
	var html = '<div class="clearresults"><a href="javascript:void(0);" id="clearSearchBtn">Clear Search<span>X</span></a></div>';
	html += '<ul>';
	var sep = '';
	if(!$.isEmptyObject(selected.countries)) {
		html += '<li><span>Countries:</span>';
		for(key in selected.countries) {
			var lbl = selected.countries[key];
			if(key=='All') continue;
			html += sep + '<a href="#">'+lbl+'</a>';
			sep = ', ';
		}
		html += '</li>';
	}
	sep = '';
	if(!$.isEmptyObject(selected.regions)) {
		html += '<li><span>Regions:</span>';
		for(key in selected.regions) {
			var lbl = selected.regions[key];
			if(key=='All') continue;
			html += sep + '<a href="#">'+lbl+'</a>';
			sep = ', ';
		}
		html += '</li>';
	}
	sep = '';
	if(!$.isEmptyObject(selected.sectors)) {
		html += '<li><span>Sectors:</span>';
		for(key in selected.sectors) {
			var lbl = selected.sectors[key];
			if(key=='All') continue;
			html += sep + '<a href="#">'+lbl+'</a>';
			sep = ', ';
		}
		html += '</li>';
	}
	sep = '';
	if(!$.isEmptyObject(selected.budgets)) {
		html += '<li><span>Budget:</span>';
		for(key in selected.budgets) {
			var lbl = selected.budgets[key];
			if(key=='All') continue;
			html += sep + '<a href="#">'+lbl+'</a>';
			sep = ', ';
		}
		html += '</li>';
	}
	html += '</ul>';
	$('.searchcriteria').html(html);
	$('.searchcriteria').show();
	$('#clearSearchBtn').click(function(){
		$(".filterbox .filtercontent input[name=countries]").attr('checked', false);
		$(".filterbox .filtercontent input[name=countries]:first").attr('checked', true);
		$(".filterbox .filtercontent input[name=regions]").attr('checked', false);
		$(".filterbox .filtercontent input[name=regions]:first").attr('checked', true);
		$(".filterbox .filtercontent input[name=sectors]").attr('checked', false);
		$(".filterbox .filtercontent input[name=sectors]:first").attr('checked', true);
		$(".filterbox .filtercontent input[name=budgets]").attr('checked', false);
		$(".filterbox .filtercontent input[name=budgets]:first").attr('checked', true);
		$(this).parent().parent().empty().hide();
		processAjaxFilters(0);
	});
}

function applyResults(meta, objects) {
	
	var limit = meta.limit,
		offset = meta.offset,
		total_count = meta.total_count,
		baseUrl = top.location.pathname.toString(),
		back_url = top.location.search.toString();
		
	var html = "";
	if(total_count>0) {
		
		for(idx in objects) {
			var project = objects[idx];
			
			html += "<div class='searchresult row"+(idx+1)+"'>" +
					"<a id='detail_1' href='javascript:void(0);' class='moredetail'></a>" +
					"<h3><a href='" + baseUrl + "?page_id=2&id="+project.iati_identifier+"&back_url="+encodeURI(back_url)+"'>"+project.title.default+"</a></h3>" +
					"<span class='detail'><span>Countries</span>: "; 
			var sep = '';
			for(i in project.recipient_country) {
				html += sep + project.recipient_country[i].name;
				sep = ', ';
			}
		
			html += "</span>" +
					"<span class='detail'><span>Subject</span>: " +project.title.default+ "</span>" +
					"<span class='detail'><span>Budget</span>: US$ " + format_number(project.statistics.total_budget) + "</span>" +
					"<span class='detail'><span>Sector</span>: ";
				
			var sep = '';
			for(i in project.activity_sectors) {
				html += sep + project.activity_sectors[i].name;
				sep = ', ';
			}	
			html += "</span>" +
					"<p class='shortdescription'>"+project.description.default+"</p>" +
					"<div class='resultdetail detail_"+(idx+1)+"'>" +
					"<div class='rcol rcol1'>" +
					"<ul>" +
					"<li><span>Last updated: </span>" + project.date_updated+"</li>" +
					"<li><span>Status: </span>"+project.activity_status.name+"</li>" +
					"</ul>" +
					"</div>" +
					"<div class='rcol rcol2'>" +
					"<ul>" +
					"<li><span>Start date planned: </span>" + project.start_planned + "</li>" +
					"<li><span>Start date actual: </span>" + project.start_actual+"</li>" +
					"</ul>" + 
					"</div>" +
					"<div class='rcol rcol3'>" +
					"<ul>" +
					"<li><span>End date planned: </span>"+project.end_planned + "</li>" +
					"<li><span>End dat actual: </span>" +project.end_actual+"</li>" +
					"</ul>" +
					"</div>" +
					"<div class='clr'></div>" +
					"<div class='resultrow'>" +
					"<a href='"+baseUrl+"?page_id=42' class='whistleb'><span>WHISTLEBLOWER</span></a>" +
					"<a href='#' class='share'><span>SHARE</span></a>" +
					"<a href='#' class='bookmark'><span>BOOKMARK</span></a>" +
					"</div>" +
					"</div></div>";
					
		}
		
		//fix the paging 
		var per_page = 20;
		var total_pages = Math.ceil(total_count/limit);
		var cur_page = offset/limit + 1;
		var paging_block = "<ul class='menu pagination'><li><a href='javascript:void(0);' class='start'><span>&laquo;</span></a></li><li><a href='javascript:void(0);' class='limitstart'><span>&larr; </span></a></li>";
		var page_limit = 7;
		var fromPage = cur_page - 3;
		if(fromPage<=0) fromPage = 1;
		var loop_limit = (total_pages>page_limit?(fromPage + page_limit - 1):total_pages);
		

		for(i=fromPage; i<=loop_limit; i++) {
			paging_block += "<li>";
			if(cur_page==i) {
				paging_block += "<a href='javascript:void(0);' class='active'><span id='cur_page'>"+i+"</span></a>";
			} else {
				paging_block += "<a href='javascript:void(0);' class='page'><span>"+i+"</span></a>";
			}
			paging_block += "</li>";
		}
		if((fromPage+loop_limit)<(total_pages-3)) {
			if(total_pages>page_limit) {
				paging_block += "<li>...</li>";
			}
			
			for(i=total_pages-2; i<=total_pages; i++) {
				paging_block += "<li>";
				
				paging_block += "<a href='javascript:void(0);' class='page'><span>"+i+"</span></a>";
			
				paging_block += "</li>";
			}
		} else {

			for(i=loop_limit+1; i<=total_pages; i++) {
				paging_block += "<li>";
				if(cur_page==i) {
					paging_block += "<a href='javascript:void(0);' class='active'><span id='cur_page'>"+i+"</span></a>";
				} else {
					paging_block += "<a href='javascript:void(0);' class='page'><span>"+i+"</span></a>";
				}
				paging_block += "</li>";
			}
			
		}
		
		paging_block += "<li><a href='javascript:void(0);' class='endmilit'><span>&rarr; </span></a></li><li><a href='javascript:void(0)' class='end'><span>&raquo;</span></a></li></ul>";
		$('#pagination').empty().html(paging_block);
		
		$('#pagination > ul > li > a').click(function(){
			var className = $(this).attr('class');
			var cur_page = parseInt($('#cur_page').html());
			var per_page = 20;
			if(className=='limitstart') {
				var offset = (cur_page-2)*per_page;
				if(cur_page==1) return false;
			} else if(className=='endmilit') {
				var offset = (cur_page)*per_page;
				if(cur_page==total_pages) return false;
			} else if(className=='end') {
				var offset = (total_pages-1)*per_page;
			} else if(className=='start') {
				var offset = 0;
			} else {
				var offset = parseInt($(this).children()[0].innerHTML) - 1;
				offset = offset*per_page;
			}
			processAjaxFilters(offset);
			return false;
		});
	
	} else {
		html += "<div class='searchresult row1'>" +
				"No results" +
				"</div>";
		jQuery('#pagination').hide();
			
	}
	
	var resultsHTML = 'Results ' + (offset+1) + ' - ';
	if((offset+limit)>total_count) {
		resultsHTML += total_count;
	} else {
		resultsHTML += offset+limit;
	}
	resultsHTML += ' of ' + total_count;
	$('.searchresultstitle>h4').html(resultsHTML);
	
	jQuery('#resultsContainer').empty();
	jQuery('#resultsContainer').html(html);
}
	
/**
* Formats the number according to the ‘format’ string;
* adherses to the american number standard where a comma
* is inserted after every 3 digits.
*  note: there should be only 1 contiguous number in the format,
* where a number consists of digits, period, and commas
*        any other characters can be wrapped around this number, including ‘$’, ‘%’, or text
*        examples (123456.789):
*          ‘0? - (123456) show only digits, no precision
*          ‘0.00? - (123456.78) show only digits, 2 precision
*          ‘0.0000? - (123456.7890) show only digits, 4 precision
*          ‘0,000? - (123,456) show comma and digits, no precision
*          ‘0,000.00? - (123,456.78) show comma and digits, 2 precision
*          ‘0,0.00? - (123,456.78) shortcut method, show comma and digits, 2 precision
*
* @method format
* @param format {string} the way you would like to format this text
* @return {string} the formatted number
* @public
*/ 

function stripNonNumeric(s) {
	return s.replace(/[^\d\.-]/g, '');
}

function format_number(format) {
  
	var s = format.split('.');
	var parts = "";
	if(s[0].length>3) {
		parts = "." + s[0].substring(s[0].length-3, s[0].length);
		s[0] = s[0].substring(0, s[0].length-3);
		if(s[0].length>3) {
			parts = "." + s[0].substring(s[0].length-3, s[0].length) + parts;
			s[0] = s[0].substring(0, s[0].length-3);
			if(s[0].length>3) {
				parts = "." + s[0].substring(s[0].length-3, s[0].length) + parts;
				s[0] = s[0].substring(0, s[0].length-3);
			} else {
				parts = s[0] + parts;
			}
		} else {
			parts = s[0] + parts;
		}
	} else {
		parts = s[0] + parts;
	}
	
	var ret = parts;
	
	if(s.length>1) {
		if(s[1]!="00") {
			ret += "," + s[1];
		}
	}
	
	return ret;
};


/***********************************************
* Bookmark site script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

/* Modified to support Opera */
function bookmarksite(title,url){
if (window.sidebar) // firefox
	window.sidebar.addPanel(title, url, "");
else if(window.opera && window.print){ // opera
	var elem = document.createElement('a');
	elem.setAttribute('href',url);
	elem.setAttribute('title',title);
	elem.setAttribute('rel','sidebar');
	elem.click();
} 
else if(document.all)// ie
	window.external.AddFavorite(url, title);
}