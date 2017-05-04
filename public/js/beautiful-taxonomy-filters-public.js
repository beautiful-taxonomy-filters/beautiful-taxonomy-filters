(function( $ ) {
	'use strict';

	/**
	 * Variables used by the AJAX call
	 */
	var xhr;
    var active = false;
    var timer;


	/**
	 * Old-timer function used by select2 to match by characters in their order of appearance (or whatever).
	 */
	function matchStart (term, text) {
		if (text.toUpperCase().indexOf(term.toUpperCase()) === 0) {
			return true;
		}
		return false;
	}


	/**
	 * Lets select2 all night long
	 */
	function create_select2_dropdown( select_el ){

		var args = {
			allowClear: btf_localization.allow_clear,
			syncCssClasses: true,
			minimumResultsForSearch: parseInt(btf_localization.min_search)
		};

		if( btf_localization.show_description == '1' ){
			args.templateResult = formatResult;
			args.templateSelection = formatSelection;
		}

		/**
		 * Support language
		 */
		if( btf_localization.language !== '' ) {
			args.language = btf_localization.language;
		}

		/**
		 * Support RTL
		 */
		if( btf_localization.rtl == '1' ) {
			args.dir = 'rtl';
		}

		var select2;
		if ( btf_localization.disable_fuzzy == '1' ) {
			$.fn.select2.amd.require(['select2/compat/matcher'], function (oldMatcher) {
				args.matcher = oldMatcher(matchStart);
				if ( typeof select_el !== 'undefined' ) {
					select2 = select_el.select2(args);
				}else{
					select2 = $('.beautiful-taxonomy-filters-select').select2(args);
				}
			});
		} else {

			if ( typeof select_el !== 'undefined' ) {
				select2 = select_el.select2(args);
			}else{
				select2 = $('.beautiful-taxonomy-filters-select').select2(args);
			}

		}

	}


	/**
	 * Format the results of select2
	 *
	 */
	function formatResult (term) {
		if (!term.id ){ return term.text; }

		var new_term = term.text;
		if( term.text.indexOf(":.:") !== -1) {
			new_term = new_term.replace(':.:', ' <br><span class="term-description">');
			new_term = new_term.replace(':-:', '</span>');

		}

		var $term = $(
			'<span class="' + term.element.className + '">' + new_term + '</span>'
		);
		return $term;

	}


	/**
	 * Format the selection of select2
	 *
	 */
	function formatSelection (term) {


		if (!term.id || term.text.indexOf(":.:") === -1) { return term.text; }

		//run a regexp on the text to find :.:<any characters:-: and then replace it
		var new_term = term.text;
		var re = /(:\.:[\s\S]*?:-:)/;
		var reg_results = re.exec(new_term);
		new_term = new_term.replace(reg_results[0], '');
		var $term = $(
			'<span>' + new_term + '</span>'
		);
		return $term;
	}


	/**
	 * Run the AJAX update function to make terms conditional.
	 *
	 * @param el	jQuery object of the select that changed.
	 */
	function conditional_terms_ajax( el ){

		/**
		 * If there's already an active AJAX request kill it.
		 */
		if( active ) {
			xhr.abort();
		}

		/**
		 * Show loaders and disable selects if the response takes more than 1 second.
		 */
		if( timer ){
			clearTimeout(timer);
		}
		timer = setTimeout(function(){
        	form.find('.beautiful-taxonomy-filters-loader').addClass('active');
        	form.find('select.beautiful-taxonomy-filters-select').prop('disabled', true);
        	form.find('.beautiful-taxonomy-filters-button').prop('disabled', true);
        }, 800);


		/**
		 * Get general options
		 */
		var form = el.closest('#beautiful-taxonomy-filters-form'),
			nonce = el.data('nonce'),
			posttype = $('input[name="post_type"]').val(),
			current_taxonomy = el.data('taxonomy'),
			selects = [];

		/**
		 * Get values from all selects
		 */
		form.find('select.beautiful-taxonomy-filters-select').each(function(index){
			var sel = $(this),
				taxonomy = sel.data('taxonomy'),
				val = sel.val();

			if( val === '' ){
				val = 0;
			}

			selects.push({
				taxonomy: sel.data('taxonomy'),
				term: val,
			});

		});

		/**
		 * Run our AJAX
		 */
		active = true;
		xhr = $.ajax({
			type: 'post',
			dataType: 'json',
			url: btf_localization.ajaxurl,
			data: {
				action: 'update_filters_callback',
				selects: selects,
				posttype: posttype,
				current_taxonomy: current_taxonomy,
				nonce: nonce,
			},
			success: function( response ){

				/**
				 * Make sure all dropdowns are enabled and loaders are hidden
				 */
				if( timer ){
					clearTimeout(timer);
				}
				form.find('select.beautiful-taxonomy-filters-select').prop( 'disabled', false );
				form.find('.beautiful-taxonomy-filters-loader').removeClass('active');
				form.find('.beautiful-taxonomy-filters-button').prop('disabled', false);

				/**
				 * Lets get cracking on hiding options
				 */
				if( Object.keys(response.taxonomies).length > 0 ){

					$.each(response.taxonomies, function(taxonomy, terms){
						var select_element = form.find('select.beautiful-taxonomy-filters-select[data-taxonomy="' + taxonomy + '"]');
						select_element.find('option').each(function(){
							var option = $(this),
								val = option.val(),
								option_text = option.text();

							/**
							 * empty or 0 is probably an "all" option and we should leave these alone!
							 * also.. leave britney alone!
							 */
							if( val === '' || val === 0 || val == '0' ){
								return true;

							}

							if( $.inArray( val, terms ) === -1 ){
								option.prop('disabled', true);

							} else {
								option.prop('disabled', false);

							}
						});

						/**
						 * If select2 is being used we need to destroy the instance and run a new one.
						 */
						if( btf_localization.disable_select2 != '1' ){
							var select_el = form.find('select.beautiful-taxonomy-filters-select[data-taxonomy="' + taxonomy + '"]');
							select_el.select2('destroy');
							create_select2_dropdown(select_el);
						}

						/**
						 * These do not work consistently.. select2 has some work to do.
						 * form.find('select.beautiful-taxonomy-filters-select[data-taxonomy="' + taxonomy + '"]').trigger('change.select2');
						 */

					});

				}
			},
			error: function(){
				/**
				 * Make sure all dropdowns are enabled and loaders are hidden
				 */
				if( timer ){
					clearTimeout(timer);
				}
				form.find('select.beautiful-taxonomy-filters-select').prop( 'disabled', false );
				form.find('.beautiful-taxonomy-filters-loader').removeClass('active');
				form.find('.beautiful-taxonomy-filters-button').prop('disabled', false);

			},
			complete: function(){
				/**
				 * Regardless of success/error we are done. Set active to false.
				 */
				active = false;
			}
		});

	}


	//Document is ready for some JS magic!
	$(document).ready(function(){

		/**
		 * Trigger select2
		 *
		 */
		if( btf_localization.disable_select2 != 1 ){
			create_select2_dropdown();
		}


		/**
		 * Update the terms of each taxonomy on the fly
		 * This allows us to only show relevant terms whenever a selection has been made.
		 *
		 */
		if( btf_localization.conditional_dropdowns == 1 ){

			/**
			 * Trigger on the first select with a value on page load.
			 * This will find all forms and look in each of them. By doing this we make sure all forms that should be updated will be.
			 */
			var forms = $('.beautiful-taxonomy-filters form, .beautiful-taxonomy-filters-widget form');
			for( var i = 0; i < forms.length; i++ ){
				var selects = $(forms[i]).find('.beautiful-taxonomy-filters-select');
				for( var j = 0; j < selects.length; j++ ){
					if( $(selects[j]).val() !== 0 ){
						conditional_terms_ajax( $(selects[j]) );
						break;

					}

				}

			}


			/**
			 * Trigger whenever select is changed
			 */
			$('.beautiful-taxonomy-filters, .beautiful-taxonomy-filters-widget').on('change', '.beautiful-taxonomy-filters-select', function(){
				var el = $(this);
				conditional_terms_ajax( el );

			});
		}

	});

})( jQuery );
