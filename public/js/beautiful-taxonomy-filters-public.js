(function( $ ) {
	'use strict';

	//Document is ready for some JS magic!
	$(document).ready(function(){
		$('.beautiful-taxonomy-filters-select').select2({
			allowClear: btf_localization.allow_clear,
			minimumResultsForSearch: parseInt(btf_localization.min_search)
		});
	});

})( jQuery );
