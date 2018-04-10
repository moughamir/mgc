/**
 * @package Social Ninja
 * @version 1.2
 * @author InspiredDev
 * @copyright 2015
 */

$ = jQuery; 

function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex ;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

function saveBlobImage(img, mid)
{
	var formData = new FormData();
	formData.append('file', img, 'image.jpg');
	formData.append('action', 'vp_meme_save');
	formData.append('media_id', mid);
	formData.append('_nonce', meme_ajax_nonce);
	
	if( $('.recap').length > 0 ) {
		var other_data = $('.recap').serializeArray();
		$.each(other_data,function(key,input){
			formData.append(input.name,input.value);
		});	
	}
	
	$('.import-edited-img-int').attr( 'disabled', true );
	notify('wait', meme_lang.saving_img+'...');
	
	$.ajax( meme_ajaxurl, {
		method: "POST",
		data: formData,
		processData: false,
		contentType: false,
		success: function (response) {
			$('.sp-container').hide();
			$('.import-edited-img-int').attr( 'disabled', false );
		  	var data = $.parseJSON(response);
			if(data.error != '')return notify('error', data.error);
			else{	
				if( tb == 'new' ) {
					p = add_new_entry( "list" ); 
					pp = p.parents(".more_items:first"); 
					add_thumb_image( p.parents(".more_items:first").find(".thumbnail_uploader"), data.media_id, data.media_url, null );
					tb_remove();
				}
				else if( tb == 'open' ) {
					var e = $( '.vp-uploader:visible' ).find( '.thumbnail_uploader' );
					add_thumb_image( e, data.media_id, data.media_url, null );
					tb_remove();
				}
				else if( tb != '' ) {
					var e = $( 'input[name="' + tb + '"]' ).parents( '.more_items:first' ).find( '.thumbnail_uploader' );
					add_thumb_image( e, data.media_id, data.media_url, null );
					tb_remove();
				}
				else notify( 'success', meme_lang.img_saved + '. <a href="'+data.media_url+'">'+ meme_lang.view_here +'</a>' );
			}
		},
		error: function () {
			notify('error', meme_lang.img_save_fail);
		}
	  });
}

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {type: contentType});
    return blob;
}

function loadCanvasFonts()
{
	html = '<option value="Impact">Impact</option>';
	html += '<option value="Josefin Slab">Josefin Slab</option>';
	html += '<option value="Georgia">Georgia</option>';	
	html += '<option value="tahoma">Tahoma</option>';	
	html += '<option value="Times New Roman">Times New Roman</option>';	
	html += '<option value="Arial">Arial</option>';
	html += '<option value="Comic Sans MS">Comic Sans MS</option>';
	html += '<option value="Lucida Sans Unicode">Lucida Sans Unicode</option>';
	html += '<option value="Trebuchet MS">Trebuchet MS</option>';
	html += '<option value="Verdana">Verdana</option>';
	html += '<option value="Courier New">Courier New</option>';	
	html += '<option value="Lucida Console">Lucida Console</option>';	
	return html
}

/*
 * Code for meme generation
 */
var canvas;
var stage;		
var update = true;
var prev_angle = 0;
/**
 * Init 
 */
 
memeinit = function () {
	
	var fonts = loadCanvasFonts();
	$('.wm-font, .wm-font-2').html(fonts);
	$('#img_cw').val(0);
	$('.sp-container').css( 'display', 'block' );
	colorpicker();

	$('.tint-color').val('#ffffff');
	if(preload_file != null){
		if(preload_file != ''){
			$('.editor-after-img').html('<img src="'+preload_file+'"/>')
			getImageSize(preload_file, 0);
			$('.editor').show();
		}
	}
	
	document.getElementById('download-edited-img-int').addEventListener('click', function() {
		this.href = document.getElementById("mycanvas").toDataURL( "image/jpeg", 0.75 );
		this.download = 'image.jpg';	
	}, false);

	
	canvas = document.getElementById("mycanvas");
	stage = new createjs.Stage(canvas);
	createjs.Touch.enable(stage);
	stage.enableMouseOver(10);
}

jQuery(window).bind('tb_unload', function () {
    $('.sp-container').css( 'display', 'none' );
});

/**
 * Load and display the uploaded picture on CreateJS Stage 
 */
displayPicture = function (imgPath, dragDrop, pos) {

	var image = new Image();	
	image.onload = function (event) {
		// Create a Bitmap from the loaded image
		var img = new createjs.Bitmap(event.target)
		// scale it
		var width = img.image.width;
		var height = img.image.height;
		var r = 1;
		if(dragDrop == 0)r = 1;//getRatio(width);
		else{
			img.x = 10;
			img.y = 10;
			if(pos != null){
				if(pos == 2)img.y = canvas.height - 90;
			}
		}
		
		img.scaleX = img.scaleY = img.scale = r;
		/// Add to display list
		stage.addChild(img);
		//Enable Drag'n'Drop 
		if(dragDrop == 1){
			img.cursor = 'move';
			img.addEventListener("mousedown", function (evt) {
				// bump the target in front of its siblings:
				var o = evt.target;
				o.parent.addChild(o);
				o.offset = {x: o.x - evt.stageX, y: o.y - evt.stageY};
			});

			// the pressmove event is dispatched when the mouse moves after a mousedown on the target until the mouse is released.
			img.addEventListener("pressmove", function (evt) {
				var o = evt.target;
				o.x = evt.stageX + o.offset.x;
				o.y = evt.stageY + o.offset.y;
				// indicate that the stage should be updated on the next tick:
				update = true;
			});
		}
		// Render Stage
		stage.update();
	}
	// Load the image
	image.src = imgPath;
	createjs.Ticker.addEventListener("tick", tick);
}

