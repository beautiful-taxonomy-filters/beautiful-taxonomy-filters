(function( $ ) {
	'use strict';

	//Document is ready for some JS magic!
	$(document).ready(function(){

		/**
		 * Format the results of select2
		 */
		function formatResult (term) {
			if (!term.id || term.text.indexOf(":.:") === -1) { return term.text; }

			var new_term = term.text;
			new_term = new_term.replace(':.:', ' <br><span class="term-description">');
			new_term = new_term.replace(':-:', '</span>');
			var $term = $(
				'<span>' + new_term + '</span>'
			);
			return $term;
		};


		/**
		 * Format the selection of select2
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
		};


		/**
		 * Lets select2 all night long
		 */
		if( btf_localization.show_description === '1' ){
			$('.beautiful-taxonomy-filters-select').select2({
				allowClear: btf_localization.allow_clear,
				minimumResultsForSearch: parseInt(btf_localization.min_search),
				templateResult: formatResult,
				templateSelection: formatSelection
			});
		}else{
			$('.beautiful-taxonomy-filters-select').select2({
				allowClear: btf_localization.allow_clear,
				minimumResultsForSearch: parseInt(btf_localization.min_search)
			});
		}


	});

})( jQuery );
