/*
 * Reusable "choose an existing image or upload a new one" picker for
 * admin image fields. Usage in markup:
 *
 *   <input type="hidden" name="card_image" id="picker_card_image" value="...">
 *   <img id="preview_card_image" src="..." class="d-none">
 *   <button type="button" onclick="openImagePicker('card_image','home_cards')">Choose Image</button>
 *
 * Backed by admin/list_picker_images.php (browse) and
 * admin/upload_picker_image.php (upload) - both scoped by the "table"
 * argument, which maps to a folder via includes/s3-config.php's
 * getFolderName().
 */

(function(){

	var $modal = null;
	var currentField = null;
	var currentTable = null;

	function ensureModal(){
		if($modal){ return $modal; }

		var html = ''
			+ '<div class="modal fade" id="imagePickerModal" tabindex="-1" role="dialog">'
			+   '<div class="modal-dialog modal-lg" role="document">'
			+     '<div class="modal-content">'
			+       '<div class="modal-header">'
			+         '<h5 class="modal-title">Choose Image</h5>'
			+         '<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>'
			+       '</div>'
			+       '<div class="modal-body">'
			+         '<div class="form-group">'
			+           '<label class="font-weight-bold">Upload New</label>'
			+           '<input type="file" id="imagePickerUploadInput" class="form-control" accept="image/*">'
			+           '<div id="imagePickerUploadStatus" class="text-muted mt-1"></div>'
			+         '</div>'
			+         '<hr>'
			+         '<label class="font-weight-bold">Or Choose Existing</label>'
			+         '<div id="imagePickerGrid" class="row" style="max-height:400px; overflow-y:auto;">'
			+           '<div class="col-12 text-muted">Loading...</div>'
			+         '</div>'
			+       '</div>'
			+     '</div>'
			+   '</div>'
			+ '</div>';

		$('body').append(html);
		$modal = $('#imagePickerModal');

		$('#imagePickerUploadInput').on('change', function(){
			var file = this.files[0];
			if(!file){ return; }

			var formData = new FormData();
			formData.append('file', file);
			formData.append('table', currentTable);

			$('#imagePickerUploadStatus').text('Uploading...');

			$.ajax({
				url: 'upload_picker_image.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json'
			}).done(function(response){
				if(response && response.filename){
					selectImage(response.filename, response.url);
					$('#imagePickerUploadStatus').text('');
					loadImages(currentTable); // refresh grid so it's selectable next time too
				}else{
					$('#imagePickerUploadStatus').text((response && response.error) ? response.error : 'Upload failed.');
				}
			}).fail(function(){
				$('#imagePickerUploadStatus').text('Upload failed.');
			});
		});

		return $modal;
	}

	function loadImages(table){
		var $grid = $('#imagePickerGrid');
		$grid.html('<div class="col-12 text-muted">Loading...</div>');

		$.ajax({
			url: 'list_picker_images.php',
			type: 'GET',
			data: { table: table },
			dataType: 'json'
		}).done(function(images){
			if(!images || images.length === 0){
				$grid.html('<div class="col-12 text-muted">No existing images found. Upload one above.</div>');
				return;
			}
			var html = '';
			for(var i = 0; i < images.length; i++){
				html += '<div class="col-3 mb-3 text-center image-picker-item" style="cursor:pointer;" data-filename="'
					+ images[i].filename.replace(/"/g,'&quot;') + '" data-url="' + images[i].url.replace(/"/g,'&quot;') + '">'
					+ '<img src="' + images[i].url + '" class="img-fluid img-thumbnail" style="max-height:90px;">'
					+ '</div>';
			}
			$grid.html(html);
			$grid.find('.image-picker-item').on('click', function(){
				selectImage($(this).data('filename'), $(this).data('url'));
			});
		}).fail(function(){
			$grid.html('<div class="col-12 text-danger">Could not load images.</div>');
		});
	}

	function selectImage(filename, url){
		if(!currentField){ return; }
		$('#picker_' + currentField).val(filename);
		var $preview = $('#preview_' + currentField);
		$preview.attr('src', url).removeClass('d-none');
		$modal.modal('hide');
	}

	window.openImagePicker = function(fieldName, table){
		currentField = fieldName;
		currentTable = table;
		ensureModal();
		$('#imagePickerUploadInput').val('');
		$('#imagePickerUploadStatus').text('');
		loadImages(table);
		$modal.modal('show');
	};

})();