function tick(event) {
	// this set makes it so the stage only re-renders when an event handler indicates a change has happened.
	if (update) {
		update = false; // only update once
		stage.update(event);
	}
}

getImageSize = function(src, dragDrop) {
	var img = new Image();
	img.src = src;
	img.addEventListener('load', function() {		
	  // once the image is loaded:
	  var context = canvas.getContext('2d');
	  if(!dragDrop){
		context.clearRect(0, 0, canvas.width, canvas.height);  
	  }
	  
	  var width = img.naturalWidth;
	  var height = img.naturalHeight;
	  
	  context.canvas.width = width;
	  context.canvas.height = height;
	  
	  var r = getRatio(width);
	  
	  width = parseInt(width*r);
	  height = parseInt(height*r);
	  
	  $('#imw').val(width);
	  $('#imh').val(height);
	  
	  canvas.style.width = width;
	  canvas.style.height = height;
	  	  
	  displayPicture(src, dragDrop);
	  
	}, false);
	img.src = src;	
}

getRatio = function(width)
{	
	if(width <= 500){
		return 1;
	}	
	else if(width > 500 && width <= 600){
		return 0.70;
	}
	else if(width > 600 && width <= 800){
		return 0.60;
	}
	else if(width > 800 && width <= 1000){
		return 0.50;
	}
	else if(width > 1000 && width <= 1300){
		return 0.40;
	}
	else if(width > 1300 && width <= 1500){
		return 0.30;
	}
	else return 0.20;
}

writeText = function(pos){
	if(pos == 1){
		var text = $('.wm-text').val();
		var color = $('.wm-col').val();
		var font = $('.wm-font').val();
		var opacity = parseFloat($('.wm-opacity').val());
		var fsize = parseInt($('.wm-font-size').val());
		var str = $('.wm-str').val();
		var strsize = parseInt($('.wm-str-size').val());
		//var angle = parseInt($('.wm-rotate').val());
	}
	else{
		var text = $('.wm-text-2').val();
		var color = $('.wm-col-2').val();
		var font = $('.wm-font-2').val();
		var opacity = parseFloat($('.wm-opacity-2').val());
		var fsize = parseInt($('.wm-font-size-2').val());	
		var str = $('.wm-str-2').val();
		var strsize = parseInt($('.wm-str-size-2').val());
		//var angle = parseInt($('.wm-rotate-2').val());
	}
	
	var angle = 0;
	
	if(isNaN(opacity) || isNaN(fsize) || isNaN(angle) || text == ''){
		return notify('error', meme_lang.inv_input);	
	}
	if(fsize > 200 || fsize < 1 || strsize > 200 || strsize < 0){
		return notify('error', meme_lang.font_size_must);	
	}
	if(opacity > 1 || opacity < 0){
		return notify('error', meme_lang.opa_must);	
	}
	if(angle > 180 || angle < -180){
		return notify('error', meme_lang.angle_must);	
	}
	
	if(pos == 1)var customcanvas = document.getElementById("hcanvas");
	else var customcanvas = document.getElementById("hcanvas2");
	
	color = color.replace('rgb', 'rgba');
	color = color.replace(')', ',' + opacity + ')');	
	if(color == '')color = 'rgba(255, 255, 255, 1)';
	
  	var context = customcanvas.getContext("2d");	
	context.clearRect(0, 0, customcanvas.width, customcanvas.height);
	context.fillStyle = color;
	if( strsize > 0 ) {
		context.strokeStyle = str;
  		context.lineWidth = strsize;
	}
	context.font = fsize+"px "+font;
	if( fsize > 60 ){
		if( strsize > 0 )context.strokeText(text, 80, 80);
		context.fillText(text, 80, 80);
	}
	else {
		if( strsize > 0 )context.strokeText(text, 48, 48);
		context.fillText(text, 48, 48);
	}
	var src = customcanvas.toDataURL();
	displayPicture(src, 1, pos);
	$('#img_cw').val(1);	
}

$(document).on('click', '.wmm-apply', function(){
	var s = 0;
	if($('.wm-text').val() != ''){
		writeText(1);
		$('.wm-text').val('');
		s = 1;
	}
	if($('.wm-text-2').val() != ''){
		writeText(2);	
		$('.wm-text-2').val('');
		s = 1;
	}
	if(!s)return notify('error', meme_lang.btm);
	else notify('success', meme_lang.added_now_drag);
	$('#import-edited-type').val('image');
	$('#edited-img').val(1);
});

$(document).on('click', '.wmm-reset', function(){
	var file = $('.editor-after-img > img').attr('src');
	getImageSize(file, 0);	
	$('#img_cw').val(0);
});


$(document).on('click', '.import-edited-img-int', function(){
	if($('#img_cw').val() == 0)return notify( 'error', meme_lang.no_change );
	var canvas = document.getElementById("mycanvas");
	canvas.toBlob(function(img){
		saveBlobImage(img, media_id);
	}, "image/jpeg", 0.75 );	
});

/**
 * check if this page is meme.php | has meme uploader
 */

function colorpicker(){
	var val3 = '#ffffff';
	
	$('.sp-replacer').remove();	
	$(".tint-color").spectrum({
		showInput: true,
		preferredFormat: "rgb",
		//color: val3
	});
}

function notify( type, msg)
{
	if( type == 'error')alert( msg )
	$('.vp_meme_feed').html( msg ).show();
}


/* canvas-toBlob.js
 * A canvas.toBlob() implementation.
 * 2013-12-27
 * 
 * By Eli Grey, http://eligrey.com and Devin Samarin, https://github.com/eboyjr
 * License: MIT
 *   See https://github.com/eligrey/canvas-toBlob.js/blob/master/LICENSE.md
 */

/*global self */
/*jslint bitwise: true, regexp: true, confusion: true, es5: true, vars: true, white: true,
  plusplus: true */

/*! @source http://purl.eligrey.com/github/canvas-toBlob.js/blob/master/canvas-toBlob.js */

(function(view) {
"use strict";
var
	  Uint8Array = view.Uint8Array
	, HTMLCanvasElement = view.HTMLCanvasElement
	, canvas_proto = HTMLCanvasElement && HTMLCanvasElement.prototype
	, is_base64_regex = /\s*;\s*base64\s*(?:;|$)/i
	, to_data_url = "toDataURL"
	, base64_ranks
	, decode_base64 = function(base64) {
		var
			  len = base64.length
			, buffer = new Uint8Array(len / 4 * 3 | 0)
			, i = 0
			, outptr = 0
			, last = [0, 0]
			, state = 0
			, save = 0
			, rank
			, code
			, undef
		;
		while (len--) {
			code = base64.charCodeAt(i++);
			rank = base64_ranks[code-43];
			if (rank !== 255 && rank !== undef) {
				last[1] = last[0];
				last[0] = code;
				save = (save << 6) | rank;
				state++;
				if (state === 4) {
					buffer[outptr++] = save >>> 16;
					if (last[1] !== 61 /* padding character */) {
						buffer[outptr++] = save >>> 8;
					}
					if (last[0] !== 61 /* padding character */) {
						buffer[outptr++] = save;
					}
					state = 0;
				}
			}
		}
		// 2/3 chance there's going to be some null bytes at the end, but that
		// doesn't really matter with most image formats.
		// If it somehow matters for you, truncate the buffer up outptr.
		return buffer;
	}
;
if (Uint8Array) {
	base64_ranks = new Uint8Array([
		  62, -1, -1, -1, 63, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1
		, -1, -1,  0, -1, -1, -1,  0,  1,  2,  3,  4,  5,  6,  7,  8,  9
		, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25
		, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35
		, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51
	]);
}
if (HTMLCanvasElement && !canvas_proto.toBlob) {
	canvas_proto.toBlob = function(callback, type /*, ...args*/) {
		  if (!type) {
			type = "image/png";
		} if (this.mozGetAsFile) {
			callback(this.mozGetAsFile("canvas", type));
			return;
		} if (this.msToBlob && /^\s*image\/png\s*(?:$|;)/i.test(type)) {
			callback(this.msToBlob());
			return;
		}

		var
			  args = Array.prototype.slice.call(arguments, 1)
			, dataURI = this[to_data_url].apply(this, args)
			, header_end = dataURI.indexOf(",")
			, data = dataURI.substring(header_end + 1)
			, is_base64 = is_base64_regex.test(dataURI.substring(0, header_end))
			, blob
		;
		if (Blob.fake) {
			// no reason to decode a data: URI that's just going to become a data URI again
			blob = new Blob
			if (is_base64) {
				blob.encoding = "base64";
			} else {
				blob.encoding = "URI";
			}
			blob.data = data;
			blob.size = data.length;
		} else if (Uint8Array) {
			if (is_base64) {
				blob = new Blob([decode_base64(data)], {type: type});
			} else {
				blob = new Blob([decodeURIComponent(data)], {type: type});
			}
		}
		callback(blob);
	};

	if (canvas_proto.toDataURLHD) {
		canvas_proto.toBlobHD = function() {
			to_data_url = "toDataURLHD";
			var blob = this.toBlob();
			to_data_url = "toDataURL";
			return blob;
		}
	} else {
		canvas_proto.toBlobHD = canvas_proto.toBlob;
	}
}
}(typeof self !== "undefined" && self || typeof window !== "undefined" && window || this.content || this));
