/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev
 * @copyright 2016
*/
if(typeof vp_lang != 'undefined') {
	var vp_text_entry_template = 
		'<div class="more_items more_items_numbered">'+
			'<fieldset>'+
				'<legend>'+ vp_lang.text +'</legend>'+
				'<div class="vp-pull-right vp-pointer">'+
					'<span class="entry-no">[number]</span>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-arrow-up entry_move_up" title="'+ vp_lang.entry_move_up +'"></i>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-arrow-down entry_move_down" title="'+ vp_lang.entry_move_down +'"></i>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-trash remove_entry" title="'+ vp_lang.remove_entry +'"></i>'+
				'</div>'+
				'<label for="entry_text[[f_number]]">'+ vp_lang.title +'</label>'+
				'<input type="text" name="entry_text[[f_number]]" class="entry_text vp-form-control" placeholder="'+vp_lang.type_title+ ' ' + vp_lang.optional +'"/>'+
				'<label for="entry_desc[[f_number]]">'+ vp_lang.desc +'</label>'+
				'<div class="wp-editor-tabs">'+
					'<img class="pull-right toggle_tinymce vp-pointer" src="'+vp_img_dir_url+'/scode.png" width="25px" title="'+vp_lang.toggle_source+'"/>'+
				'</div>'+
				'<textarea id="tareaid" class="entry_desc tinymce vp-form-control" '+
				'name="entry_desc[[f_number]]" placeholder="'+vp_lang.type_desc+ ' ' + vp_lang.required +'"></textarea>'+
				'<label for="entry_source[[f_number]]">'+ vp_lang.source +'</label>'+
				'<input type="text" name="entry_source[[f_number]]" class="entry_source vp-form-control" placeholder="'+vp_lang.type_source+ ' ' + vp_lang.optional +'"/>'+
				'<input type="hidden" name="entry_type[[f_number]]" value="text" />'+
				'<input type="hidden" name="entry_post_id[[f_number]]" class="entry_post_id" value="" />'+
				'<input type="hidden" name="entry_order[[f_number]]" value="[number_c]" class="entry-no-val"/>'+
				'<div class="vp-clearfix"></div>'+
				'<input type="checkbox" name="entry_show_num[[f_number]]" value="1" class="entry-show-num" checked="checked"/>&nbsp;&nbsp;'+
				'<span class="show_numering">'+ vp_lang.show_numering +'</span>'+
			'</fieldset>'+
		'</div>';
		
	var vp_list_entry_template = 
		'<div class="more_items more_items_numbered">'+
			'<fieldset>'+
				'<!--title-->'+
				'<div class="vp-pull-right vp-pointer">'+
					'<span class="entry-no">[number]</span>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-arrow-up entry_move_up" title="'+ vp_lang.entry_move_up +'"></i>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-arrow-down entry_move_down" title="'+ vp_lang.entry_move_down +'"></i>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-trash remove_entry" title="'+ vp_lang.remove_entry +'"></i>'+
				'</div>'+
				'<div class="vp-clearfix"></div>'+
				'<div class="row">'+
					'<div class="col-lg-12">'+
						'<div class="vp-uploader vp-uploader-list vp-uploader-lesspad text-center" data-target="entry_images[[f_number]]">'+
                            '<button class="thumbnail_uploader list_img btn btn-sm btn-danger">'+
                                '<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
                                 vp_lang.upload_photo +
								 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
                            '</button>'+
							'<span class="list_exclusive">'+
								'<h4 class="big_or">'+vp_lang.big_or+'</h4>'+
								'<span class="thumbnail_uploader list_img thumbnail_uploader_url vp-pointer" style="font-size:15px">'+
									'<i class="glyphicon glyphicon-link"></i>&nbsp;'+vp_lang.upload_from_link+
								'</span>'+
							'</span>'+
                        '</div>'+
                        '<div class="vp-uploader-nopad vp-uploader-nopad-list">'+
                        	'<div class="vp-uploader-image vp-uploader-image-list"></div>'+
                            '<button class="thumbnail_uploader list_img btn btn-sm btn-info change-item-btn">'+
                                '<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
                                 vp_lang.change_photo +
								 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
                            '</button>&nbsp;'+
                            '<button class="thumbnail_remove list_img btn btn-sm btn-danger">'+
                                '<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'+
                                 vp_lang.remove +
                            '</button>&nbsp;'+
							'<span class="list_exc_1"><button class="add_meme_gen btn btn-sm btn-success">'+
                                '<i class="glyphicon glyphicon-tint"></i>&nbsp;&nbsp;'+
                                 vp_lang.gen_meme +
                            '</button>&nbsp;</span>'+
							'<button class="btn btn-sm btn-primary more_details_btn"><i class="glyphicon glyphicon-plus"></i>&nbsp;'+vp_lang.more_details+'</button><button class="btn btn-sm btn-primary hide_details_btn" style="display:none"><i class="glyphicon glyphicon-minus"></i>&nbsp;'+vp_lang.hide_details+'</button>'+
                        '</div>'+
					'</div>'+
				'</div>'+
				'<div class="row more_details" style="display:none">'+	
					'<div class="col-lg-12">'+
						'<label for="entry_text[[f_number]]">'+ vp_lang.title +'</label>'+
						'<input type="text" name="entry_text[[f_number]]" class="entry_text vp-form-control" placeholder="'+vp_lang.type_title+ ' ' + vp_lang.optional +'"/>'+
						'<label for="entry_desc[[f_number]]">'+ vp_lang.desc +'</label>'+
						'<div class="wp-editor-tabs">'+
							'<img class="pull-right toggle_tinymce vp-pointer" src="'+vp_img_dir_url+'/scode.png" width="25px" title="'+vp_lang.toggle_source+'"/>'+
						'</div>'+
						'<textarea id="tareaid" class="entry_desc tinymce vp-form-control" '+
						'name="entry_desc[[f_number]]" placeholder="'+vp_lang.type_desc+ ' ' + vp_lang.optional +'"></textarea>'+
						'<label for="entry_source[[f_number]]">'+ vp_lang.source +'</label>'+
						'<input type="text" name="entry_source[[f_number]]" class="entry_source vp-form-control" placeholder="'+vp_lang.type_source+ ' ' + vp_lang.optional +'"/>'+
						'<input type="hidden" name="entry_type[[f_number]]" value="list" />'+
						'<input type="hidden" name="entry_post_id[[f_number]]" class="entry_post_id" value="" />'+
						'<input type="hidden" name="entry_order[[f_number]]" value="[number_c]" class="entry-no-val"/>'+
						'<!--show_num_hold-->'+
					'</div>'+
				'</div>'+
				'<div class="row show_num_hold">'+
					'<div class="col-lg-12">'+
					'<div class="vp-clearfix"></div>'+
					'<input type="checkbox" name="entry_show_num[[f_number]]" value="1" class="entry-show-num" checked="checked"/>&nbsp;&nbsp;'+ 
					'<span class="show_numering">'+ vp_lang.show_numering +'</span>'+
					'</div>'+
				'</div>'+
			'</fieldset>'+
		'</div>';
		
		var quiz_poll_entry_template = 
		'<div class="more_items more_items_numbered quiz_parent" data-quiz-rel="[f_number]">'+
			'<input type="hidden" name="entry_type[[f_number]]" value="quiz" />'+
			'<input type="hidden" name="entry_order[[f_number]]" value="[number_c]" class="entry-no-val" />'+
			'<input type="hidden" name="entry_post_id[[f_number]]" class="entry_post_id" value="" />'+
			'<fieldset>'+
				'<!--title-->'+
				'<div class="vp-pull-right vp-pointer">'+
					'<span class="entry-no">[number]</span>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-arrow-up entry_move_up" title="'+ vp_lang.entry_move_up +'"></i>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-arrow-down entry_move_down" title="'+ vp_lang.entry_move_down +'"></i>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-trash remove_entry" title="'+ vp_lang.remove_entry +'"></i>'+
				'</div>'+
				'<div class="vp-clearfix"></div>'+
				'<div class="row">'+
					'<div class="col-lg-12">'+
						'<label for="entry_text[[f_number]]">'+ vp_lang.question +'</label>'+
						'<input type="text" name="entry_text[[f_number]]" class="entry_text vp-form-control" placeholder="'+vp_lang.type_qu+'"/><br/><br/>'+
						'<div class="row">'+
							'<div class="col-lg-5">'+	
								'<div class="vp-uploader vp-uploader-ques text-center" data-target="entry_images[[f_number]]">'+
									'<button class="thumbnail_uploader ques_img btn btn-sm btn-danger">'+
										'<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
										 vp_lang.upload_photo +
										 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
									'</button>'+									
									'<h4 class="big_or">'+vp_lang.big_or+'</h4>'+
									'<span class="thumbnail_uploader ques_img thumbnail_uploader_url vp-pointer" style="font-size:15px">'+
										'<i class="glyphicon glyphicon-link"></i>&nbsp;'+vp_lang.upload_from_link+
									'</span>'+
								'</div>'+
								'<div class="vp-uploader-nopad vp-uploader-nopad-ques">'+
									'<div class="vp-uploader-image vp-uploader-image-ques"></div>'+
									'<button class="thumbnail_uploader ques_img btn btn-sm btn-info change-item-btn">'+
										'<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
										 vp_lang.change_photo +
										 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
									'</button>&nbsp;'+
									'<button class="thumbnail_remove ques_img btn btn-sm btn-danger">'+
										'<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'+
										 vp_lang.remove +
									'</button>'+
								'</div>'+
							'</div>'+
							'<div class="col-lg-7">'+
								'<label for="entry_desc[[f_number]]">'+ vp_lang.desc +'</label>'+
								'<div class="wp-editor-tabs">'+
									'<img class="pull-right toggle_tinymce vp-pointer" src="'+vp_img_dir_url+'/scode.png" width="25px" title="'+vp_lang.toggle_source+'"/>'+
								'</div>'+
								'<textarea id="tareaid" class="entry_desc tinymce vp-form-control" '+
								'name="entry_desc[[f_number]]" placeholder="'+vp_lang.type_desc+ ' ' + vp_lang.optional +'"></textarea>'+
								'<label for="entry_source[[f_number]]">'+ vp_lang.source +'</label>'+
								'<input type="text" name="entry_source[[f_number]]" class="entry_source vp-form-control" placeholder="'+vp_lang.type_source+ ' ' + vp_lang.optional +'"/>'+
							'</div>'+
						'</div>'+
					'</div>'+	
				'</div>'+
				'<hr/>'+
				'<div class="row">'+
					'<div class="col-lg-12">'+
						'<label>'+vp_lang.possible_answers+'</label><br/>'+
					'</div>'+
				'</div>'+
				'<div class="quiz_answer_entry">'+
				'</div>'+
				'<div class="row">'+
					'<div class="col-lg-12">'+
						'<br/><button class="btn btn-sm btn-info add_more_ans"><i class="glyphicon glyphicon-plus"></i>&nbsp;'+vp_lang.add_answer+'</button>&nbsp;&nbsp;'+
						'<button class="btn btn-sm btn-danger show_explain_ans"><i class="glyphicon glyphicon-adjust"></i>&nbsp;'+vp_lang.explain_answer+'</button>'+
						'<br/><br/>'+
					'</div>'+
				'</div>'+
				'<div class="vp-clearfix"></div>'+
				'<div class="row quiz_ans_exp" style="display:none">'+
					'<div class="col-lg-12">'+
						'<label for="explain_text[[f_number]]">'+ vp_lang.title_of_exp +'</label>'+
						'<input type="text" name="explain_text[[f_number]]" class="explain_text vp-form-control" placeholder="'+vp_lang.title_of_exp+'"/><br/><br/>'+
						'<div class="row">'+
							'<div class="col-lg-5">'+	
								'<div class="vp-uploader vp-uploader-exp text-center" data-target="explain_images[[f_number]]">'+
									'<button class="thumbnail_uploader exp_img btn btn-sm btn-danger">'+
										'<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
										 vp_lang.upload_photo +
										 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
									'</button>'+									
									'<h4 class="big_or">'+vp_lang.big_or+'</h4>'+
									'<span class="thumbnail_uploader exp_img thumbnail_uploader_url vp-pointer" style="font-size:15px">'+
										'<i class="glyphicon glyphicon-link"></i>&nbsp;'+vp_lang.upload_from_link+
									'</span>'+
								'</div>'+
								'<div class="vp-uploader-nopad vp-uploader-nopad-exp">'+
									'<div class="vp-uploader-image exp_img vp-uploader-image-exp"></div>'+
									'<button class="thumbnail_uploader btn btn-sm btn-info change-item-btn">'+
										'<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
										 vp_lang.change_photo +
										 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
									'</button>&nbsp;'+
									'<button class="thumbnail_remove exp_img btn btn-sm btn-danger">'+
										'<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'+
										 vp_lang.remove +
									'</button>'+
								'</div>'+
							'</div>'+
							'<div class="col-lg-7">'+
								'<label for="explain_desc[[f_number]]">'+ vp_lang.desc_of_exp + ' ' + vp_lang.optional +'</label>'+
								'<textarea id="explain_tareaid" class="explain_desc tinymce vp-form-control" '+
								'name="explain_desc[[f_number]]" placeholder="'+vp_lang.desc_of_exp+ ' ' + vp_lang.optional +'"></textarea>'+
							'</div>'+
						'</div>'+
					'</div>'+	
				'</div>'+
				'<div class="row">'+
					'<div class="col-lg-12">'+
						'<div class="vp-clearfix"></div>'+
						'<input type="checkbox" name="entry_show_num[[f_number]]" value="1" class="entry-show-num" checked="checked"/>&nbsp;&nbsp;'+
						'<span class="show_numering">'+ vp_lang.show_numering +'</span>'+
					'</div>'+
				'</div>'+
			'</fieldset>'+
		'</div>';
		
		var quiz_answer_entry_template = 
		'<div class="col-lg-4 more_items_x">'+
			'<div class="more_ans">'+
				'<div class="vp-uploader vp-uploader-ans vp-uploader-lesspad-2 text-center" data-target="quiz_images[[f_number]][[f_number_1]]">'+
					'<button class="thumbnail_uploader ans_img btn btn-sm btn-danger">'+
						'<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
						 vp_lang.upload_photo +
						 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
					'</button>'+
					'<h4 class="big_or">'+vp_lang.big_or+'</h4>'+
					'<span class="thumbnail_uploader ans_img thumbnail_uploader_url vp-pointer" style="font-size:15px">'+
						'<i class="glyphicon glyphicon-link"></i>&nbsp;'+vp_lang.upload_from_link+
					'</span>'+
				'</div>'+
				'<div class="vp-uploader-nopad vp-uploader-nopad-ans vp-uploader-answer">'+
					'<div class="vp-uploader-image vp-uploader-image-ans vp-uploader-lesspad-2-img"></div>'+
					'<button class="thumbnail_uploader ans_img btn btn-sm btn-info change-item-btn">'+
						'<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
						 vp_lang.change_photo +
						 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
					'</button>&nbsp;'+
					'<button class="thumbnail_remove ans_img btn btn-sm btn-danger">'+
						'<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'+
						 vp_lang.remove +
					'</button>'+
				'</div>'+
				'<div class="vp-pull-right vp-pointer">'+
					'<i class="glyphicon glyphicon-trash remove_ans_entry" title="'+ vp_lang.remove_entry +'"></i>'+
				'</div>'+
				'<label for="quiz_answer[[f_number]][[f_number_1]]">'+ vp_lang.answer +'&nbsp;-&nbsp;<span class="entry-no">[number_1]</span></label>'+
				'<input type="text" name="quiz_answer[[f_number]][[f_number_1]]" class="entry_text vp-form-control" placeholder="'+vp_lang.type_ans+ '"/>'+
				'<div class="for_personality_quiz" style="display:none">'+
					'<label for="quiz_answer_result_p1[[f_number]][[f_number_1]]">'+ vp_lang.result +'&nbsp;-&nbsp;<span class="entry-no">[number_1]</span></label>'+
					'<select name="quiz_answer_result_p1[[f_number]][[f_number_1]]" class="personality_res_ans vp-form-control">'+
						'<option value="">'+vp_lang.select_one+'</option>'+
					'</select>'+
				'</div>'+
			'</div>'+
			//'<input type="hidden" name="quiz_ans_order[[f_number]][[f_number_1]]" value="[number_1]" class="entry-no-val"/>'+
			'<input type="hidden" name="entry_ans_post_id[[f_number]][[f_number_1]]" class="entry_ans_post_id" value="" />'+
		'</div>';
		
		var vp_gallery_entry_template = 
		'<div class="more_items more_items_numbered">'+
			'<fieldset>'+
				'<legend>'+vp_lang.gallery+'</legend>'+
				'<div class="vp-pull-right vp-pointer">'+
					'<span class="entry-no">[number]</span>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-arrow-up entry_move_up" title="'+ vp_lang.entry_move_up +'"></i>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-arrow-down entry_move_down" title="'+ vp_lang.entry_move_down +'"></i>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-trash remove_entry" title="'+ vp_lang.remove_entry +'"></i>'+
				'</div>'+
				'<div class="vp-clearfix"></div>'+
				'<div class="row">'+
					'<div class="col-lg-12">'+
						'<div class="vp-uploader vp-uploader-list vp-uploader-lesspad text-center" data-target="entry_images[[f_number]]">'+
                            '<button class="gallery_uploader btn btn-sm btn-danger">'+
                                '<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
                                 vp_lang.sel_img +
								 '</span>'+
                            '</button>'+
                        '</div>'+
                        '<div class="vp-uploader-nopad vp-uploader-nopad-list">'+
                        	'<div class="vp-uploader-image vp-uploader-image-list"></div>'+
							'<div class="vp-clearfix"></div>'+
							'<div class="vp-clearfix"></div>'+
                            '<button class="gallery_uploader btn btn-sm btn-info change-item-btn">'+
                                '<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
                                 vp_lang.add_more_photo +
								 '</span>'+
                            '</button>&nbsp;&nbsp;<button class="btn btn-sm btn-primary more_details_btn"><i class="glyphicon glyphicon-plus"></i>&nbsp;'+vp_lang.more_details+'</button><button class="btn btn-sm btn-primary hide_details_btn" style="display:none"><i class="glyphicon glyphicon-minus"></i>&nbsp;'+vp_lang.hide_details+'</button>'+
                        '</div>'+
					'</div>'+
				'</div>'+
				'<div class="row more_details" style="display:none">'+
					'<div class="col-lg-12">'+
						'<div class="vp-clearfix"></div>'+
						'<div class="vp-clearfix"></div>'+
						'<div class="gal_more_details">'+
							'<label>'+vp_lang.gal_type+'</label>&nbsp;&nbsp;'+
							'<select name="gal_type[[f_number]]" class="gal_type">'+
								'<option value="rectangular">'+vp_lang.rectangular+'</option>'+
								'<option value="thumbnail">'+vp_lang.thumbnail+'</option>'+
								'<option value="square">'+vp_lang.square+'</option>'+
								'<option value="circle">'+vp_lang.circle+'</option>'+
								'<option value="columns">'+vp_lang.columns+'</option>'+
								'<option value="slideshow">'+vp_lang.slideshow+'</option>'+
							'</select>&nbsp;&nbsp;'+
							'<label>'+vp_lang.gal_cols+'</label>&nbsp;&nbsp;'+
							'<select name="gal_cols[[f_number]]" class="gal_col">'+
								'<option value="1">1</option>'+
								'<option value="2">2</option>'+
								'<option value="3" selected>3</option>'+
								'<option value="4">4</option>'+
								'<option value="5">5</option>'+
								'<option value="6">6</option>'+
								'<option value="7">7</option>'+
							'</select>&nbsp;&nbsp;'+
							'<label>'+vp_lang.gal_autostart+'</label>&nbsp;&nbsp;'+
							'<select name="gal_autostart[[f_number]]" class="gal_autostart">'+
								'<option value="0">'+vp_lang.no+'</option>'+
								'<option value="1">'+vp_lang.yes+'</option>'+
							'</select>&nbsp;&nbsp;'+
							'<div class="vp-clearfix"></div>'+
						'</div>'+
						'<label for="entry_text[[f_number]]">'+ vp_lang.title +'</label>'+
						'<input type="text" name="entry_text[[f_number]]" class="entry_text vp-form-control" placeholder="'+vp_lang.type_title+ ' ' + vp_lang.optional +'"/>'+
						'<label for="entry_desc[[f_number]]">'+ vp_lang.desc +'</label>'+
						'<div class="wp-editor-tabs">'+
							'<img class="pull-right toggle_tinymce vp-pointer" src="'+vp_img_dir_url+'/scode.png" width="25px" title="'+vp_lang.toggle_source+'"/>'+
						'</div>'+
						'<textarea id="tareaid" class="entry_desc tinymce vp-form-control" '+
						'name="entry_desc[[f_number]]" placeholder="'+vp_lang.type_desc+ ' ' + vp_lang.optional +'"></textarea>'+
						'<label for="entry_source[[f_number]]">'+ vp_lang.source +'</label>'+
						'<input type="text" name="entry_source[[f_number]]" class="entry_source vp-form-control" placeholder="'+vp_lang.type_source+ ' ' + vp_lang.optional +'"/>'+
						'<input type="hidden" name="entry_type[[f_number]]" value="gallery" />'+
						'<input type="hidden" name="entry_post_id[[f_number]]" class="entry_post_id" value="" />'+
						'<input type="hidden" name="entry_order[[f_number]]" value="[number_c]" class="entry-no-val"/>'+
						'<!--show_num_hold-->'+
					'</div>'+
				'</div>'+
				'<div class="row show_num_hold">'+
					'<div class="col-lg-12">'+
					'<div class="vp-clearfix"></div>'+
					'<input type="checkbox" name="entry_show_num[[f_number]]" value="1" class="entry-show-num" checked="checked"/>&nbsp;&nbsp;'+ 
					'<span class="show_numering">'+ vp_lang.show_numering +'</span>'+
					'</div>'+
				'</div>'+
			'</fieldset>'+
		'</div>';
		
		var vp_old_list_entry_template = 
		'<div class="more_items more_items_numbered">'+
			'<fieldset>'+
				'<!--title-->'+
				'<div class="vp-pull-right vp-pointer">'+
					'<span class="entry-no">[number]</span>&nbsp;&nbsp;'+
					'<i class="glyphicon glyphicon-trash remove_entry" title="'+ vp_lang.remove_entry +'"></i>'+
				'</div>'+
				'<div class="vp-clearfix"></div>'+
				'<div class="row">'+
					'<div class="col-lg-5">'+
						'<div class="vp-uploader vp-uploader-list vp-uploader-lesspad text-center" data-target="entry_images[[f_number]]">'+
                            '<button class="thumbnail_uploader list_img btn btn-sm btn-danger">'+
                                '<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
                                 vp_lang.upload_photo +
								 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
                            '</button>'+
							'<span class="list_exclusive">'+
								'<h4 class="big_or">'+vp_lang.big_or+'</h4>'+
								'<span class="thumbnail_uploader list_img thumbnail_uploader_url vp-pointer" style="font-size:15px">'+
									'<i class="glyphicon glyphicon-link"></i>&nbsp;<small>'+vp_lang.upload_from_link+'</small>'+
								'</span>'+
							'</span>'+
                        '</div>'+
                        '<div class="vp-uploader-nopad vp-uploader-nopad-list">'+
                        	'<div class="vp-uploader-image vp-uploader-image-list"></div>'+
                            '<button class="thumbnail_uploader list_img btn btn-sm btn-info change-item-btn">'+
                                '<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
                                 vp_lang.change_photo +
								 '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
                            '</button>&nbsp;'+
                            '<button class="thumbnail_remove list_img btn btn-sm btn-danger">'+
                                '<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'+
                                 vp_lang.remove +
                            '</button><!--more_buttons-->'+
                        '</div>'+
					'</div>'+
					'<div class="col-lg-7">'+
						'<label for="entry_text[[f_number]]">'+ vp_lang.title +'</label>'+
						'<input type="text" name="entry_text[[f_number]]" class="entry_text vp-form-control" placeholder="'+vp_lang.type_title+ ' ' + vp_lang.optional +'"/>'+
						'<label for="entry_desc[[f_number]]">'+ vp_lang.desc +'</label>'+
						'<div class="wp-editor-tabs">'+
							'<img class="pull-right toggle_tinymce vp-pointer" src="'+vp_img_dir_url+'/scode.png" width="25px" title="'+vp_lang.toggle_source+'"/>'+
						'</div>'+
						'<textarea id="tareaid" class="entry_desc tinymce vp-form-control" '+
						'name="entry_desc[[f_number]]" placeholder="'+vp_lang.type_desc+ ' ' + vp_lang.optional +'"></textarea>'+
						'<label for="entry_source[[f_number]]">'+ vp_lang.source +'</label>'+
						'<input type="text" name="entry_source[[f_number]]" class="entry_source vp-form-control" placeholder="'+vp_lang.type_source+ ' ' + vp_lang.optional +'"/>'+
						'<input type="hidden" name="entry_type[[f_number]]" value="list" />'+
						'<input type="hidden" name="entry_post_id[[f_number]]" class="entry_post_id" value="" />'+
						'<input type="hidden" name="entry_order[[f_number]]" value="[number_c]" class="entry-no-val"/>'+
						'<!--show_num_hold-->'+
					'</div>'+
				'</div>'+
				'<div class="row show_num_hold">'+
					'<div class="col-lg-12">'+
					'<div class="vp-clearfix"></div>'+
					'<input type="checkbox" name="entry_show_num[[f_number]]" value="1" class="entry-show-num" checked="checked"/>&nbsp;&nbsp;'+ 
					'<span class="show_numering">'+ vp_lang.show_numering +'</span>'+
					'</div>'+
				'</div>'+
			'</fieldset>'+
		'</div>';
		
		if( vp_meme_enabled == 0 )vp_list_entry_template = vp_list_entry_template.replace(/<span class="list_exc_1">([\s\S]*?)<\/span>/g, '');
		
		
		var vp_playlist_entry_template = vp_gallery_entry_template;
		vp_playlist_entry_template = vp_playlist_entry_template.replace(vp_lang.gallery, vp_lang.playlist);
		vp_playlist_entry_template = vp_playlist_entry_template.replace(vp_lang.sel_img, vp_lang.sel_playlist);
		vp_playlist_entry_template = vp_playlist_entry_template.replace(/<div class="gal_more_details">([\s\S]*?)<label for="entry_text/g, '<label for="entry_text');
		vp_playlist_entry_template = vp_playlist_entry_template.replace('entry_images', 'entry_media');
		vp_playlist_entry_template = vp_playlist_entry_template.replace(/gallery_uploader/g, 'playlist_uploader');
		vp_playlist_entry_template = vp_playlist_entry_template.replace('value="gallery"', 'value="playlist"');
		vp_playlist_entry_template = vp_playlist_entry_template.replace(vp_lang.add_more_photo, vp_lang.add_more_media);
		
		
		var num_code = jQuery(vp_list_entry_template).find('.show_num_hold .col-lg-12').html();
		
		var quiz_entry_template = quiz_poll_entry_template.replace(/\<\!--title--\>/g, '<legend>' + vp_lang.quiz + '</legend>' + vp_lang.correct_answer+':&nbsp;<select id="correct_answer[[f_number]]" class="correct_answer" name="correct_answer[[f_number]]"></select>');
		
		var quiz_result_entry_template = vp_old_list_entry_template;
		quiz_result_entry_template = quiz_result_entry_template.replace(/entry_text vp-form-control/g, 'entry_text vp-form-control vp_ans_title_text');
		quiz_result_entry_template = quiz_result_entry_template.replace(/remove_entry/g, 'remove_entry quiz_results');
		quiz_result_entry_template = quiz_result_entry_template.replace(/<div class="row show_num_hold">([\s\S]*?)<\/fieldset>/g, '</fieldset>');
		quiz_result_entry_template = quiz_result_entry_template.replace(/<span class="list_exc_1">([\s\S]*?)<\/span>/g, '');
		quiz_result_entry_template = quiz_result_entry_template.replace(/value="list"/g, 'value="result"');
		quiz_result_entry_template = quiz_result_entry_template.replace(/value="\[number\]"/g, 'value="[x_number]"');
		quiz_result_entry_template = quiz_result_entry_template.replace(/more_items/g, 'more_items_x');
		quiz_result_entry_template = quiz_result_entry_template.replace(/more_items_numbered/g, '');
		quiz_result_entry_template = quiz_result_entry_template.replace(/\<\!--title--\>/g, '<legend>' + vp_lang.results + '</legend>' +vp_lang.scoring+':&nbsp;<select id="quiz_res_from_[f_number]" class="quiz_res_from_score" name="quiz_score_from[[f_number]]"></select>&nbsp;'+ vp_lang.to +'&nbsp;<select id="quiz_res_to_[f_number]" class="quiz_res_to_score" name="quiz_score_to[[f_number]]"></select><input type="hidden" name="quiz_score_sheet[[f_number]]" value="1"/>');
	
		var vp_poll_entry_template = quiz_poll_entry_template.replace(/value="quiz"/, 'value="poll"');
		vp_poll_entry_template = vp_poll_entry_template.replace(/\<\!--title--\>/g, '<legend>' + vp_lang.poll + '</legend>' );
		vp_poll_entry_template = vp_poll_entry_template.replace(/show_explain_ans\"/g, 'show_explain_ans" style="display:none"' );

		
		var vp_video_entry_template = vp_list_entry_template;
		vp_video_entry_template = vp_video_entry_template.replace(/<span class="list_exc_1">([\s\S]*?)<\/span>/g, '');
		vp_video_entry_template = vp_video_entry_template.replace(/<span class="list_exclusive">([\s\S]*?)<\/span>/g, '');
		vp_video_entry_template = vp_video_entry_template.replace(/vp-uploader-lesspad/g, 'vp-uploader-lesspad-3');
		vp_video_entry_template = vp_video_entry_template.replace(vp_lang.change_photo, vp_lang.change_video);
		vp_video_entry_template = vp_video_entry_template.replace(vp_lang.upload_photo, vp_lang.add_video);
		vp_video_entry_template = vp_video_entry_template.replace(/<!--title-->/g, '<legend>'+vp_lang.video+'</legend>');
		vp_video_entry_template = vp_video_entry_template.replace(/value="list"/g, 'value="video"');
		vp_video_entry_template = vp_video_entry_template.replace('glyphicon-picture', 'glyphicon-film');
		vp_video_entry_template = vp_video_entry_template.replace('entry_images', 'entry_videos');
		vp_video_entry_template = vp_video_entry_template.replace(/list_img/g, 'list_video');
		vp_video_entry_template = vp_video_entry_template.replace(/-list/g, '-video');
		vp_video_entry_template = vp_video_entry_template.replace(/list-/g, 'video-');
		
		var vp_audio_entry_template = vp_video_entry_template;
		vp_audio_entry_template = vp_audio_entry_template.replace(vp_lang.change_video, vp_lang.change_audio);
		vp_audio_entry_template = vp_audio_entry_template.replace(vp_lang.add_video, vp_lang.add_audio);
		vp_audio_entry_template = vp_audio_entry_template.replace(vp_lang.video, vp_lang.audio);
		vp_audio_entry_template = vp_audio_entry_template.replace(/value="video"/g, 'value="audio"');
		vp_audio_entry_template = vp_audio_entry_template.replace('glyphicon-film', 'glyphicon-sound-stereo');
		vp_audio_entry_template = vp_audio_entry_template.replace('entry_videos', 'entry_audio');
		vp_audio_entry_template = vp_audio_entry_template.replace(/list_video/g, 'list_audio');
		vp_audio_entry_template = vp_audio_entry_template.replace(/video/g, 'audio');
		vp_audio_entry_template = vp_audio_entry_template.replace(/-video/g, '-audio');
		vp_audio_entry_template = vp_audio_entry_template.replace(/video-/g, 'audio-');
		
		var vp_wide_template = vp_list_entry_template;
		vp_wide_template = vp_wide_template.replace(/<span class="list_exclusive">([\s\S]*?)<\/span>/g, '');
		vp_wide_template = vp_wide_template.replace(/<span class="list_exc_1">([\s\S]*?)<\/span>/g, '');
		vp_wide_template = vp_wide_template.replace(vp_lang.change_photo, vp_lang.change_video);
		vp_wide_template = vp_wide_template.replace(vp_lang.upload_photo, vp_lang.add_video);
		vp_wide_template = vp_wide_template.replace(/<!--title-->/g, '<legend>'+vp_lang.video+'</legend>');
		vp_wide_template = vp_wide_template.replace(/value="list"/g, 'value="video"');
		vp_wide_template = vp_wide_template.replace('glyphicon-picture', 'glyphicon-film');
		vp_wide_template = vp_wide_template.replace('entry_images', 'entry_videos');
		vp_wide_template = vp_wide_template.replace(/thumbnail_uploader/g, 'add_video');
		vp_wide_template = vp_wide_template.replace(/<div class="col-lg-5">/g, '<div class="col-lg-12">');
		vp_wide_template = vp_wide_template.replace(/<div class="col-lg-7">/g, '</div><div class="row more_details" style="display:none"><div class="col-lg-12"><br/>');
		vp_wide_template = vp_wide_template.replace(/class="vp-uploader text-center"/g, 'class="vp-uploader vp-uploader-lesspad text-center"');
		vp_wide_template = vp_wide_template.replace(/<\!--more_buttons-->/g, '&nbsp;<button class="btn btn-sm btn-primary more_details_btn"><i class="glyphicon glyphicon-plus"></i>&nbsp;'+vp_lang.more_details+'</button><button class="btn btn-sm btn-primary hide_details_btn" style="display:none"><i class="glyphicon glyphicon-minus"></i>&nbsp;'+vp_lang.hide_details+'</button>');
	
		
		var vp_pin_entry_template = vp_wide_template;
		vp_pin_entry_template = vp_pin_entry_template.replace(vp_lang.change_video, vp_lang.change_pin);
		vp_pin_entry_template = vp_pin_entry_template.replace(vp_lang.add_video, vp_lang.add_pin);
		vp_pin_entry_template = vp_pin_entry_template.replace(vp_lang.video, vp_lang.pin);
		vp_pin_entry_template = vp_pin_entry_template.replace(/value="video"/g, 'value="pin"');
		vp_pin_entry_template = vp_pin_entry_template.replace('glyphicon-film', 'glyphicon-calendar');
		vp_pin_entry_template = vp_pin_entry_template.replace('entry_videos', 'entry_pins');
		vp_pin_entry_template = vp_pin_entry_template.replace(/add_video/g, 'add_pin');
		
		vp_list_entry_template = vp_list_entry_template.replace(/\<\!--title--\>/g, '<legend>' + vp_lang.image + '</legend>' );
		
		/***
		 * open list templates
		 */
		var vp_openlist_list_template = 
		'<div class="more_items">'+
            '<div class="vp-uploader vp-uploader-list vp-uploader-lesspad text-center" data-target="open_list_input">'+
                '<button class="thumbnail_uploader list_img btn btn-sm btn-danger">'+
                    '<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
                     vp_lang.upload_photo+
                     '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
                '</button>'+
                '<span class="list_exclusive">'+
                    '<h4 class="big_or">'+vp_lang.big_or+'</h4>'+
                    '<span class="thumbnail_uploader list_img thumbnail_uploader_url vp-pointer" style="font-size:15px">'+
                        '<i class="glyphicon glyphicon-link"></i>&nbsp;'+vp_lang.upload_from_link+
                    '</span>'+
                '</span>'+
            '</div>'+
            '<div class="vp-uploader-nopad vp-uploader-nopad-list">'+
                '<div class="vp-uploader-image vp-uploader-image-list"></div>'+
                '<button class="thumbnail_uploader list_img btn btn-sm btn-info change-item-btn">'+
                    '<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
                    vp_lang.change_photo+
                     '</span><span class="dload_text" style="display:none">'+vp_lang.downloading+'</span>'+
                '</button>&nbsp;'+
                '<button class="thumbnail_remove list_img btn btn-sm btn-danger">'+
                    '<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'+
                     vp_lang.remove+
                '</button>&nbsp;'+
				'<span class="list_exc_1"><button class="add_meme_gen btn btn-sm btn-success">'+
					'<i class="glyphicon glyphicon-tint"></i>&nbsp;&nbsp;'+
					 vp_lang.gen_meme +
				'</button>&nbsp;</span>'+
				'<button class="btn btn-sm btn-primary more_details_btn"><i class="glyphicon glyphicon-plus"></i>&nbsp;'+vp_lang.more_details+'</button><button class="btn btn-sm btn-primary hide_details_btn" style="display:none"><i class="glyphicon glyphicon-minus"></i>&nbsp;'+vp_lang.hide_details+'</button>'+
            '</div>'+
            '<div class="vp-clearfix"></div>'+
            '<div class="open_list_desc_more more_details" style="display:none">'+
                '<label>'+vp_lang.title+'</label>'+
                '<input type="text" class="vp-form-control" name="open_list_title" id="open_list_title" placeholder="'+vp_lang.type_title+'">'+
                '<label>'+vp_lang.desc+'</label>'+
                '<textarea name="open_list_desc" id="open_list_desc" class="tinymce"></textarea>'+  
                '<label for="open_list_source">'+ vp_lang.source +'</label>'+
				'<input type="text" name="open_list_source" class="open_list_source vp-form-control" placeholder="'+vp_lang.type_source+ ' ' + vp_lang.optional +'"/>'+
				//'<br/><br/><input type="checkbox" name="open_list_num" value="1" checked/>&nbsp;&nbsp;'+vp_lang.show_numering+
				'<br/><br/>'+
                '<button class="btn btn-info submit_open_list">'+vp_lang.submit+'</button>'+          
            '</div>'+
        '</div>'+
		'<input type="hidden" name="open_list_entry_type" value="list" />'; 
		
		var vp_openlist_news_template = 
		'<div class="more_items">'+
            '<div class="open_list_desc_more">'+
                '<label>'+vp_lang.title+'</label>'+
                '<input type="text" class="vp-form-control" name="open_list_title" id="open_list_title" placeholder="'+vp_lang.type_title+'">'+
                '<label>'+vp_lang.desc+'</label>'+
                '<textarea name="open_list_desc" id="open_list_desc" class="tinymce"></textarea>'+ 
				'<label for="open_list_source">'+ vp_lang.source +'</label>'+
				'<input type="text" name="open_list_source" class="open_list_source vp-form-control" placeholder="'+vp_lang.type_source+ ' ' + vp_lang.optional +'"/>'+
				//'<br/><br/><input type="checkbox" name="open_list_num" value="1"  checked/>&nbsp;&nbsp;'+vp_lang.show_numering+ 
                '<br/><br/>'+
                '<button class="btn btn-info submit_open_list">'+vp_lang.submit+'</button>'+          
            '</div>'+
			'<input type="hidden" name="open_list_entry_type" value="news" />'+
        '</div>'; 
		
		var vp_openlist_gallery_template = 
		'<div class="more_items">'+				
			'<div class="vp-uploader vp-uploader-list vp-uploader-lesspad text-center" data-target="open_list_input">'+
				'<button class="gallery_uploader gl-small btn btn-sm btn-danger">'+
					'<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
					 vp_lang.sel_img +
					 '</span>'+
				'</button>'+
			'</div>'+
			'<div class="vp-uploader-nopad vp-uploader-nopad-list">'+
				'<div class="vp-uploader-image vp-uploader-image-list"></div>'+
				'<div class="vp-clearfix"></div>'+
				'<div class="vp-clearfix"></div>'+
				'<button class="gallery_uploader gl-small btn btn-sm btn-info change-item-btn">'+
					'<i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<span class="default_text">'+
					 vp_lang.add_more_photo +
					 '</span>'+
				'</button>&nbsp;&nbsp;<button class="btn btn-sm btn-primary more_details_btn"><i class="glyphicon glyphicon-plus"></i>&nbsp;'+vp_lang.more_details+'</button><button class="btn btn-sm btn-primary hide_details_btn" style="display:none"><i class="glyphicon glyphicon-minus"></i>&nbsp;'+vp_lang.hide_details+'</button>'+
			'</div>'+
			'<div class="vp-clearfix"></div>'+
            '<div class="open_list_desc_more more_details" style="display:none">'+
				'<div class="vp-clearfix"></div>'+
				'<div class="vp-clearfix"></div>'+
				'<div class="gal_more_details">'+
					'<label>'+vp_lang.gal_type+'</label>&nbsp;&nbsp;'+
					'<select name="gal_type">'+
						'<option value="rectangular">'+vp_lang.rectangular+'</option>'+
						'<option value="thumbnail">'+vp_lang.thumbnail+'</option>'+
						'<option value="square">'+vp_lang.square+'</option>'+
						'<option value="circle">'+vp_lang.circle+'</option>'+
						'<option value="slideshow">'+vp_lang.slideshow+'</option>'+
					'</select>&nbsp;&nbsp;'+
					'<label>'+vp_lang.gal_cols+'</label>&nbsp;&nbsp;'+
					'<select name="gal_cols">'+
						'<option value="1">1</option>'+
						'<option value="2">2</option>'+
						'<option value="3" selected>3</option>'+
						'<option value="4">4</option>'+
						'<option value="5">5</option>'+
						'<option value="6">6</option>'+
						'<option value="7">7</option>'+
					'</select>&nbsp;&nbsp;'+
					'<label>'+vp_lang.gal_autostart+'</label>&nbsp;&nbsp;'+
					'<select name="gal_autostart">'+
						'<option value="0">'+vp_lang.no+'</option>'+
						'<option value="1">'+vp_lang.yes+'</option>'+
					'</select>&nbsp;&nbsp;'+
					'<div class="vp-clearfix"></div>'+
                '</div>'+
				'<label for="open_list_title">'+vp_lang.title+'</label>'+
                '<input type="text" class="vp-form-control" name="open_list_title" id="open_list_title" placeholder="'+vp_lang.type_title+'">'+
                '<label>'+vp_lang.desc+'</label>'+
                '<textarea name="open_list_desc" id="open_list_desc" class="tinymce"></textarea>'+  
                '<label for="open_list_source">'+ vp_lang.source +'</label>'+
				'<input type="text" name="open_list_source" class="open_list_source vp-form-control" placeholder="'+vp_lang.type_source+ ' ' + vp_lang.optional +'"/>'+
				//'<br/><br/><input type="checkbox" name="open_list_num" value="1" checked/>&nbsp;&nbsp;'+vp_lang.show_numering+
				'<br/><br/>'+
                '<button class="btn btn-info submit_open_list">'+vp_lang.submit+'</button>'+          
            '</div>'+
			'<input type="hidden" name="open_list_entry_type" value="gallery" />'+
		'</div>';
		
		if( vp_meme_enabled == 0 )vp_openlist_list_template = vp_openlist_list_template.replace(/<span class="list_exc_1">([\s\S]*?)<\/span>/g, '');
		
		//var vp_openlist_meme_template = vp_openlist_list_template;
		//vp_openlist_meme_template = vp_openlist_meme_template.replace('', vp_lang.playlist);
		
		var vp_openlist_playlist_template = vp_openlist_gallery_template;
		vp_openlist_playlist_template = vp_openlist_playlist_template.replace(vp_lang.gallery, vp_lang.playlist);
		vp_openlist_playlist_template = vp_openlist_playlist_template.replace(vp_lang.sel_img, vp_lang.sel_playlist);
		vp_openlist_playlist_template = vp_openlist_playlist_template.replace(/<div class="gal_more_details">([\s\S]*?)<label for="open_list_title/g, '<label for="open_list_title');
		vp_openlist_playlist_template = vp_openlist_playlist_template.replace('entry_images', 'entry_media');
		vp_openlist_playlist_template = vp_openlist_playlist_template.replace('gallery_uploader', 'playlist_uploader');
		vp_openlist_playlist_template = vp_openlist_playlist_template.replace('value="gallery"', 'value="playlist"');
		vp_openlist_playlist_template = vp_openlist_playlist_template.replace(vp_lang.add_more_photo, vp_lang.add_more_media);
		
		var vp_openlist_video_template = vp_openlist_list_template;
		vp_openlist_video_template = vp_openlist_video_template.replace(/<span class="list_exc_1">([\s\S]*?)<\/span>/g, '');
		vp_openlist_video_template = vp_openlist_video_template.replace(/<span class="list_exclusive">([\s\S]*?)<\/span>/g, '');
		vp_openlist_video_template = vp_openlist_video_template.replace(vp_lang.change_photo, vp_lang.change_video);
		vp_openlist_video_template = vp_openlist_video_template.replace(vp_lang.upload_photo, vp_lang.add_video);
		vp_openlist_video_template = vp_openlist_video_template.replace(/<!--title-->/g, '<legend>'+vp_lang.video+'</legend>');
		vp_openlist_video_template = vp_openlist_video_template.replace(/value="list"/g, 'value="video"');
		vp_openlist_video_template = vp_openlist_video_template.replace('glyphicon-picture', 'glyphicon-film');
		vp_openlist_video_template = vp_openlist_video_template.replace('entry_images', 'entry_videos');
		vp_openlist_video_template = vp_openlist_video_template.replace(/list_img/g, 'list_video');
		vp_openlist_video_template = vp_openlist_video_template.replace(/list-/g, 'video-');
		vp_openlist_video_template = vp_openlist_video_template.replace(/-list/g, '-video');
		vp_openlist_video_template = vp_openlist_video_template.replace(/class="vp-uploader text-center"/g, 'class="vp-uploader vp-uploader-lesspad text-center"');
		
		var vp_openlist_audio_template = vp_openlist_video_template;
		vp_openlist_audio_template = vp_openlist_audio_template.replace(vp_lang.change_video, vp_lang.change_audio);
		vp_openlist_audio_template = vp_openlist_audio_template.replace(vp_lang.add_video, vp_lang.add_audio);
		vp_openlist_audio_template = vp_openlist_audio_template.replace(vp_lang.video, vp_lang.audio);
		vp_openlist_audio_template = vp_openlist_audio_template.replace(/value="video"/g, 'value="audio"');
		vp_openlist_audio_template = vp_openlist_audio_template.replace('glyphicon-film', 'glyphicon-sound-stereo');
		vp_openlist_audio_template = vp_openlist_audio_template.replace('entry_videos', 'entry_audio');
		vp_openlist_audio_template = vp_openlist_audio_template.replace(/add_video/g, 'add_audio');
		vp_openlist_audio_template = vp_openlist_audio_template.replace(/list_video/g, 'list_audio');
		vp_openlist_audio_template = vp_openlist_audio_template.replace(/video-/g, 'audio-');
		vp_openlist_audio_template = vp_openlist_audio_template.replace(/-video/g, '-audio');
		
		
		var vp_openlist_embed_template = vp_openlist_list_template;
		vp_openlist_embed_template = vp_openlist_embed_template.replace(/<span class="list_exc_1">([\s\S]*?)<\/span>/g, '');
		vp_openlist_embed_template = vp_openlist_embed_template.replace(/<span class="list_exclusive">([\s\S]*?)<\/span>/g, '');
		vp_openlist_embed_template = vp_openlist_embed_template.replace(vp_lang.change_photo, vp_lang.change_pin);
		vp_openlist_embed_template = vp_openlist_embed_template.replace(vp_lang.upload_photo, vp_lang.add_pin);
		vp_openlist_embed_template = vp_openlist_embed_template.replace(/<!--title-->/g, '<legend>'+vp_lang.pin+'</legend>');
		vp_openlist_embed_template = vp_openlist_embed_template.replace(/value="list"/g, 'value="pin"');
		vp_openlist_embed_template = vp_openlist_embed_template.replace('glyphicon-picture', 'glyphicon-calendar');
		vp_openlist_embed_template = vp_openlist_embed_template.replace('entry_images', 'entry_pins');
		vp_openlist_embed_template = vp_openlist_embed_template.replace(/class="vp-uploader text-center"/g, 'class="vp-uploader vp-uploader-lesspad text-center"');
		vp_openlist_embed_template = vp_openlist_embed_template.replace(/thumbnail_uploader/g, 'add_pin');
}

var image_set = 0;
var dlink = '';
var dcaption = '';
var dalt = '';
var dlink_p = '';
var next_form_num = 0;
var next_ans_form_num = 0;
var quiz_fixed_ans = 1;
var x_number = 50000;
var embed_link_css = 0;
var refresh_media_lib = 0;
var quiz_type = 'trivia';
var vp_action_pending = {};
var vp_emo_pending = 0;	
var enable_drag_drop = 1;

jQuery(document).ready(function($) {
	
	scale_jetpack_tiles();
	enable_drag_drop = !vp_isMobile();
	refresh_bp_noti();
	
	$( window ).resize( function(){scale_jetpack_tiles();});
	
	$('#regcaphidden').val(1);
	$('#lostcaphidden').val(1);
	
	$( '.quiz_checkbox' ).prop( 'checked', false );
	$( '#quiz_type' ).val( 'trivia' ); 
	$( '#vp_quiz_person_sw' ).val( 0 );
	
	/*
	if( quiz_fixed_ans == 1 ){
		$( '#fixed_answer' ).prop( 'checked', true ).trigger( 'change' );
	}
	else $( '#fixed_answer' ).prop( 'checked', false ).trigger( 'change' );
	*/
	
	if( $('#preface_desc').length > 0 ) {
		add_tinymce( $('#preface_desc') );	
	}
	
	var vp_w = jQuery('.vp-pins').width();
	if( vp_w != null ) {
		if( vp_w < 500 ) {
			$('a[data-pin-do="embedPin"]').attr( 'data-pin-width', 'medium' );
		}
		else if( vp_w < 368 ) {
			$('a[data-pin-do="embedPin"]').attr( 'data-pin-width', 'small' );
		}
	}
	
	if( jQuery('.vp-check-container').parents('.container:first').length > 0 )jQuery( '.vp-check-container' ).removeClass( 'container' ).attr( 'id', '' );
	else if( jQuery('.vp-check-container').parents('#container:first').length > 0 )jQuery( '.vp-check-container' ).removeClass( 'container' ).attr( 'id', '' );
	else if( jQuery('.vp-check-container').parents('#container-fluid:first').length > 0 )jQuery( '.vp-check-container' ).removeClass( 'container' ).attr( 'id', '' );
	
	if( $('#vp-slides').length > 0 ) {
		vp_help_slide();
	}
	
	if( $('.show_open_list_editor').length > 0 && vp_user_logged_in != '' && vp_user_logged_in != 0 && vp_autoload_op_editor == 1 ) {
		setTimeout( function(){ $('.show_open_list_editor').click() }, 2000 );	
	}
	
	if( typeof poll_submit != 'undefined' ) {
		if( user_already_voted == 1 ) {
			$('.quiz_ans_list_obs').show();
			if( user_votes != '' ) {
				var votes = $.parseJSON( user_votes );
				$.each( votes, function( k, v ) {
					$('#quiz_ans_' + v ).prop( 'checked', true );	
				});
			}
		} 	
	}
	
	if( $.fn.datepicker ) {
		jQuery('#voting_till').datepicker({
			dateFormat : 'dd-M-yy',
			minDate: 0
		});
	}
	
	
	if( $('.regif_row').length > 0 ) {
		$('.regif_row').show();
		$('.regif_row').bxSlider({
			slideWidth: 200,
			minSlides: 1,
			maxSlides: 5,
			slideMargin: 10,
			captions: true,
			adaptiveHeight: true
		  });	
		  
		$('.regif_row').find('.slide').click(function(){
			
			if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) ) {
				modal_open( jQuery('.login_modal_link') );
				resize_modal(350, 600);
				return false;	
			}
			
			var post_id = $( this ).parents( '.regif_row_parent:first' ).attr( 'data-rel' );
			
			if( $(this).hasClass( 'vp_gif_add_new' ) ) {
				$('.vp_react_gif_wrap').find('li').removeClass('active');	
				$('.vp_react_gif_wrap').find('li').eq(0).addClass('active');	
			}
			else {
				$('.vp_react_gif_wrap').find('li').removeClass('active');	
				$('.vp_react_gif_wrap').find('li').eq(1).addClass('active');
					
				var url = $( this ).find( 'img' ).attr( 'data-gif-url' );	
				$( '.gif_react_url' ).eq(1).removeAttr( 'src' );
				$( '.gif_react_url' ).eq(1).attr( 'src', url );
			}
		
			$( '#react_with_gif_post_id' ).val( post_id );
			$('.vp_react_gif_wrap').slideDown();
			scroll_to_next_additem( 'vp_react_gif_wrap' );
		});  
	}
	
	if( $('.fb-comm-waypoint').length > 0 ) {
		$('.fb-comm-waypoint').waypoint(function(direction) {
		 	var d = $(this.element);
			if( !d.hasClass( 'fb_loaded' ) ) {
				d.addClass('fb-comments');
				FB.XFBML.parse();
				d.addClass('fb_loaded');
				//console.log(d.attr('data-href'));
			}
		}, {
		  offset: '200%'
		})
	}

	if( jQuery('.vp-grid').length > 0 ) {
		try{
			jQuery('.vp-grid').masonry({
			  itemSelector: '.vp-grid-item',
			  columnWidth: 360,
			  gutter: 10


			});
			jQuery('.vp-grid-2').masonry({
			  itemSelector: '.vp-grid-item-2',
			  columnWidth: 500,
			  gutter: 10
			});
		}catch(e){}
	}

	$(document).on( 'click', '.show_editor_advanced' , function(e){
		e.preventDefault();
		jQuery('.vp-editor-advanced').slideToggle( function(){
			if( $('.vp-editor-advanced' ).is( ':hidden' ) == true ) {
				$( '.show_editor_advanced' ).html( '<i class="glyphicon glyphicon-wrench"></i>&nbsp;&nbsp;' + vp_lang.show_adv_opt );	
			}
			else $( '.show_editor_advanced' ).html( '<i class="glyphicon glyphicon-wrench"></i>&nbsp;&nbsp;' + vp_lang.hide_adv_opt );	
		});
	});
	

	$(document).on( 'click', '.entry_move_up' , function(e){
		var self = $(this).parents('.more_items:first');
		var prev = self.prev('.more_items');
		
		if( prev.length == 0 ) return;
		
		swap_elems( self, prev, 1 );
	});
	
	$(document).on( 'click', '.entry_move_down' , function(e){
		var self = $(this).parents('.more_items:first');
		var next = self.next('.more_items');
		
		if( next.length == 0 ) return;
		
		swap_elems( self, next, 0 );
	});
	
	
	$(document).on( 'change', '.vp_react_gif_url' , function(e){
		var u = $(this).val();
		if( u == '' )return;
		$( '.gif_react_url' ).eq(0).attr( 'src', u );
	});
	
	$(document).on( 'click', '.vp_open_list_del' , function(e){
		e.preventDefault();
		if( !confirm( vp_lang.confirm_del ) ) return;
		var self = $(this);
		self.hide();
		var pid = self.attr('data-rel');
		$('.vp_open_list_del_spinner').remove();
		$('<img src="'+vp_spinner_url+'" class="vp_open_list_del_spinner"/>').insertAfter(self);
		$.post( vp_ajaxurl, {
			'action' : 'vp_open_list_del',
			'post_id': pid	,
			'_nonce': vp_ajax_nonce	
		}, function( response ){
			$('.vp_open_list_del_spinner').remove();
			var data = $.parseJSON( response );
			if( data.error != '' ) {
				alert( data.error );				
				self.show();
			}
			else {
				if( self.parents('.vp-op-bp-wrap:first').length > 0 ) {
					self.parents('.vp-op-bp-wrap:first').slideUp();
				}
				else{
					$('.vp-entry[data-rel="'+pid+'"]').html( '<div class="alert alert-info">' + vp_lang.entry_deleted + '</div>' );
				}
			}	
		});
	});
	
	$(document).on( 'click', '.submit_open_list' , function(e){
		e.preventDefault();
		try{
			tinyMCE.triggerSave();
		}catch(e){console.log(e);}
		var self = $(this);
		self.hide();
		var p = $('#open_list_form').serialize();
		$('.open_list_form_feedback').html(vp_lang.submitting_open_list).show();
		
		$.post( vp_ajaxurl, 
			p	
		, function( response ){
			var data = $.parseJSON( response );
			if( data.error != '' ) {
				alert( data.error );				
				self.show();
				$('.open_list_form_feedback').html(data.error).show();
				scroll_to_next_additem( 'open_list_form_feedback' );
			}
			else {
				self.show();
				$('.open_list_form_feedback').html(vp_lang.submitted_open_list).show();
				$("ul#vp-tabs li").eq(0).removeClass('active');
				$("ul#vp-tab li").eq(0).removeClass('active');
				$("ul#vp-tabs li").eq(0).click();
				scroll_to_next_additem( 'open_list_form_feedback' );
			}	
		});
	});
	
	$(document).on( 'change', '#quiz_type' , function(){
		quiz_type = $(this).val();
		var r = 1;
		if( typeof prevent_item_scroll != 'undefined' && prevent_item_scroll == 1 ) r = 0;
				
		if(quiz_type == 'person1' ) {
			quiz_fixed_ans = 0;
			$('.correct_answer, .quiz_res_from_score, .quiz_res_to_score').attr( 'disabled', true );
			set_quiz_results_dropdown( 'all' );
			$('.show_explain_ans, .quiz_ans_exp').hide();
			$('.for_personality_quiz').show();
			
			if( $( '#vp_quiz_person_sw' ).val() == 0 ) {
				if( r )remove_all_tinymce();
				$('.vp_post_entries_options').insertBefore($('.vp_quiz_divider'));
       			$('.vp_post_entries_main').insertAfter($('.vp_quiz_divider'));
				$( '#vp_quiz_person_sw' ).val( 1 );
				if( r )reattach_all_tinymce();
			}
		}
		else if( quiz_type == 'person2' ) {			
			quiz_fixed_ans = 0;
			$('.correct_answer').attr( 'disabled', true );
			$( '.quiz_res_from_score, .quiz_res_to_score' ).attr( 'disabled', false );
			set_quiz_results_dropdown( 'all' );
			$('.show_explain_ans, .quiz_ans_exp').hide();
			$('.for_personality_quiz').hide();
			
			if( $( '#vp_quiz_person_sw' ).val() == 1 ) {
				if( r )remove_all_tinymce();
				$('.vp_post_entries_options').insertAfter($('.vp_quiz_divider'));
       			$('.vp_post_entries_main').insertBefore($('.vp_quiz_divider'));
				$( '#vp_quiz_person_sw' ).val( 0 );
				if( r )reattach_all_tinymce();
			}
		}
		else if( quiz_type == 'mcq' ) {			
			quiz_fixed_ans = 1;
			$('.correct_answer').attr( 'disabled', false );
			$( '.quiz_res_from_score, .quiz_res_to_score' ).attr( 'disabled', false );
			set_quiz_results_dropdown( 'all' );
			$('.show_explain_ans').show();
			$('.for_personality_quiz').hide();
			
			if( $( '#vp_quiz_person_sw' ).val() == 1 ) {
				if( r )remove_all_tinymce();
				$('.vp_post_entries_options').insertAfter($('.vp_quiz_divider'));
       			$('.vp_post_entries_main').insertBefore($('.vp_quiz_divider'));
				$( '#vp_quiz_person_sw' ).val( 0 );
				if( r )reattach_all_tinymce();
			}
		}
		else if( quiz_type == 'trivia' ) {			
			quiz_fixed_ans = 1;
			$('.correct_answer').attr( 'disabled', false );
			$( '.quiz_res_from_score, .quiz_res_to_score' ).attr( 'disabled', false );
			set_quiz_results_dropdown( 'all' );
			$('.show_explain_ans').show();
			$('.for_personality_quiz').hide();
			
			if( $( '#vp_quiz_person_sw' ).val() == 1 ) {
				if( r )remove_all_tinymce();
				$('.vp_post_entries_options').insertAfter($('.vp_quiz_divider'));
       			$('.vp_post_entries_main').insertBefore($('.vp_quiz_divider'));
				$( '#vp_quiz_person_sw' ).val( 0 );
				if( r )reattach_all_tinymce();
			}
		}
	});
	
	$(document).on( 'click', '.register-button' , function(){
		if( $('.reg-recap').length > 0 ) {
			if( $('#regcaphidden').val() == 1 ) {
				if( typeof load_reg_recap != 'undefined' )$('.reg-recap').html($('.recap-html').html());
				$('.nocap').hide();
				$('.reg-recap').show();
				$('#regcaphidden').val(0)
				return false;
			}
		}
	});
	
	$(document).on( 'click', '.lostpassword-button' , function(){
		if( $('.lost-recap').length > 0 ) {
			if( $('#lostcaphidden').val() == 1 ) {
				if( typeof load_lost_recap != 'undefined' )$('.lost-recap').html($('.recap-html').html());
				$('.nolostrecap').hide();
				$('.lost-recap').show();
				$('#lostcaphidden').val(0)
				return false;
			}
		}
	});
	
	$(document).on( 'click', "ul#vp-tabs li", function(e){
        if (!$(this).hasClass("active")) {
            var tabNum = $(this).index();
            var nthChild = tabNum+1;
            $("ul#vp-tabs li.active").removeClass("active");
            $(this).addClass("active");
            $("ul#vp-tab li.active").removeClass("active");
            $("ul#vp-tab li:nth-child("+nthChild+")").addClass("active");
			vp_open_list_tab_activate( $(this) );
        }
    });

	
	$(document).on( 'click', '.show_explain_ans' , function(){
		$(this).parents('.more_items:first').find('.quiz_ans_exp').toggle();
		return false;
	});
	
	$(document).on( 'click', '.vp_copy_post' , function(){
		if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) ) {
			modal_open( jQuery('.login_modal_link') );
			resize_modal(350, 600);
			return false;	
		}
		return true;
	});
	
	$(document).on( 'click', '.show_open_list_editor' , function(){
		
		if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) ) {
			modal_open( jQuery('.login_modal_link') );
			resize_modal(350, 600);
			return false;	
		}
		
		if( $('#open_list_form').length <= 0 ){
			$('.open_list_editor_load_feed').show();
			scroll_to_next_additem( 'open_list_editor_load_feed' );
			$.post( vp_ajaxurl, { 
				'action': 'vp_load_open_list_editor',
				'post_id': $(this).attr( 'data-rel' ),
				'_nonce': vp_ajax_nonce
			}, function( response ){
				$('.open_list_editor_load_feed').hide();
				var data = $.parseJSON( response );
				$('.vp_save_loader').hide();
				$('.udata_form_btn').show();
				
				if( data.error != '' ) {
					alert( data.error );
				}
				else {
					$('.open_list_editor').html( data.editor );
					if( $('.open_list_editor').is(':hidden') == true ){
						$("ul#vp-tabs li").eq(0).removeClass('active');
						$("ul#vp-tab li").eq(0).removeClass('active');
						$("ul#vp-tabs li").eq(0).click();
						$('.open_list_editor').slideDown();
					}
					else $('.open_list_editor').slideUp();
				}	
			});	
		}
		else {
			/*if( $('.open_list_editor').is(':hidden') == true ){
				$("ul#vp-tabs li").eq(0).removeClass('active');
				$("ul#vp-tab li").eq(0).removeClass('active');
				$("ul#vp-tabs li").eq(0).click();
				$('.open_list_editor').slideDown();
			}
			else $('.open_list_editor').slideUp();*/
			scroll_to_next_additem( 'open_list_editor' ); 
		}
	});
	
	$(document).on( 'change', '.vp_ad_pos' , function(){
		if( $( this ).val( ) == 'list' ) {
			$( this ).parents('table:first').find('input[name="index"]').show();
		}
		else $( this ).parents('table:first').find('input[name="index"]').hide();
	});
	
	$(document).on( 'click', '.delete_vp_ad' , function(e){
		e.preventDefault();
		var f = $( this ).parents('form:first');
		f.find('input[name="delete_vp_ad"]').val(1);
		f.find('.button-primary').click();
	});
	
	$(document).on( 'click', '#show_numbers' , function(){
		if( $(this).is( ':checked' ) == false ) {
			$('.entry-show-num').prop('checked', false).attr( 'disabled', true ).trigger( 'change' );
			$('#open_list').attr( 'checked', false );
		}
		else 
			$('.entry-show-num').prop('checked', true).attr( 'disabled', false ).trigger( 'change' );	
	});
	
	$(document).on( 'click', '#open_list' , function(){
		if( $(this).is( ':checked' ) == true ) {
			if( $( '#show_numbers' ).is( ':checked' ) == false )
				$('#show_numbers').click();
		}	
	});
		
	$(document).on( 'change', '.entry-show-num' , function(){
		if( $(this).is( ':checked' ) == true ) {
			$(this).parents('.more_items:first').addClass('more_items_numbered');
			$(this).parents('.more_items:first').find('.entry-no').eq(0).html('');
		}
		else {
			$(this).parents('.more_items:first').removeClass('more_items_numbered');
			$(this).parents('.more_items:first').find('.entry-no').eq(0).html('');
		}
		$('.more_items').each(function(){
			var en = $(this).prevAll('.more_items').length;
			var en_n = $(this).prevAll('.more_items_numbered').length;
			if( $(this).hasClass( 'more_items_numbered' ) ) {
				$(this).find('.entry-no').eq(0).html( parseInt(en_n) );
			}
			$(this).find('.entry-no-val').eq(0).val( parseInt(en) );
		});
	});
	
	$(document).on( 'click', '.show_numering' , function(){
		$(this).parent().find( '.entry-show-num' ).click();
	});
	
	$(document).on( 'click', '.udata_form_btn' , function(e){
		$('.udata_form_btn').hide();
		$('.vp_save_ok').hide();
		$('.vp_save_loader').show();
		$.post( vp_ajaxurl, 
			$('#udata_form').serialize()
		, function( response ){
			var data = $.parseJSON( response );
			$('.vp_save_loader').hide();
			$('.udata_form_btn').show();
			
			if( data.error != '' ) {
				alert( data.error );
			}
			else {
				$('.vp_save_ok').show();
			}	
		});
		return false;
	});
	
	$(document).on( 'click', '.udata_s_form_btn' , function(e){
		$('.udata_s_form_btn').hide();
		$('.vp_s_save_ok').hide();
		$('.vp_s_save_loader').show();
		$.post( vp_ajaxurl, 
			$('#udata_s_form').serialize()
		, function( response ){
			var data = $.parseJSON( response );
			$('.vp_s_save_loader').hide();
			$('.udata_s_form_btn').show();
				
			if( data.error != '' ) {
				alert( data.error );
			}
			else {
				$('.vp_s_save_ok').show();
			}	
		});
		return false;
	});
	
	$(document).on( 'click', '.udata_c_form_btn' , function(e){
		$('.udata_c_form_btn').hide();
		$('.vp_c_save_ok').hide();
		$('.vp_c_save_loader').show();
		$.post( vp_ajaxurl, 
			$('#udata_c_form').serialize()
		, function( response ){
			var data = $.parseJSON( response );
			$('.vp_c_save_loader').hide();
			$('.udata_c_form_btn').show();
				
			if( data.error != '' ) {
				alert( data.error );
			}
			else {
				$('.vp_c_save_ok').show();
			}	
		});
		return false;
	});

	$(document).on( 'click', '.vp-dash-media' , function(e){
		e.preventDefault();
		
		wp.media.view.l10n.insertIntoPost = vp_lang.close;
		wp.media.view.l10n.insertFromUrlTitle = vp_lang.upload_from_url;
		wp.media.view.l10n.insertMediaTitle = vp_lang.manage_media;
		
		var frame = wp.media({
				frame: 'post',
				state: 'insert',
				library: {type: 'image'},
				multiple: false,
			});
		frame.on('insert', function() {
		});

		frame.open();
		if( refresh_media_lib ) try{wp.media.frame.content.get('gallery').collection.props.set({ignore: (+ new Date())});}catch(e){}
		
		$('.media-menu > a:nth-child(2)').hide();
		$('.media-menu > a:nth-child(3)').hide();
		$('.media-menu > a:nth-child(4)').hide();
		$('.media-menu > a:nth-child(6)').hide();
		
		$('.embed-link-settings').hide();
		
		if( embed_link_css == 0 ) {
			var style = $('<style>.embed-link-settings,.setting.align,.setting.link-to{display:none}</style>');
			$('html > head').append(style);
			embed_link_css = 1;
		}
	});
	
	$(document).on( 'click', '.gallery_uploader' , function(e){
		image_set = 0;
		dlink = '';
		dcaption = '';
		dalt = '';
		size = 250;
		if($(this).hasClass('gl-small'))size = 200;
		var url_up = $(this).hasClass('thumbnail_uploader_url') ? 1 : 0;
		var parent = $(this);
		dlink_p = parent;
		
		e.preventDefault();
		
		wp.media.view.l10n.insertIntoPost = vp_lang.insert;
		wp.media.view.l10n.insertFromUrlTitle = vp_lang.upload_from_url;
		wp.media.view.l10n.insertMediaTitle = vp_lang.upload_media;
		
		var frame = wp.media({
				frame: 'post',
				state: 'insert',
				library: {type: 'image'},
				multiple: true,
			});

		frame.on('insert', function() {
			var selected = [];
			var selection = frame.state().get('selection');
			selection.map(function(file) {
				selected.push(file.toJSON());
			});
			
			if(selected.length > 0){
				parent.parents('.more_items:first').find('.vp-uploader').hide();
				parent.parents('.more_items:first').find('.vp-uploader-nopad').show();
				$('.attachment-filters').val('image').trigger('change');
				for( k = 0; k < selected.length; k++ ) {
					h = get_gallery_editor_html( selected[k].id, selected[k].url, parent.parents('.more_items:first').find('.vp-uploader'), size );
					parent.parents('.more_items:first').find('.vp-uploader-image').append(h);
				}
				
				parent.parents('.more_items:first').find('.more_details_btn').click();
			}
			
		});
		
		frame.open();
		//if( refresh_media_lib ) try{wp.media.frame.content.get('gallery').collection.props.set({ignore: (+ new Date())});}catch(e){}
		
		$('.media-menu > a:nth-child(2)').hide();
		$('.media-menu > a:nth-child(3)').hide();
		$('.media-menu > a:nth-child(4)').hide();
		$('.media-menu > a:nth-child(6)').hide();
		
		if( url_up ) {
			jQuery('a.media-menu-item:nth-child(6)').click();
		}
		$('.embed-link-settings').hide();
		
		if( embed_link_css == 0 ) {
			var style = $('<style>.embed-link-settings,.setting.align,.setting.link-to{display:none}</style>');
			$('html > head').append(style);
			embed_link_css = 1;
		}
	});
	
	$(document).on( 'click', '.playlist_uploader' , function(e){
		image_set = 0;
		dlink = '';
		dcaption = '';
		dalt = '';
		size = 250;
		if($(this).hasClass('gl-small'))size = 200;
		var url_up = $(this).hasClass('thumbnail_uploader_url') ? 1 : 0;
		var parent = $(this);
		dlink_p = parent;
		
		e.preventDefault();
		
		wp.media.view.l10n.insertIntoPost = vp_lang.insert;
		wp.media.view.l10n.insertFromUrlTitle = vp_lang.upload_from_url;
		wp.media.view.l10n.insertMediaTitle = vp_lang.upload_media;
		
		var frame = wp.media({
				frame: 'post',
				state: 'insert',
				library: {type: 'video'},
				multiple: true,
			});
		
		frame.on('insert', function() {
			var selected = [];
			var selection = frame.state().get('selection');
			selection.map(function(file) {
				selected.push(file.toJSON());
			});
			
			if(selected.length > 0){
				parent.parents('.more_items:first').find('.vp-uploader').hide();
				parent.parents('.more_items:first').find('.vp-uploader-nopad').show();
				ttt = '';
				for( k = 0; k < selected.length; k++ ) {
					if( k == 0 ){
						ttt = selected[k].type;
						$('.attachment-filters').val(ttt).trigger('change');
					}
					else if( ttt != selected[k].type ) {
						return alert( vp_lang.one_type_playlist );
					}
					h = get_playlist_editor_html( selected[k].id, selected[k].url, selected[k].type, selected[k].name, parent.parents('.more_items:first').find('.vp-uploader'), size - 50 );
					parent.parents('.more_items:first').find('.vp-uploader-image').append(h);
				}
				
				parent.parents('.more_items:first').find('.more_details_btn').click();
			}
			
		});
		
		frame.open();
		//if( refresh_media_lib ) try{wp.media.frame.content.get('gallery').collection.props.set({ignore: (+ new Date())});}catch(e){}
		
		$('.media-menu > a:nth-child(2)').hide();
		$('.media-menu > a:nth-child(3)').hide();
		$('.media-menu > a:nth-child(4)').hide();
		$('.media-menu > a:nth-child(6)').hide();
		
		if( url_up ) {
			jQuery('a.media-menu-item:nth-child(6)').click();
		}
		$('.embed-link-settings').hide();
		
		if( embed_link_css == 0 ) {
			var style = $('<style>.embed-link-settings,.setting.align,.setting.link-to{display:none}</style>');
			$('html > head').append(style);
			embed_link_css = 1;
		}
	});
	
	$(document).on( 'click', '.gal_img_move_left' , function(e){
		var p = $(this).parents('.vp_editor_gal_img:first');
		p.insertBefore(p.prev('.vp_editor_gal_img'));
		return false;
	});
	
	$(document).on( 'click', '.gal_img_move_right' , function(e){
		var p = $(this).parents('.vp_editor_gal_img:first');
		p.insertAfter(p.next('.vp_editor_gal_img'));
		return false;
	});
	
	$(document).on( 'click', '.thumbnail_uploader' , function(e){
		image_set = 0;
		dlink = '';
		dcaption = '';
		dalt = '';
		var url_up = $(this).hasClass('thumbnail_uploader_url') ? 1 : 0;
		var parent = $(this);
		var type = 'image';
		
		if( $(this).hasClass( 'list_video' ) ) type = 'video';
		else if( $(this).hasClass( 'list_audio' ) ) type = 'audio';
		
		dlink_p = parent;
		
		e.preventDefault();
		
		wp.media.view.l10n.insertIntoPost = vp_lang.insert;
		wp.media.view.l10n.insertFromUrlTitle = vp_lang.upload_from_url;
		wp.media.view.l10n.insertMediaTitle = vp_lang.upload_media;
		
		var frame = wp.media({
				frame: 'post',
				state: 'insert',
				library: {type: type},
				multiple: false,
			});

		frame.on('insert', function() {
			var first = frame.state().get('selection').first().toJSON();
			var url = first.url;
			
			if( first.type != type ) return alert( type +' ' + vp_lang.vp_req );
			
			image_set = 1;
			add_thumb_image( parent, first.id, url, first.title );
		});
		
		
		frame.open();
		if( refresh_media_lib ) try{wp.media.frame.content.get('gallery').collection.props.set({ignore: (+ new Date())});}catch(e){}
				
		$('.media-menu > a:nth-child(2)').hide();
		$('.media-menu > a:nth-child(3)').hide();
		$('.media-menu > a:nth-child(4)').hide();
		
		if( type == 'video' || type == 'audio' )$('.media-menu > a:nth-child(6)').hide();
		
		if( url_up ) {
			jQuery('a.media-menu-item:nth-child(6)').click();
		}
		$('.embed-link-settings').hide();
		
		if( embed_link_css == 0 ) {
			var style = $('<style>.embed-link-settings,.setting.align,.setting.link-to{display:none}</style>');
			$('html > head').append(style);
			embed_link_css = 1;
		}
	});
	
	$(document).on( 'click', '.edit-profile-images' , function(e){
		e.preventDefault();
		var avatar = 0;
		
		if( $(this).hasClass('edit-avatar') ) {
			wp.media.view.l10n.insertMediaTitle = vp_lang.upload_avatar;
			avatar = 1;	
		}
		else if( $(this).hasClass('edit-cover') ) {
			avatar = 2;	
			wp.media.view.l10n.insertMediaTitle = vp_lang.upload_cover;
		}
		else if( $(this).hasClass('comm-react') ) {
			avatar = 3;	
			wp.media.view.l10n.insertMediaTitle = vp_lang.upload_media;
		}
		
		wp.media.view.l10n.insertIntoPost = vp_lang.save;
		wp.media.view.l10n.insertFromUrlTitle = vp_lang.upload_from_url;
		
		
		var frame = wp.media({
				frame: 'post',
				state: 'insert',
				library: {type: 'image'},
				multiple: false,
			});

		frame.on('insert', function() {
			var first = frame.state().get('selection').first().toJSON();
			var url = first.url;
			var id = first.id;
			
			if( first.type != 'image' ) return alert( vp_lang.img_req );
			
			if( avatar == 1 )
				$('.avatar').attr( 'src', url );
			else if( avatar == 2 ){
				$('.vp-cover').css( 'background', 'url('+url+')');
			}
			else if( avatar == 3 ){
				$('.vp_react_gif_url').val(url).trigger('change');
			}
			
			if( avatar == 1 || avatar == 2 ) {
				$.post( vp_ajaxurl, {
					'media_id': id,
					'action': avatar == 1 ? 'vp_set_avatar' : 'vp_set_cover',
					'_nonce': vp_ajax_nonce
				}, function( response ){
					var data = $.parseJSON( response );
					if( data.error != '' ) {
						alert( data.error );
					}
					else {
						window.location.reload();
					}	
				});
			}
		});
		
		frame.open();
		if( refresh_media_lib ) try{wp.media.frame.content.get('gallery').collection.props.set({ignore: (+ new Date())});}catch(e){}
			
		$('.media-menu > a:nth-child(2), a.media-menu-item:nth-child(6)').hide();
		$('.media-menu > a:nth-child(2)').hide();
		$('.media-menu > a:nth-child(3)').hide();
		$('.media-menu > a:nth-child(4)').hide();
		$('.embed-link-settings').hide();
		
		if( embed_link_css == 0 ) {
			var style = $('<style>.embed-link-settings,.setting.align,.setting.link-to{display:none}</style>');
			$('html > head').append(style);
			embed_link_css = 1;
		}
	});
	
	$(document).on( 'mousedown', 'li.attachment' , function(){
		if( vp_meme_enabled != 1 ) return;
		var e = $(this);
		setTimeout(function(){
			var id = e.attr('data-id');
			if( id == 'undefined' ) return;
			var u = '<a href="'+vp_meme_gen_url+'?media_id='+id+'" target="_blank" class="vp-meme-gen-link">'+vp_lang.gen_meme+'</a><br/>';
			$(u).insertAfter('.edit-attachment');
			return true;
		}, 300);
	});
	
	$(document).on( 'mousedown', '.media-button' , function(){
		setTimeout(function(){
			if( image_set == 0 && dlink != '' ){
				
				if( vp_hotlink_image == 1 ) {
					add_thumb_image( dlink_p, dlink, dlink );
					return;
				}
				
				show_thumb_downloading( dlink_p );
				
				$.post( vp_ajaxurl, {
					'action': 'vp_download_image',
					'load_url': dlink,
					'caption': dcaption,
					'alt': dalt,
					'_nonce': vp_ajax_nonce
				},function( response ){
					
					hide_thumb_downloading( dlink_p );
					
					var data = $.parseJSON( response );
					if( data.error != '' )alert( data.error );
					else{
						refresh_media_lib = 1;
						add_thumb_image( dlink_p, data.id, data.url );	
					}
				});	
			}
		}, 500);
		return true;
	});
	
	$(document).on( 'change', '#embed-url-field' , function(){
		dlink = $(this).val();
		return true;
	});
	
	$(document).on( 'change', '.setting.caption > textarea' , function(){
		dcaption = $(this).val();
		return true;
	});
	
	$(document).on( 'change', '.setting.alt-text > input' , function(){
		dalt = $(this).val();
		return true;
	});
	
	$(document).on( 'change', '#embed-url-field' , function(){
		dlink = $(this).val();
		return true;
	});
	
	$(document).on( 'click', '.vp-nav-login' , function(){
		if( typeof prevent_login_modals != 'undefined' ) return true;
		if( $('.login_modal_link').length > 0 ) {
			modal_open( $('.login_modal_link') );
			resize_modal(350, 600);
			return false;
		}
	});
	
	$(document).on( 'click', '.vp-nav-register' , function(){
		if( typeof prevent_login_modals != 'undefined' ) return true;
		if( $('.register_modal_link').length > 0 ) {
			modal_open( $('.register_modal_link') );
			resize_modal(350, 600);
			return false;
		}
	});
	
	$(document).on( 'click', '.forgot-password' , function(){
		tb_remove();
		setTimeout( function() {
			modal_open( $('.forgot_modal_link') );
			resize_modal(350, 350);
		}, 400);
		return false;
	});
	
	$(document).on( 'click', '.add_more_ans' , function(){
		var elem = $(this).parents('.quiz_parent:first');
		p = add_quiz_answers( elem );
		elem = p.find('.more_ans:last');
		var hq = elem.offset().top;
    	if( typeof prevent_item_scroll != 'undefined' && prevent_item_scroll == 1 ){}
		else jQuery("html, body").animate({ scrollTop: hq }, 500);
		return false;
	});
	
	$(document).on( 'click', '.add_video' , function(){
		$('#video_url_elem').val( $(this).parents('.more_items:first').find('.tinymce').eq(0).attr( 'id' ) )
		var c = $(this).attr( 'data-config' );
		if( typeof c != 'undefined' ){
			reverse_embed( $('#add_video_modal'), c );	
		}
		modal_open( $('.add_video_modal_link') );
		resize_modal();
		return false;
	});
	
	$(document).on( 'click', '.add_audio' , function(){
		$('#audio_url_elem').val( $(this).parents('.more_items:first').find('.tinymce').eq(0).attr( 'id' ) )
		var c = $(this).attr( 'data-config' );
		if( typeof c != 'undefined' ){
			reverse_embed( $('#add_audio_modal'), c );	
		}
		modal_open( $('.add_audio_modal_link') );
		resize_modal();
		return false;
	});
	
	$(document).on( 'click', '.add_pin' , function(){
		$('#pin_url_elem').val( $(this).parents('.more_items:first').find('.tinymce').eq(0).attr( 'id' ) )
		var c = $(this).attr( 'data-config' );
		if( typeof c != 'undefined' ){
			reverse_embed( $('#add_pin_modal'), c );	
		}
		modal_open( $('.add_pin_modal_link') );
		resize_modal();
		return false;
	});
	
	$(document).on( 'click', '.add_video_submit' , function(){
		
		var vp_allowed_embeds_regex = new RegExp( vp_allowed_embeds, 'ig' );
		var err = $('.video_add_error_msg');
		var vid = 0;
		err.html('');
		var url = $('#video_url').val();
		if(url == '')return err.html( '<div class="alert alert-danger">' +vp_lang.insert_url+ '</div>' ).show();
		var domain = extractDomain(url);
		
		var video_width = $('#video_width').val();
		var video_height = $('#video_height').val();
		
		if( 
			!domain.match(/youtube\.com|youtu\.be|facebook\.com|fb\.com|dailymotion\.com|vimeo\.com|vine\.co|ted\.com|bbc\.co|liveleak\.com/g) && !domain.match( vp_allowed_embeds_regex )
		  )
		return err.html( '<div class="alert alert-danger">' +vp_lang.choose_valid_video_domain+ '</div>' ).show();
		
		if( url.match(/youtube\.com|youtu\.be/g) ){
			site = 'youtube';
			vid = yt_parser( url );
		}
		else if( url.match(/facebook\.com|fb\.com/g) ){
			site = 'facebook';
			vid = fb_parser( url );
		}
		else if( url.match(/dailymotion\.com/g) ){
			site = 'dailymotion';
			vid = dm_parser( url );
		}
		else if( url.match(/vimeo\.com/g) ){
			site = 'vimeo';
			vid = vm_parser( url );
		}
		else if( url.match(/vine\.co/g) ){
			site = 'vine';
			vid = vn_parser( url );
		}
		else if( url.match(/ted\.com/g) ){
			site = 'ted';
			vid = ted_parser( url );
		}
		else if( url.match(/bbc\.co/g) ){
			site = 'bbc';
			vid = bbc_parser( url );
		}
		else if( url.match(/liveleak\.com/g) ){
			site = 'liveleak';
			vid = lk_parser( url );
		}
		else {
			site = 'custom';
			vid = custom_parser( url );
		}
		
		v_err = vp_lang.invalid_url;
		if( site == 'liveleak' && !vid )v_err = vp_lang.lk_embed_url;
		if( !vid )return err.html( '<div class="alert alert-danger">' + v_err + '</div>' ).show();
		
		var val_code = 'video|'+site+'|'+vid+'|'+video_width+'|'+video_height;	
		var parent = $('#' + $('#video_url_elem').val() );
		
		add_embed( parent, val_code);
		parent.parents('.more_items:first').find('.more_details_btn').click();
		tb_remove();
		return false;
	});
	
	$(document).on( 'click', '.add_audio_submit' , function(){
		var vp_allowed_embeds_regex = new RegExp( vp_allowed_embeds, 'ig' );
		var err = $('.audio_add_error_msg');
		var aid = 0;
		err.html('');
		var url = $('#audio_url').val();
		if(url == '')return err.html( '<div class="alert alert-danger">' +vp_lang.insert_url+ '</div>' ).show();
		
		var domain = extractDomain(url);
		
		if( !domain.match(/soundcloud\.com/g)  && !domain.match( vp_allowed_embeds_regex ) ){
			return err.html( '<div class="alert alert-danger">' +vp_lang.choose_valid_audio_domain+ '</div>' ).show();	
		}
		
		var audio_width = $('#audio_width').val();
		var audio_height = $('#audio_height').val();
	
		if( domain.match(/soundcloud\.com/g) ){
			site = 'soundcloud';
			aid = scloud_parser( url );	
		}
		else {
			site = 'custom';
			aid = custom_parser( url );	
		}
		
		if( !aid )return err.html( '<div class="alert alert-danger">' +vp_lang.invalid_url+ '</div>' ).show();
		
		var parent = $('#' + $('#audio_url_elem').val() );
		var val_code = 'audio|'+site+'|'+aid+'|'+audio_width+'|'+audio_height;	
		
		add_embed( parent, val_code);
		parent.parents('.more_items:first').find('.more_details_btn').click();
		
		tb_remove();
		return false;
		
	});
	
	$(document).on( 'click', '.add_pin_submit' , function(){
		var vp_allowed_embeds_regex = new RegExp( vp_allowed_embeds, 'ig' );
		var err = $('.pin_add_error_msg');
		var eid = 0;
		var type = 'pin';
		err.html('');
		var url = $('#pin_url').val();
		if(url == '')return err.html( '<div class="alert alert-danger">' +vp_lang.insert_url+ '</div>' ).show();
		var domain = extractDomain(url);
		
		var pin_width = $('#pin_width').val();
		var pin_height = $('#pin_height').val();
		
		if( 
			!domain.match(/instagram\.com|instagra\.me|facebook\.com|fb\.com|twitter\.com|pinterest\.com|vine\.co|plus\.google\.com|youtube\.com|youtu\.be|dailymotion\.com|vimeo\.com|ted\.com|bbc\.co|liveleak\.com|soundcloud\.com/g)
			  && !domain.match( vp_allowed_embeds_regex ) 
		  )
		return err.html( '<div class="alert alert-danger">' +vp_lang.choose_valid_pin_domain+ '</div>' ).show();
		
		if( url.match(/instagram\.com|instagra\.me/g) ){
			site = 'instagram';
			eid = in_parser( url );
		}
		else if( url.match(/facebook\.com|fb\.com/g) ){
			if( url.match(/\/posts\/|\/activity\/|\/photo\.php\?|\/photos\/|permalink\.php|\/media\/|\/questions\/|\/notes\/|\/videos\/|\/video.\php\?/g) ){
				site = 'facebook';
				eid = fb_parser( url );
			}
			else{
				site = 'fbpage';
				eid = fb_parser( url );
			}
		}
		else if( url.match(/twitter\.com\/.*\/status/g) ){
			site = 'twitter';
			eid = tw_parser( url );
		}
		else if( url.match(/twitter\.com/g) ){
			site = 'twitter_profile';
			eid = tw_profile_parser( url );
		}
		else if( url.match(/pinterest\.com\/pin\//g) ){
			site = 'pinterest_pin';
			eid = pin_parser( url );
		}
		else if( url.match(/pinterest\.com\/([a-zA-Z0-9\_\-]+)\/([a-zA-Z0-9\_\-]+)/g) ){
			site = 'pinterest_board';
			eid = pin_parser( url );
		}
		else if( url.match(/pinterest\.com\/([a-zA-Z0-9\_\-]+)($|\/|\&)/g) ){
			site = 'pinterest_profile';
			eid = pin_parser( url );
		}
		else if( url.match(/plus\.google\.com/g) ){
			site = 'gplus';
			eid = pin_parser( url );
		}
		else if( url.match(/vine\.co/g) ){
			site = 'vine';
			eid = vn_parser( url );		
		}
		else if( url.match(/youtube\.com|youtu\.be/g) ){
			site = 'youtube';
			eid = yt_parser( url );
			type = 'video';
		}
		else if( url.match(/dailymotion\.com/g) ){
			type = 'video';
			site = 'dailymotion';
			eid = dm_parser( url );
		}
		else if( url.match(/vimeo\.com/g) ){
			type = 'video';
			site = 'vimeo';
			eid = vm_parser( url );
		}
		else if( url.match(/ted\.com/g) ){
			type = 'video';
			site = 'ted';
			eid = ted_parser( url );
		}
		else if( url.match(/bbc\.co/g) ){
			type = 'video';
			site = 'bbc';
			eid = bbc_parser( url );
		}
		else if( url.match(/liveleak\.com/g) ){
			type = 'video';
			site = 'liveleak';
			eid = lk_parser( url );
		}
		else {
			site = 'custom';
			eid = custom_parser( url );	
		}
		
		if( !eid )return err.html( '<div class="alert alert-danger">' +vp_lang.invalid_url+ '</div>' ).show();
			
		var parent = $('#' + $('#pin_url_elem').val() );
		var val_code = type+'|'+site+'|'+eid+'|'+pin_width+'|'+pin_height;
		
		add_embed( parent, val_code);
		parent.parents('.more_items:first').find('.more_details_btn').click();
		
		tb_remove();
		return false;
	});
	
	$(document).on( 'click', '.thumbnail_remove' , function(){
		var parent = $(this);
		var type = '';
		
		if( parent.hasClass( 'list_img') ) type = 'list';
		else if( parent.hasClass( 'list_video') ) type = 'video';
		else if( parent.hasClass( 'list_audio') ) type = 'audio';
		else if( parent.hasClass( 'ques_img') ) type = 'ques';
		else if( parent.hasClass( 'ans_img') ) type = 'ans';
		else if( parent.hasClass( 'exp_img') ) type = 'exp';
		else if( parent.hasClass( 'sum_img') ) type = 'sum';
		else if( parent.hasClass( 'pref_img') ) type = 'pref';
		
		if( parent.find('.vp-uploader-'+type).length > 0 ) {
			var vp_uploader = parent.find('.vp-uploader-'+type).eq(0);
    		var vp_uploader_nopad = parent.find('.vp-uploader-nopad-'+type).eq(0);
		}
		else if( parent.parents('.more_items_x:first').length > 0 ) {
			var vp_uploader = parent.parents('.more_items_x:first').find('.vp-uploader-'+type).eq(0);
			var vp_uploader_nopad = parent.parents('.more_items_x:first').find('.vp-uploader-nopad-'+type).eq(0);
		}
		else {
			var vp_uploader = parent.parents('.more_items:first').find('.vp-uploader-'+type).eq(0);
			var vp_uploader_nopad = parent.parents('.more_items:first').find('.vp-uploader-nopad-'+type).eq(0);
		}
		
		vp_uploader_nopad.find('.hide_details_btn').click();
		vp_uploader_nopad.find('.vp-uploader-image-'+type).eq(0).html('');
		vp_uploader_nopad.hide();
		vp_uploader.show();
		return false;
	});
	
	$(document).on( 'click', '.quiz_row' , function(){
		if( typeof poll_submit != 'undefined' ) {
			if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 )  && vp_allow_anon_votes == 0  ) {
				modal_open( jQuery('.login_modal_link') );
				resize_modal(350, 600);
				return false;	
			}	
		}
		var c = $(this).find('input[type="checkbox"]');
		if( c.is(':checked') == true ) {
			c.prop( 'checked', false );
			$(this).parents('.vp-entry:first').addClass( 'quiz-unchecked' );
		}
		else{
			$(this).parents('.quiz_ans_list').find('input[type="checkbox"]').prop( 'checked', false );
			c.prop( 'checked', true );
			$(this).parents('.vp-entry:first').removeClass( 'quiz-unchecked' );
			if( $('.quiz-unchecked').length > 0 ){
				scroll_to_next_additem( 'quiz-unchecked' );
			}
			
			if( typeof score_sheets != 'undefined' )
				check_quiz_answers( $(this) );
			else if( typeof poll_submit != 'undefined' )
				check_poll_answers( $(this) );
		}
	});
	
	$(document).on( 'change', '#fixed_answer' , function(){
		if( $(this).is(':checked') == true ) {
			quiz_fixed_ans = 1;
			$('.correct_answer').attr( 'disabled', false );
			set_quiz_results_dropdown( 'all' );
		}
		else {
			quiz_fixed_ans = 0;
			$('.correct_answer').attr( 'disabled', true );
			set_quiz_results_dropdown( 'all' );
		}
	});
	
	$(document).on( 'click', '.add_more_item' , function(){
		$('.more_items').eq(0).clone().appendTo('.more_items_holder');
		return false;
	});
	
	$(document).on( 'click', '.remove_preface' , function(){
		var p = $(this).parents( '.editor_preface:first' );
		$('#preface_title, #preface_desc').val( '' );
		tinyMCE.get('preface_desc').setContent( '' );
		p.find('.thumbnail_remove').click();
		$('.editor_add_preface').click();
	});
	
	$(document).on( 'click', '.remove_entry' , function(){
		var e = '.more_items';
		
		var is_results = $(this).hasClass('quiz_results');
		
		if( $(this).parents(e + ':first').length <= 0 )e = '.more_items_x';

		if( !is_results ) {
			if( quiz_fixed_ans ) delete_quiz_results_dropdown();
			else{
				$(this).parents(e + ':first').find('.more_items_x').each(function(){
					delete_quiz_results_dropdown();	
				});
			}
		}
		
		if( is_results ) {
			delete_quiz_results_personality_dropdown( parseInt( $(this).parents(e + ':first').find('.entry-no') ) );
		}
		
		var tn = $(this).parents( e + ':first' ).find( '.tinymce' );
		if( tn.length > 0 ) {
			remove_tinymce( tn );
		}
		
		var nextAll = $(this).parents(e + ':first').nextAll('.more_items');
		var nextAllx = $(this).parents(e + ':first').nextAll('.more_items_x');
		
		$(this).parents(e + ':first').slideUp(function(){
			$(this).remove();	
			nextAll.each(function(){
				var en = $(this).prevAll('.more_items').length;
				var en_n = $(this).prevAll('.more_items_numbered').length;
				if( $(this).hasClass( 'more_items_numbered' ) ) {
					$(this).find('.entry-no').eq(0).html( parseInt(en_n) );
				}
				$(this).find('.entry-no-val').eq(0).val( parseInt(en) );
			});
			
			nextAllx.each(function(){
				var en = $(this).prevAll('.more_items_x').length;
				$(this).find('.entry-no').eq(0).html( parseInt(en) );
				$(this).find('.entry-no-val').eq(0).val( parseInt(en) );
			});
		});
		return false;
	});
	
	$(document).on( 'click', '.remove_ans_entry' , function(){
		var p = $(this).parents('.more_items:first');
		$(this).parents('.more_items_x:first').slideUp(function(){
			$(this).remove();	
		});
		$(this).parents('.more_items_x:first').nextAll('.more_items_x').each(function(){
			var en = $(this).find('.entry-no').eq(0).text();
			$(this).find('.entry-no').eq(0).html( parseInt(en) - 1 );
		});
		set_ans_results_dropdown( p, true );
		if( !quiz_fixed_ans ) delete_quiz_results_dropdown();
		return false;
	});
	
	$(document).on( 'click', '.more_details_btn' , function(){
		var self = $(this);
		var target = $(this).parents('.more_items:first');
		var _scroll = 1;
		if( _scroll ) {
			target.find('.more_details').slideDown(function(){
				if( typeof prevent_item_scroll != 'undefined' && prevent_item_scroll == 1 ){}
				else { 
					var h = target.find('.hide_details_btn').offset().top;
					$("html, body").animate({ scrollTop: h }, 500);	
				}
			});	
		}
		self.hide();
		target.find('.hide_details_btn').show();	
		return false;
	});
	
	$(document).on( 'click', '.hide_details_btn' , function(){
		var self = $(this);
		var target = $(this).parents('.more_items:first');
		target.find('.more_details').slideUp();
		self.hide();
		target.find('.more_details_btn').show();
		return false;
	});
	
	$(document).on( 'click', '.show_details' , function(){
		$(this).hide();
		$('.hide_details').show();
		$('.more_items_hidden').slideDown();
		return false;
	});
	
	$(document).on( 'click', '.hide_details' , function(){
		$(this).hide();
		$('.show_details').show();
		$('.more_items_hidden').slideUp();
		return false;
	});
	
	$(document).on( 'click', '.add_text_item' , function(){
		add_new_entry( 'news' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_list_item' , function(){
		add_new_entry( 'list' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_meme_gen' , function(){
		var self = $(this);
		var id = self.parents('.vp-uploader-nopad').find('input[type="hidden"]').val();
		var elem = self.parents('.vp-uploader-nopad').find('input[type="hidden"]').attr( 'name' );
		
		tb_show(vp_lang.gen_meme, vp_ajaxurl+'?action=load_meme_modal&media_id=' + id + '&elem=' + elem ); 
		
		return false;
	});
	
	
	$(document).on( 'click', '.add_new_meme_gen' , function(){
		var op = 0;
		if( $(this).hasClass( 'open_list' ) ) op = 1;
		add_new_meme_gen( op );
		return false;
	});
	
	$(document).on( 'click', '.add_self_video_item' , function(){
		add_new_entry( 'video' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_video_item' , function(){
		add_new_entry( 'video' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_audio_item' , function(){
		add_new_entry( 'audio' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_self_audio_item' , function(){
		add_new_entry( 'audio' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_pin_item' , function(){
		add_new_entry( 'pin' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_gallery_item' , function(){
		add_new_entry( 'gallery' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_playlist_item' , function(){
		add_new_entry( 'playlist' );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_quiz_item' , function(){
		
		if( $(this).hasClass( 'no_quiz' ) || create_post == 'poll' ) {
			var elem = add_new_entry( 'poll' );
			$('#voting_till_span').show();
		}
		else var elem = add_new_entry( 'quiz' );
		
		add_quiz_answers( elem );
		add_quiz_answers( elem );
		add_quiz_answers( elem );
		scroll_to_last_additem();
		return false;
	});
	
	$(document).on( 'click', '.add_res_item' , function(){
		add_new_entry( 'results' );
		scroll_to_last_additem( 'more_items_x' );
		return false;
	});
	
	$(document).on( 'click', '.add_poll_item' , function(){
		var elem = add_new_entry( 'poll' );
		add_quiz_answers( elem );
		add_quiz_answers( elem );
		add_quiz_answers( elem );
		scroll_to_last_additem()
		return false;
	});
	
	$(document).on( 'click', '.toggle_tinymce' , function(){
		var elem = $(this).parents('.more_items:first').find('.tinymce');
		if( elem.length <= 0 )elem = $(this).parents('.more_items_x:first').find('.tinymce');
		toggle_tinymce( elem );
		return false;
	});

	$(document).on( 'click', '.editor_add_preface' , function( e ){
		e.preventDefault();
		jQuery('.editor_preface').slideToggle( function(){
			if( $('.editor_preface' ).is( ':hidden' ) == true ) {
				$( '.editor_add_preface' ).html( '<i class="glyphicon glyphicon-plus"></i>&nbsp;' + vp_lang.add_preface );	
			}
			else $( '.editor_add_preface' ).html( '<i class="glyphicon glyphicon-minus"></i>&nbsp;' + vp_lang.hide_preface );	
		});
	});
	
	$(document).on( 'click', '.editor_add_thumb' , function( e ){
		e.preventDefault();
		jQuery('.thumb_and_sub').slideToggle( function(){
			if( $('.thumb_and_sub' ).is( ':hidden' ) == true ) {
				$( '.editor_add_thumb' ).html( '<i class="glyphicon glyphicon-plus"></i>&nbsp;' + vp_lang.add_thumb );	
			}
			else $( '.editor_add_thumb' ).html( '<i class="glyphicon glyphicon-minus"></i>&nbsp;' + vp_lang.hide_thumb );	
		});
	});
		
	$(document).on( 'click', '.fb-share-quiz-res' , function(){
		tb_remove();
		var u = $(this).attr('data-href');
		var t = $(this).find('.fb-share-lg').text();
		FB.ui({
		  method: 'feed',
		  name: t,
		  link: u
		}, function(response){
			if( response && response.post_id ) {
				$('.quiz_hhh').show();	
				$('.mshare_quiz').hide();	
				$('.vp_correct_ans_t').addClass('vp_correct_ans');
				$('.vp_wrong_ans_t').addClass('vp_wrong_ans');
				
				jQuery.post( vp_ajaxurl, {
					'action': 'vp_quiz_taken',
					'post_id': jQuery('.quiz_title').attr('data-rel'),
					'_nonce': vp_ajax_nonce,
					'shared': response.post_id
				},function( response ){
				});	
			}
			else if( vp_share_quiz_force == 1 ) alert( vp_lang.must_share_quiz );	
		});
		return false;
	});
	
	jQuery(document).on( 'click', '.vp-fb-login-button' , function(){
		FB.login( function(response) {
			if( response.status == 'connected' ) {
				jQuery('.login-error').html('<div class="alert alert-info">' + vp_lang.login_success_wait + '</div>');
				jQuery.post(
					vp_ajaxurl, {
						'action': 'vp_fb_auth',
						'access_token': response.authResponse.accessToken ,
						'_nonce': vp_ajax_nonce
					}, function( response ) {
						var data = jQuery.parseJSON( response );
						if( data.error != '' )return  jQuery('.login-error').html('<div class="alert alert-error">' + data.error + '</div>');
						else{
							window.location.reload();	
						}
					}
				);	
			}
			else {
				$('.login-error').html('<div class="alert alert-danger">' + vp_lang.login_failed + '</div>');
			} 
		}, {scope: 'email'});
		return false;
	});
	
	jQuery(document).on( 'click', '.vp-tw-login-button' , function(){
		
	});
	
	jQuery(document).on( 'click', '.vp-gp-login-button' , function(){
		var myParams = {
			'clientid' : vp_google_oauth_id,
			'cookiepolicy' : 'single_host_origin',
			'callback' : 'OnGoogleAuth',
			'scope' : 'email profile'
		};
		gapi.auth.signIn(myParams);	
		return false;	
	});

	jQuery(document).on( 'click', '.delete_post_l' , function(){
		$('#publication').val('delete');
		$('#add_new_post_form').submit();
	});
	
	jQuery(document).on( 'click', '.cancel_delete_post_l' , function(){
		$(this).parents('div:first').hide();
	});
	
	jQuery(document).on( 'click', '.profile_post_search_i' , function(){
		if( $('.vp-hidden-search-from').is( ':hidden') == true )$(".vp-hidden-search-from").animate({width:'toggle'},350);
		else $('.profile_post_search_form').submit();
	});
	
	jQuery(document).on( 'change', '.vp_post_select_action' , function(){
		if( $(this).val() != '' ) $('.vp_mass_post_action_btn').click();
	});
	
	jQuery(document).on( 'click', '.vp_mass_post_action_btn' , function( e ){
		e.preventDefault();
		if( !confirm( vp_lang.confirm_action ) ) return;
		var action = $('select[name="vp_mass_post_action"]').val();
		if( action == '' )return;//alert( vp_lang.sel_mass_action );
		var post_ids = [];
		$( '.vp_post_sel' ).each( function(){
			if( $(this).is( ':checked' ) == true )post_ids.push( $(this).val() );
		});
		
		if( post_ids.length <= 0 )return alert( vp_lang.sel_at_one_post );
		
		$('.vp_mass_post_action_btn').hide();
		$('.vp_mass_post_loader').show();		
		
		vp_mass_post_action( post_ids, action );
	});
	
	jQuery(document).on( 'click', '.vp_single_post_publish' , function(){
		var post_id = $(this).parents('.vp_profile_post_grid:first').attr( 'data-rel' );
		
		$( '.vp_single_action_'+post_id ).hide();
		$( '.vp_single_loader_'+post_id ).show();
		
		vp_mass_post_action( Array(post_id), 'vp_mass_publish_post', post_id );
	});
	
	jQuery(document).on( 'click', '.vp_single_post_draft' , function(){
		var post_id = $(this).parents('.vp_profile_post_grid:first').attr( 'data-rel' );
		
		$( '.vp_single_action_'+post_id ).hide();
		$( '.vp_single_loader_'+post_id ).show();
		
		vp_mass_post_action( Array(post_id), 'vp_mass_draft_post', post_id );
	});
	
	jQuery(document).on( 'click', '.vp_single_post_delete' , function(){
		if( !confirm( vp_lang.confirm_action ) )return false;
		
		var post_id = $(this).parents('.vp_profile_post_grid:first').attr( 'data-rel' );
		
		$( '.vp_single_action_'+post_id ).hide();
		$( '.vp_single_loader_'+post_id ).show();
		
		vp_mass_post_action( Array(post_id), 'vp_mass_delete_post', post_id );
	});
	
	jQuery(document).on( 'click', '.vp_submit_post' , function(){
		try{
			tinyMCE.triggerSave();
		}catch(e){console.log(e);}
		$('.vp_submit_post').hide();
		$('.vp_submit_post_loader').show();
		$('.post_sub_err').remove();
		
		$.post( vp_ajaxurl, 
			$( '#add_new_post_form' ).serialize()
		, function( response ){
			window.onbeforeunload = '';
			$('.vp_submit_post_loader').hide();
			$('.vp_submit_post').show();
			
			var data = $.parseJSON( response );
			if( data.success == 1 ) {
				document.title = vp_lang.edit_post_title;
				$( ".submit_errors" ).html( data.message ).removeClass('alert-danger').addClass('alert-success').show();
				$( 'input[name="vp_post_id"]' ).val( data.post_id );
				
				$.each( data.child_ids , function ( k, v ) {
					$('.entry_post_id').eq( k ).val( v );
				});
				
				$.each( data.ans_ids , function ( k, v ) {
					$('.entry_ans_post_id').eq( k ).val( v );
				});
				try{window.history.replaceState( {} , document.title, data.edit_url);}catch(e){}
			}
			else {
				$(".submit_errors").html(data.error).removeClass('alert-success').addClass('alert-danger').show();
				for( i = 0; i < data.error_selectors.length; i++ ) {
					k = data.error_selectors[i];
					if( k > 0 )$(".more_items").eq( k ).prepend("<div class='alert alert-danger post_sub_err'>"+ data.error_selectors_msg[i] +"</div>");	
					else $(".more_results_holder").find(".more_items_x").eq( parseInt(-k) ).prepend("<div class='alert alert-danger post_sub_err'>"+ data.error_selectors_msg[i] +"</div>");
				}
			}
			
			scroll_to_next_additem( 'submit_errors' );	
		});
		return false;
	});
	
	jQuery(document).on( 'click', '.vp_post_sel_all' , function(){
		if( $(this).is( ':checked') == true ){
			$(".vp_post_sel").prop( 'checked', true );
		}
		else $(".vp_post_sel").prop( 'checked', false );
	});
	
	jQuery(document).on( 'click', '.poll_submit_btn' , function(){
		var poll_data = {};
		var self = $(this);
		self.attr( 'disabled', true );
		$('.poll-feedback').html( vp_lang.login_success_wait ).show();		
		$('.quiz_ans_list').each( function() {
			c = jQuery(this).find('input[type="checkbox"]:checked');
			if( c.length == 0 ){
				return $('.poll-feedback').html( '<div class="alert alert-danger">' + vp_lang.all_required + '</div>' );	
			}
			qu_id = c.parents( '.quiz_ans_list:first' ).attr( 'data-rel' );
			poll_data[qu_id] = c.parents( '.quiz_row:first' ).attr( 'data-rel' );
		});
		
		$('.red_title').removeClass('red_title');
		$.post( vp_ajaxurl, {
			'poll_votes': JSON.stringify( poll_data ),
			'poll_id': poll_id,
			'action': 'vp_poll_vote',
			'_nonce': vp_ajax_nonce
		}, function( response ){
			self.attr( 'disabled', false );
			var data = $.parseJSON( response );
			if( data.error != '' ) {
				$('.poll-feedback').html( '<div class="alert alert-danger">' + data.error + '</div>' );	
				if( data.error_elem >= 0 )$('.vp-entry').eq( data.error_elem - 1 ).find('.quiz_title').addClass('red_title');
				scroll_to_next_additem( 'red_title' );
			}
			else {
				$('.quiz_ans_list_obs').show();
				$('.poll_submit').hide();
				$('.poll-feedback').html( '<div class="alert alert-success">' + vp_lang.vote_done + '</div>' );
				print_poll_results( data.data );
			}	
		});
	});
	
	jQuery(document).on( 'click', '.edit-attachment' , function(){
		if( typeof create_post != 'undefined' || typeof show_media_editor != 'undefined' ){
			return false;
		}
	});
	
	jQuery(document).on( 'click', '.emoji_row_nosel > div' , function(){
		var self = $(this);
		var type = $(this).attr('rel');
		
		if( vp_emo_pending == 1 )return alert( vp_lang.pl_wait_action );
		
		var post_id = $(this).parents( '.emoji_row:first' ).attr( 'data-rel' );
		if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) && vp_allow_anon_votes == 0 ) {
			modal_open( jQuery('.login_modal_link') );
			resize_modal(350, 600);
			return false;	
		}
		
		vp_emo_pending = 1;
		
		if( self.hasClass( 'emoji_row_sel' ) ) {
			self.removeClass('emoji_row_sel');
			var prev = parseInt( self.find('.vbar_num').html() );
			self.find('.vbar_num').html( prev - 1 );
			refresh_emoji_react();	
		}
		//else if( $('.emoji_row_sel').length > 0 ) {
			//vp_action_pending = 0; 
			//return alert( vp_lang.withdraw_last_vote );			
		//}
		else {
			var rr = $('.emoji_row_sel').eq(0); 
			rr.removeClass( 'emoji_row_sel' );
			rr.find('.vbar_num').html( parseInt( rr.find('.vbar_num').html() - 1 ));
			self.addClass('emoji_row_sel');
			var prev = parseInt( self.find('.vbar_num').html() );
			self.find('.vbar_num').html( prev + 1 );
			refresh_emoji_react();	
		}
		
		$.post( vp_ajaxurl, {
			'post_id': post_id,
			'action': 'vp_post_react',
			'type': type,
			'_nonce': vp_ajax_nonce
		}, function( response ){
			setTimeout( vp_set_emo_action_done, 500 ); 
			var data = $.parseJSON( response );
			if( data.error != '' ) {
				alert( data.error );				
				if( self.hasClass( 'emoji_row_sel' ) ) {
					self.removeClass('emoji_row_sel');
					var prev = parseInt( self.find('.vbar_num').html() );
					self.find('.vbar_num').html( prev - 1 );
					refresh_emoji_react();	
				}
				else {
					self.addClass('emoji_row_sel');
					var prev = parseInt( self.find('.vbar_num').html() );
					self.find('.vbar_num').html( prev + 1 );
					refresh_emoji_react();	
				}
			}
			else {
				/*
				if( data.withdrawn == 1 ) {
					self.removeClass('emoji_row_sel');
					var prev = parseInt( self.find('.vbar_num').html() );
					self.find('.vbar_num').html( prev - 1 );
					refresh_emoji_react();
				}
				else {
					//$('.emoji_row_nosel').removeClass('emoji_row_nosel');
					self.addClass('emoji_row_sel');
					var prev = parseInt( self.find('.vbar_num').html() );
					self.find('.vbar_num').html( prev + 1 );
					refresh_emoji_react();
				}
				*/
			}	
		});
	});
	
	jQuery(document).on( 'mouseover', '.react_gif_img' , function(){
		var u = $(this).attr( 'data-gif-url' );
		$(this).attr( 'src', u );
	});
	
	jQuery(document).on( 'mouseout', '.react_gif_img' , function(){
		var u = $(this).attr( 'data-static-url' );
		$(this).attr( 'src', u );
	});
	
	
	jQuery(document).on( 'mouseover', '.vp_like_item' , function(){
		/*if($(this).hasClass( 'fa-thumbs-o-up' ))$(this).removeClass( 'fa-thumbs-o-up' ).addClass( 'fa-thumbs-up' );
		else if($(this).hasClass( 'fa-thumbs-up' ))$(this).removeClass( 'fa-thumbs-up' ).addClass( 'fa-thumbs-o-up' );*/
		$(this).css( 'color', 'brown' );
	});
	
	jQuery(document).on( 'mouseout', '.vp_like_item' , function(){
		/*if($(this).hasClass( 'noch' ))return $(this).removeClass( 'noch' );
		if($(this).hasClass( 'fa-thumbs-up' ))$(this).removeClass( 'fa-thumbs-up' ).addClass( 'fa-thumbs-o-up' );
		else if($(this).hasClass( 'fa-thumbs-o-up' ))$(this).removeClass( 'fa-thumbs-o-up' ).addClass( 'fa-thumbs-up' );*/
		$(this).css( 'color', '' );
	});
	
	jQuery(document).on( 'mouseover', '.vp_dislike_item' , function(){
		/*if($(this).hasClass( 'fa-thumbs-o-down' ))$(this).removeClass( 'fa-thumbs-o-down' ).addClass( 'fa-thumbs-down' );
		else if($(this).hasClass( 'fa-thumbs-down' ))$(this).removeClass( 'fa-thumbs-down' ).addClass( 'fa-thumbs-o-down' );*/
		$(this).css( 'color', 'brown' );
	});
	
	jQuery(document).on( 'mouseout', '.vp_dislike_item' , function(){
		/*if($(this).hasClass( 'noch' ))return $(this).removeClass( 'noch' );
		if($(this).hasClass( 'fa-thumbs-down' ))$(this).removeClass( 'fa-thumbs-down' ).addClass( 'fa-thumbs-o-down' );
		else if($(this).hasClass( 'fa-thumbs-o-down' ))$(this).removeClass( 'fa-thumbs-o-down' ).addClass( 'fa-thumbs-down' );*/
		$(this).css( 'color', '' );
	});
	
	jQuery(document).on( 'mouseover', '.vp_upvote_item' , function(){
		$(this).css( 'color', 'brown' );
	});
	
	jQuery(document).on( 'mouseout', '.vp_upvote_item' , function(){
		$(this).css( 'color', '' );
	});
	
	jQuery(document).on( 'mouseover', '.vp_downvote_item' , function(){
		$(this).css( 'color', 'brown' );
	});
	
	jQuery(document).on( 'mouseout', '.vp_downvote_item' , function(){
		$(this).css( 'color', '' );
	});
	
	jQuery(document).on( 'click', '.vp_upvote_item' , function(){
		var self = $(this);
		var rival = self.parents('.vp-op-au-4:first').find('.vp_downvote');
		var rvc = 0;
		var post_id = self.parents('.vp-op-au-4:first').attr('data-rel');
		var ac = post_id;
		
		if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) && vp_allow_anon_votes == 0 ) {
			tb_remove();
			setTimeout( function() {
				modal_open( jQuery('.login_modal_link') );
				resize_modal(350, 600);
			}, 400);			
			return false;	
		}
		
		if( vp_action_pending[ac] && vp_action_pending[ac] === 1 )return alert( vp_lang.pl_wait_action );
		
		
		vp_action_pending[ac] = 1;
		if( rival.hasClass( 'vp_list_vote_added' ) ) {
			rvc = 1;
			rival.removeClass( 'vp_list_vote_added' );
		}
		else if( self.hasClass( 'vp_list_vote_added' ) ) {
			rvc = 2;
			self.removeClass( 'vp_list_vote_added' );
		}
		
		if( rvc != 2 )self.addClass( 'vp_list_vote_added' );
		
		var le = self.parents('.vp-op-au-4:first').find('.vp_item_score_count');
		var l = parseInt( le.html() ); 
		
		if( rvc == 1 ) {
			l += 2;	
		}
		else if( rvc == 2 ) {
			l--;	
		}
		else l++;
		
		le.html( l );
		
		$.post( vp_ajaxurl, {
			'post_id': post_id,
			'action': 'vp_upvote_item',
			'_nonce': vp_ajax_nonce
		}, function( response ){
			setTimeout( function(){vp_set_action_done(ac)}, 500 );
			var data = $.parseJSON( response );
			if( data.error != '' ) {
				alert( data.error );
				self.removeClass('vp_list_vote_added');
				if( rvc == 1 ) { 
					rival.addClass( 'vp_list_vote_added' );	
					le.html( l-2 );
				}
				else if( rvc == 2 ) { 
					self.addClass( 'vp_list_vote_added' );	
					le.html( ++l );
				}
				else le.html( --l );
			}
			else {
				var tv = data.total_votes;
				le.html(tv);
				if( data.vote_withdrawn == 1 )self.removeClass( 'vp_list_vote_added' );
			}	
		});
	});
	
	jQuery(document).on( 'click', '.vp_downvote_item' , function(){
		var self = $(this);
		var rival = self.parents('.vp-op-au-4:first').find('.vp_upvote');
		var rvc = 0;
		var post_id = self.parents('.vp-op-au-4:first').attr('data-rel');
		var ac = post_id;
		
		if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) && vp_allow_anon_votes == 0 ) {
			tb_remove();
			setTimeout( function() {
				modal_open( jQuery('.login_modal_link') );
				resize_modal(350, 600);
			}, 400);			
			return false;	
		}
		
		if( vp_action_pending[ac] && vp_action_pending[ac] === 1 )return alert( vp_lang.pl_wait_action );
		
		vp_action_pending[ac] = 1;
		
		if( rival.hasClass( 'vp_list_vote_added' ) ) {
			rvc = 1;
			rival.removeClass( 'vp_list_vote_added' );
		}
		else if( self.hasClass( 'vp_list_vote_added' ) ) {
			rvc = 2;
			self.removeClass( 'vp_list_vote_added' );
		}
		
		if( rvc != 2 ) self.addClass( 'vp_list_vote_added' );
		
		var le = self.parents('.vp-op-au-4:first').find('.vp_item_score_count');
		var l = parseInt( le.html() ); 
		
		if( rvc == 1 ) {
			l -= 2;	
		}
		else if( rvc == 2 ) {
			l++;	
		}
		else l--;
		
		le.html( l );
		
		$.post( vp_ajaxurl, {
			'post_id': post_id,
			'action': 'vp_downvote_item',
			'_nonce': vp_ajax_nonce
		}, function( response ){
			setTimeout( function(){vp_set_action_done(ac)}, 500 );  
			var data = $.parseJSON( response );
			if( data.error != '' ) {
				alert( data.error );
				self.removeClass('vp_list_vote_added');
				if( rvc == 1 ){
					le.html( l+2 );
					rival.addClass( 'vp_list_vote_added' );	
				}
				else if( rvc == 2 ){
					le.html( ++l );
					rival.addClass( 'vp_list_vote_added' );	
				}
				else le.html( ++l );
			}
			else {
				var tv = data.total_votes;
				le.html(tv);
				if( data.vote_withdrawn == 1 )self.removeClass( 'vp_list_vote_added' );
			}	
		});
	});
	
	jQuery(document).on( 'click', '.vp_like_item' , function(){
		var self = $(this);
		var rival = self.parents('.vp-op-au-3:first').find('.vp_dislike_item');
		var rvc = 0;
		var post_id = self.parents('.vp-op-au-3:first').attr('data-rel');
		var ac = post_id;
		
		if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) && vp_allow_anon_votes == 0 ) {
			tb_remove();
			setTimeout( function() {
				modal_open( jQuery('.login_modal_link') );
				resize_modal(350, 600);
			}, 400);			
			return false;	
		}
		
		if( vp_action_pending[ac] && vp_action_pending[ac] === 1 )return alert( vp_lang.pl_wait_action );
		
		vp_action_pending[ac] = 1;
		
		if( rival.hasClass( 'vp_liked' ) ) {
			rvc = 1;
			rival.removeClass( 'vp_liked' );
		}
		else if( self.hasClass( 'vp_liked' ) ) {
			rvc = 2;
			self.removeClass( 'vp_liked' );
		}
		
		if( rvc != 2 )self.addClass( 'vp_liked' );
		
		
		var le = self.parents('.vp-op-au-3:first').find('.list_like_count');
		var ld = self.parents('.vp-op-au-3:first').find('.list_dislike_count');
		
		var l = parseInt( le.html() );
		var d = parseInt( ld.html() ); 
		
		if( rvc == 1 ) {
			l++;
			d--;	
		}
		else if( rvc == 2 ) {
			l--;	
		}
		else l++;
		
		le.html( l );
		ld.html( d );
		
		$.post( vp_ajaxurl, {
			'post_id': post_id,
			'action': 'vp_like_item',
			'_nonce': vp_ajax_nonce
		}, function( response ){
			setTimeout( function(){vp_set_action_done(ac)}, 500 );
			var data = $.parseJSON( response );
			if( data.error != '' ) {
				alert( data.error );
				self.removeClass('vp_liked');
				if( rvc == 1 ){
					le.html( --l );
					ld.html( ++d );
					rival.addClass( 'vp_liked' );
				}
				else if( rvc == 2 ){
					le.html( ++l );
					self.addClass( 'vp_liked' );
				}
				else le.html( --l );
			}
			else {
				var tl = data.total_likes;
				var td = data.total_dislikes;
				le.html(tl);
				de.html(td);
				if( data.vote_withdrawn == 1 )self.removeClass( 'vp_liked' );
			}	
		});
	});
	
	jQuery(document).on( 'click', '.vp_dislike_item' , function(){
		var self = $(this);
		var rival = self.parents('.vp-op-au-3:first').find('.vp_like_item');
		var rvc = 0;
		var post_id = self.parents('.vp-op-au-3:first').attr('data-rel');
		var ac = post_id;
	
		
		if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) && vp_allow_anon_votes == 0 ) {
			tb_remove();
			setTimeout( function() {
				modal_open( jQuery('.login_modal_link') );
				resize_modal(350, 600);
			}, 400);			
			return false;	
		}
		
		
		if( vp_action_pending[ac] && vp_action_pending[ac] === 1 )return alert( vp_lang.pl_wait_action );
		vp_action_pending[ac] = 1;
		
		if( rival.hasClass( 'vp_liked' ) ) {
			rvc = 1;
			rival.removeClass( 'vp_liked' );
		}
		else if( self.hasClass( 'vp_liked' ) ) {
			rvc = 2;
			self.removeClass( 'vp_liked' );
		}
		
		if( rvc != 2 )self.addClass( 'vp_liked' );
		
		var le = self.parents('.vp-op-au-3:first').find('.list_like_count');
		var ld = self.parents('.vp-op-au-3:first').find('.list_dislike_count');
		
		var l = parseInt( le.html() );
		var d = parseInt( ld.html() ); 
		
		if( rvc == 1 ) {
			l--;
			d++;	
		}
		else if( rvc == 2 ) {
			d--;	
		}
		else d++;
		
		le.html( l );
		ld.html( d );
		
		$.post( vp_ajaxurl, {
			'post_id': post_id,
			'action': 'vp_dislike_item',
			'_nonce': vp_ajax_nonce
		}, function( response ){
			setTimeout( function(){vp_set_action_done(ac)}, 500 );
			var data = $.parseJSON( response );
			if( data.error != '' ) {
				alert( data.error );
				self.removeClass('vp_liked');
				if( rvc == 1 ){
					ld.html( --d );
					le.html( ++l );
					rival.addClass( 'vp_liked' );
				}
				else if( rvc == 2 ){
					ld.html( ++d );
					self.addClass( 'vp_liked' );
				}
				else ld.html( --d );
			}
			else {
				var tl = data.total_likes;
				var td = data.total_dislikes;
				le.html(tl);
				ld.html(td);
				if( data.vote_withdrawn == 1 )self.removeClass( 'vp_liked' );
			}	
		});
	});
	
	jQuery(document).on( 'click', '.react_with_gif' , function(){
		
		if( ( vp_user_logged_in == '' || vp_user_logged_in == 0 ) ) {
			tb_remove();
			setTimeout( function() {
				modal_open( jQuery('.login_modal_link') );
				resize_modal(350, 600);
			}, 400);			
			return false;	
		}
		
		var self = $(this);
		
		if( self.hasClass( 'custom_gif' ) ) {
			var url = $('.vp_react_gif_url').val();	
			var comment = $('.vp_react_gif_comm').eq(0).val();
		}
		else {
			var url = $( '.gif_react_url' ).eq(1).attr( 'src' );
			var comment = $('.vp_react_gif_comm').eq(1).val();
		}
		var post_id = $( '#react_with_gif_post_id' ).val();
		
		if( url == '' ) {
			return alert( vp_lang.url_required_react );	
		}
		
		self.hide();
		$('.vp_react_gif_feed').html( vp_lang.login_success_wait ).show();
		
		scroll_to_next_additem( 'vp_react_gif_feed' );
		
		$.post( vp_ajaxurl, {
			'post_id': post_id,
			'action': 'vp_gif_react',
			'url': url,
			'comment': comment,
			'_nonce': vp_ajax_nonce
		}, function( response ){
			self.show();
			$('.vp_react_gif_feed').hide();
			try{
				var data = $.parseJSON( response );
				if( data.error != '' ) {
					alert( data.error );
					$('.vp_react_gif_feed').html( data.error ).show();
				}
				else {
					var r = Math.round( Math.random() * (9999 - 1000) + 1000 );
					var u = window.location.href.split('#')[0];
					if( u.indexOf("?") > -1 ) {
						u = u + '&' + r + '#comment-' + data.comment_id;	
					}
					else u = u + '?' + r + '#comment-' + data.comment_id;
					//window.location.href = data.comment_url ;
					window.location.href = u ;
					//location.reload();
				}	
			}catch( e ){
				alert( $( "<div/>" ).html( response ).text() );	
				$('.vp_react_gif_feed').html( response ).show();
			}
		});
	});	
	
	jQuery(document).on( 'click', '.vp-add-item' , function(){
		var type = $(this).attr( 'data-rel' );
		init_vp_editor( type );
		$( '#add_new_post_form' ).show();
		$( '.vp_editor_intro' ).hide();
	});
	
	jQuery(document).on( 'change', '.vp_ans_title_text' , function(){
		var v = $(this).val();
		var e = $(this).parents( '.more_items_x_numbered:first' ).find( '.entry-no-val' ).val();
		var ee = $( '.vp-person-opt-' + e ).eq(0).text();
		var eee = ee.split( ':' )[0].trim();
		$( '.vp-person-opt-' + e ).text( eee + ' : ' + v );
	});
});

function init_vp_editor( type )
{
	$ = jQuery;
	create_post = type.replace(/s+$/, "");
	if( create_post == 'new' ) create_post = 'news';
	
	if( typeof create_post != 'undefined' ) {
		document.title = vp_lang[create_post+'_title'];
		$('.post_entries').html( vp_lang[create_post+'_text1'] );
		$('.post_entries_2').html( vp_lang[create_post+'_text2'] );
		$( '.vp-for-'+create_post ).show();
		$( '.vp-not-for' ).show();
		$( '.vp-not-for-'+create_post ).hide();
		
		$('.editor_loader').hide();
		
		if( create_post == 'news' ){
			$('#show_numbers').prop( 'checked', false );
		}
		
		$(document).keyup( function( e ) {
			var regex = new RegExp("^[a-zA-Z0-9]+$");
    		var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    		if (regex.test(str)) {
        		window.onbeforeunload = vp_confirm_exit;
    		}
		});
	}
	
	if( typeof create_post != 'undefined' && typeof prevent_new_item == 'undefined' ){
		
		$("#vp_post_type").val(type);
		
		if( create_post == 'news')add_new_entry( 'news' );
		else if( create_post == 'image' )add_new_entry( 'list' );
		/*
		else if( create_post == 'video' || create_post == 'videos' ) {
			if( vp_self_video == 1 ) add_new_entry( 'video' );
			else add_new_entry( 'pin' );
		}
		else if( create_post == 'audio') {
			if( vp_self_audio == 1 ) add_new_entry( 'audio' );
			else add_new_entry( 'pin' );
		}
		*/
		else if( create_post == 'pin' || create_post == 'pins' || create_post == 'embed' || create_post == 'embeds' )add_new_entry( 'pin' );
		else if( create_post == 'gallery' )add_new_entry( 'gallery' );
		else if( create_post == 'playlist' )add_new_entry( 'playlist' );
		else if( create_post == 'quiz' ){
			var elem = add_new_entry( 'quiz' );
			add_quiz_answers( elem );
			add_quiz_answers( elem );
			add_quiz_answers( elem );
			add_new_entry( 'results' );
		}
		else if( create_post == 'poll' || create_post == 'polls' ){
			var elem = add_new_entry( 'poll' );
			add_quiz_answers( elem );
			add_quiz_answers( elem );
			add_quiz_answers( elem );
		}

		try{window.history.replaceState( {} , document.title, vp_create_url + '/?type='+ create_post );}catch(e){}
	}
}

function fb_sharer( url, image, cap )
{
	try{
		FB.ui({
		  method: 'feed',
		  link: url,
		  caption: unescape(cap),
		  picture: image
		}, function(response){
		});
	}catch(e){
		alert(e);	
	}
}

function vp_mass_post_action( post_ids, action, elem )
{
	jQuery('select[name="vp_mass_post_action"]').attr('disabled', true);
	
	jQuery.post( vp_ajaxurl, {
		'post_ids': post_ids.join(','),
		'action': action,
		'_nonce': vp_ajax_nonce
	}, function( response ){
		jQuery('select[name="vp_mass_post_action"]').val('').attr('disabled', false);

		if( elem == null ) {
			jQuery('.vp_mass_post_loader').hide();
			//jQuery('.vp_mass_post_action_btn').show();
		}
		else{
			jQuery( '.vp_single_loader_'+elem ).hide();
			jQuery( '.vp_single_action_'+elem ).show();	
		}
		var data = jQuery.parseJSON( response );
		if( data.error != '' )return alert( data.error );
		if( action == 'vp_mass_delete_post' ){
			var d = data.deleted.split(',');
			for( i = 0; i < d.length; i++ ) {
				jQuery('.p_'+d[i]).html('<div class="alert alert-info">' +vp_lang.deleted+ '</div>');	
			}
		}
		else if( action == 'vp_mass_publish_post' ){
			var d = jQuery.parseJSON( data.edited );
			jQuery.each( d, function( k, v ) {
				jQuery('.p_'+k).find( '.post_status' ).html( '<span class="label label-'+( v == 'publish' ? 'success' : 'danger' )+'">' + v + '</span>' );	
			});
		}
		else if( action == 'vp_mass_draft_post' ){
			var d = jQuery.parseJSON( data.edited );
			jQuery.each( d, function( k, v ) {
				jQuery('.p_'+k).find( '.post_status' ).html( '<span class="label label-'+( v == 'publish' ? 'success' : 'danger' )+'">' + v + '</span>' );	
			});
		}
		
		if( data.post_count != data.edit_count ) {
			alert( (data.post_count - data.edit_count) + ' ' + vp_lang.could_not_edit)	
		}
	});	
}

function GoggleOnLoad() {
	gapi.client.setApiKey( vp_google_api_key );
    gapi.client.load('plus', 'v1',function(){});
}

function OnGoogleAuth( googleUser ) {
	
	if(googleUser['status']['signed_in']) { 
		jQuery('.login-error').html('<div class="alert alert-info">' + vp_lang.login_success_wait + '</div>');
		jQuery.post(
			vp_ajaxurl, {
				'action': 'vp_google_auth',
				'access_token': googleUser.access_token ,
				'_nonce': vp_ajax_nonce
			}, function( response ) {
				var data = jQuery.parseJSON( response );
				if( data.error != '' )return  jQuery('.login-error').html('<div class="alert alert-error">' + data.error + '</div>');
				else{
					window.location.reload();
				}
			}
		);
    }
	else {
		if( googleUser['error'] != 'immediate_failed' )
		jQuery('.login-error').html('<div class="alert alert-danger">' + vp_lang.login_failed + '</div>');
	}   
}

function add_new_entry( type )
{
	jQuery('.post_entries').show();
	var count = 0;
	var count_numbered = 0;
	
	if( type == 'results' ){
		count_numbered = count = jQuery('.more_results_holder').find('.more_items_x').length;
	}
	else {
		count_numbered = jQuery('.more_items_holder').find('.more_items_numbered').length;
		count = jQuery('.more_items_holder').find('.more_items').length;
	}
	
	tareaid = 'tarea' + next_form_num;
	
	if( type == 'news' ) {	
		var data = vp_text_entry_template;
	}
	else if( type == 'list' ) {
		var data = vp_list_entry_template;	
	}
	else if( type == 'video' ) {
		var data = vp_video_entry_template;	
	}
	else if( type == 'audio' ) {
		var data = vp_audio_entry_template;	
	}
	else if( type == 'pin' ) {
		var data = vp_pin_entry_template;	
	}
	else if( type == 'gallery' ) {
		var data = vp_gallery_entry_template;	
	}
	else if( type == 'playlist' ) {
		var data = vp_playlist_entry_template;	
	}
	else if( type == 'quiz' ) {
		var data = quiz_entry_template;
	}
	else if( type == 'poll' ) {
		jQuery('.voting_till_span').show();
		var data = vp_poll_entry_template;
	}
	else if( type == 'results' ) {
		var data = quiz_result_entry_template;
		data = data.replace(/\[x_number\]/g, x_number);
		x_number++;
	}
	
	data = data.replace(/tareaid/g, tareaid);
	data = data.replace(/\[number\]/g, count_numbered);
	data = data.replace(/\[number_c\]/g, count);
	data = data.replace(/\[f_number\]/g, next_form_num);
	if( type == 'results' ){
		var ttt = '';
		jQuery('.more_results_holder').append(data);
		set_quiz_results_dropdown( 'quiz_res_from_'+next_form_num );
		set_quiz_results_dropdown( 'quiz_res_to_'+next_form_num );
		jQuery('.personality_res_ans').append('<option value="'+count_numbered+'" class="vp-person-opt-'+count_numbered+'">'+vp_lang.result+' - '+count_numbered+' : '+ ttt +'</option>');
	}
	else jQuery('.more_items_holder').append(data);
	next_form_num++;
	
	if(typeof tinyMCE != 'undefined' && jQuery('#' + tareaid).length > 0){	
		tinyMCE.init({
			menubar:false,
			statusbar: false,
			plugins : 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs',
			toolbar1 : 'bold,italic,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,spellchecker,fullscreen,wp_adv',
			toolbar2 : 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
		});
		tinyMCE.execCommand('mceAddEditor', false, tareaid); 
		tinyMCE.DOM.setStyle(tinyMCE.DOM.get(tareaid), 'height', 120 + 'px');
		tinyMCE.DOM.setStyle(tinyMCE.DOM.get(tareaid + '_ifr'), 'height', 120 + 'px');
	}
	
	if(typeof tinyMCE != 'undefined' && jQuery('#explain_' + tareaid).length > 0){	
		tinyMCE.init({
			menubar:false,
			statusbar: false,
			plugins : 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs',
			toolbar1 : 'bold,italic,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,spellchecker,fullscreen,wp_adv',
			toolbar2 : 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
		});
		tinyMCE.execCommand('mceAddEditor', false, 'explain_' + tareaid); 
		tinyMCE.DOM.setStyle(tinyMCE.DOM.get('explain_' + tareaid), 'height', 120 + 'px');
		tinyMCE.DOM.setStyle(tinyMCE.DOM.get('explain_' + tareaid + '_ifr'), 'height', 120 + 'px');
	}
	
	more_items_drag_drop( '.more_items' );
	
	if( type == 'quiz' && quiz_fixed_ans ) {
		set_quiz_results_dropdown( null, count );	
	}
	
	if( type == 'quiz' && !quiz_fixed_ans ) {
		jQuery('.correct_answer').attr( 'disabled', true );	
		$('.show_explain_ans, .quiz_ans_exp').hide();
	}
	
	if( ( type == 'quiz' || type == 'results' ) && quiz_type == 'person1' ) {
		jQuery('.quiz_res_from_score, .quiz_res_to_score').attr( 'disabled', true );
		jQuery('.for_personality_quiz').show();	
	}
	//else jQuery('.for_personality_quiz').hide();	
	
	if( jQuery('#show_numbers').is(':checked') == false ) {
		jQuery('.entry-show-num').prop('checked', false).attr( 'disabled', true ).trigger( 'change' );	
	}
	//else $('.entry-show-num').prop('checked', true).attr( 'disabled', false ).trigger( 'change' );
	
	if( type != 'quiz' && type != 'poll' )
		var added_elem = jQuery('#'+tareaid);
	else 
		var added_elem = jQuery('div[data-quiz-rel="'+ ( next_form_num - 1 ) +'"]');
		
	return added_elem;	
}

function add_quiz_answers( elem ) {
	if( next_form_num <= 0 )return false;
	
	var quiz_no = elem.attr('data-quiz-rel');
	var c = elem.find('.quiz_answer_entry').find('.more_items_x').length;
	c = c + 1;
	var t = quiz_answer_entry_template;
	t = t.replace(/\[number\]/g, quiz_no);
	t = t.replace(/\[f_number\]/g, quiz_no);
	
	t = t.replace(/\[number_1\]/g, c);
	t = t.replace(/\[f_number_1\]/g, next_ans_form_num);
	next_ans_form_num++;
	
	elem.find('.quiz_answer_entry').append( t );
	set_ans_results_dropdown( elem );
	
	if( !quiz_fixed_ans ){
		set_quiz_results_dropdown( null, jQuery(document).find('.quiz_answer_entry').find('.more_items_x').length ); 
	}
	
	if(quiz_type == 'person1'){
		set_person_results_dropdown( elem );
		jQuery('.for_personality_quiz').show();
	}
	
	return elem.find('.quiz_answer_entry').find('.more_items_x').eq( c-1 );
}

function set_person_results_dropdown( elem )
{
	var d = jQuery('.for_personality_quiz').eq(0);
	if( d.length > 0 ) {
		elem.find( '.personality_res_ans:last' ).html( d.html() );	
	}
}

function set_ans_results_dropdown( elem, c )
{
	var self_qu = parseInt( elem.find('.quiz_answer_entry').find('.more_items_x').length );
	if( c != null ) self_qu--;
	elem.find( '.correct_answer' ).html( '' );
	for( i = 1; i <= self_qu; i++ ) {
		elem.find( '.correct_answer' ).append( '<option value="'+ i +'">'+ i +'</option>' );
	}
}

function set_quiz_results_dropdown( elem, from )
{
	var c = quiz_fixed_ans ? 0 : 1;
	if( elem == 'all' ) {
		jQuery( '.quiz_res_from_score' ).html( '' );
		jQuery( '.quiz_res_to_score' ).html( '' );	
	}
	else if( elem != null ){
		jQuery( '#'+elem ).html( '' );	
	}
	
	if( from != null )c = from;
	l = quiz_fixed_ans ? jQuery('.more_items_holder').find('.more_items').length - 1 : jQuery(document).find('.quiz_answer_entry').find('.more_items_x').length; 
	
	for( i = c; i <= l; i++ ) {
		if( elem == 'all' || elem == null ) {
			jQuery( '.quiz_res_from_score' ).append( '<option value="'+ i +'">'+ i +'</option>' );
			jQuery( '.quiz_res_to_score' ).append( '<option value="'+ i +'">'+ i +'</option>' );		
		}
		else {
			jQuery( '#'+elem ).append( '<option value="'+ i +'">'+ i +'</option>' );
		}
	}
}

function delete_quiz_results_dropdown()
{
	var k = 0;
	jQuery('.quiz_res_from_score, .quiz_res_to_score').each( function(){
		k = 1;
		if( jQuery(this).val() == jQuery(this).find('option:last').attr('value') ){
			jQuery(this).find('option').eq(-2).attr('selected', 'selected')	
		}	
	});
	jQuery('.quiz_res_from_score').find('option:last').remove();
	jQuery('.quiz_res_to_score').find('option:last').remove();
}

function delete_quiz_results_personality_dropdown( no )
{
	//var k = 0;
	jQuery('.personality_res_ans[value="'+no+'"]').each( function(){
		//k = 1;
		//if( jQuery(this).val() == jQuery(this).find('option:last').attr('value') ){
		jQuery(this).find('option').eq(-2).attr('selected', 'selected')	
		//}	
	});
	jQuery('.personality_res_ans').find('option:last').remove();
}

function more_items_drag_drop(elem) {
	
	if( enable_drag_drop == 0 ) return;
	
	jQuery(elem).draggable({
		revert: "invalid",
		cursor: "move",
		helper: "clone",
		
	});
	jQuery(elem).droppable({
		drop: function(event, ui) {
			var dragged = jQuery(ui.draggable).find('.tinymce').eq(0).attr('id');
			
			var dragged_no = jQuery(ui.draggable).prevAll('.more_items').length;
			var dragged_no_numbered = jQuery(ui.draggable).prevAll('.more_items_numbered').length;
			
			var dropped_no = jQuery(this).prevAll('.more_items').length;
			var dropped_no_numbered = jQuery(this).prevAll('.more_items_numbered').length;
			
			if( jQuery(ui.draggable).hasClass( 'more_items_numbered' ) ) {
				jQuery(ui.draggable).find('.entry-no').eq(0).html( dropped_no_numbered );
			}
			jQuery(ui.draggable).find('.entry-no-val').eq(0).val( dropped_no );
			
			if( jQuery(this).hasClass( 'more_items_numbered' ) ) {
				jQuery(this).find('.entry-no').eq(0).html( dragged_no_numbered );
			}
			jQuery(this).find('.entry-no-val').eq(0).val( dragged_no );
			
			remove_tinymce( jQuery(this).find( '.tinymce' ) );
			remove_tinymce( jQuery('#' + dragged) );
			
			var jQuerydragElem = jQuery(ui.draggable).clone().replaceAll(this);
			jQuery(this).replaceAll(ui.draggable);
			
			reattach_tinymce( jQuery(this).find( '.tinymce' ) );
			reattach_tinymce( jQuery('#' + dragged) );
			
			more_items_drag_drop(this);
			more_items_drag_drop(jQuerydragElem);
		}
	});
}

function swap_elems( self, target, prev )
{
	var dragged = target.find('.tinymce').eq(0).attr('id');			
	var dragged_no = target.prevAll('.more_items').length;
	var dragged_no_numbered = target.prevAll('.more_items_numbered').length;
	
	if( prev == 1 && ( dragged_no_numbered == 0 || dragged_no == 0 ) ) return;
	
	var dropped_no = self.prevAll('.more_items').length;
	var dropped_no_numbered = self.prevAll('.more_items_numbered').length;
	
	if( target.hasClass( 'more_items_numbered' ) ) {
		target.find('.entry-no').eq(0).html( dropped_no_numbered );
	}
	target.find('.entry-no-val').eq(0).val( dropped_no );
	
	if( self.hasClass( 'more_items_numbered' ) ) {
		self.find('.entry-no').eq(0).html( dragged_no_numbered );
	}
	self.find('.entry-no-val').eq(0).val( dragged_no );
	
	remove_tinymce( self.find( '.tinymce' ) );
	remove_tinymce( jQuery('#' + dragged) );
	
	var jQuerydragElem = target.clone().replaceAll( self );
	self.replaceAll( target );
	
	reattach_tinymce( self.find( '.tinymce' ) );
	reattach_tinymce( jQuery('#' + dragged) );
	
	more_items_drag_drop( self );
	more_items_drag_drop(jQuerydragElem);	
}

function add_tinymce( elem ) {
	var id = elem.attr('id');
	tinyMCE.init({
		menubar:false,
		statusbar: false,
		plugins : 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs',
		toolbar1 : 'bold,italic,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,spellchecker,fullscreen,wp_adv',
		toolbar2 : 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
	});
	tinyMCE.execCommand('mceAddEditor', false, id); 
	tinyMCE.DOM.setStyle(tinyMCE.DOM.get(id), 'height', 120 + 'px');
	tinyMCE.DOM.setStyle(tinyMCE.DOM.get(id + '_ifr'), 'height', 120 + 'px'); 
}

function remove_all_tinymce( ) {
	$ = jQuery;
	$( '.tinymce' ).each( function( ) {
		try{
			remove_tinymce( $(this) );
		}catch(e){}
	});
}

function reattach_all_tinymce( ) {
	$ = jQuery;
	$( '.tinymce' ).each( function( ) {
		try{	
			reattach_tinymce( $(this) );
		}catch(e){}
	});
}

function remove_tinymce( elem ) {
	var id = elem.attr('id');
	tinyMCE.execCommand('mceRemoveEditor', false, id); 
}

function reattach_tinymce( elem ) {
	var id = elem.attr('id');
	tinyMCE.execCommand('mceAddEditor', false, id); 
}

function toggle_tinymce( elem ){
	var id = elem.attr('id');
	tinymce.execCommand('mceToggleEditor', false, id);
}

function extractDomain(url) {
    var domain;
    //find & remove protocol (http, ftp, etc.) and get domain
    if (url.indexOf("://") > -1) {
        domain = url.split('/')[2];
    }
    else {
        domain = url.split('/')[0];
    }

    //find & remove port number
    domain = domain.split(':')[0];

    return domain;
}

function yt_parser(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    return (match&&match[7].length==11)? match[7] : false;
}
function fb_parser(url){
    return url;
}
function ted_parser(url){
    var regExp = /src?=?\"([^#\s]*)\"/;
    var match = url.match(regExp);
	if(match&&match[1].length>0)return match[1];
	else{
		var regExp = /ted\.com\/talks\/([^#\&\?]*).*/;
		var match = url.match(regExp);
		if(match&&match[1].length>0)return 'https://embed-ssl.ted.com/talks/'+match[1]+'.html';	
	}
	return false;
}
function bbc_parser(url){
    var regExp = /src?=?\"([^#\s]*)\"/;
    var match = url.match(regExp);
    return (match&&match[1].length>0)? match[1] : url.replace(/embed$/, "").replace(/\/\s*$/, "")+'/embed';
}
function custom_parser(url){
    var regExp = /src?=?\"([^#\s]*)\"/;
    var match = url.match(regExp);
	if(match === null || match[1].length<=0){
		var regExp = /src?=?\'([^#\s]*)\'/;
   	 	var match = url.match(regExp);	
	}
    return (match&&match[1].length>0)? match[1] : url;
}
function lk_parser(url){
	var regExp = /src?=?\"([^#\s]*)\"/;
    var match = url.match(regExp);
    return (match&&match[1].length>0)? match[1] : false;	
}
function tw_parser(url){
    var regExp = /twitter\.com\/(.*)\/status\/(\d+)($|\/|\&)/;
    var match = url.match(regExp);
    return (match)? match[2] : false;
}
function tw_profile_parser(url){
    var regExp = /twitter\.com\/([a-zA-Z0-9\_\-]+)($|\/|\&)/;
    var match = url.match(regExp);
    return (match)? match[1] : false;
}
function in_parser(url){
    var regExp = /(instagram\.com|instagra\.me)\/p\/([a-zA-Z0-9\_\-]+)($|\/|\&)/;
    var match = url.match(regExp);
    return (match)? match[2] : false;
}
function pin_parser(url){
    return url;
}
function gplus_parser(url){
    return url;
}
function dm_parser(url){
    var regExp = /dailymotion\.com\/video\/([^_]+)/;
    var match = url.match(regExp);
    return (match)? match[1] : false;
}
function vm_parser(url){
    var regExp = /vimeo.com\/(\d+)($|\/|\&)/;
    var match = url.match(regExp);
    return (match)? match[1] : false;
}
function vn_parser(url){
    var regExp = /vine\.co\/v\/([^#\&\?]*).*/;
    var match = url.match(regExp);
    return (match)? match[1] : false;
}
function scloud_parser(url){
    var regExp = /soundcloud\.com\/tracks\/(\d+)($|\/|\&)/;
    var match = url.match(regExp);
    if( match )return 'tracks/' + match[1];
    else{
        var regExp = /soundcloud\.com\/playlists\/(\d+)($|\/|\&)/;
        var match = url.match(regExp);
        if( match )return 'playlists/' + match[1];
    }
    return false;
}

function get_pin_code( site, eid, small, w, h, elem_id )
{
	var vp_allowed_embeds_regex = new RegExp( vp_allowed_embeds, 'ig' );
	
    if(small && (w == null || h == null ) ){
        w = 400;
        h = 220;	
    }
    else if( (w == null || h == null ) ){
        w = 500;
        h = 280;
    }
    
    if( site == 'facebook' ){
        return '<div class="fb-post" data-href="'+eid+'" data-allowfullscreen="true" data-width="'+w+'" data-height="'+h+'"></div>';	
    }
	else if( site == 'fbpage' ){
        return '<div class="fb-page" data-tabs="timeline,events,messages" data-href="'+eid+'" data-allowfullscreen="true" data-width="'+w+'" data-height="'+h+'"></div>';	
    }
    else if( site == 'vine' ){
        return '<iframe src="https://vine.co/v/'+eid+'/embed/postcard" width="'+w+'" height="'+w+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }
    else if( site == 'instagram' ){
        return '<iframe src="https://instagram.com/p/'+eid+'/embed/" width="'+w+'" height="'+h+'" frameborder="0"></iframe>';
    }
    else if( site == 'twitter' ){
		if( typeof twttr != 'undefined' )
        	return '<script>twttr.widgets.createTweet(\''+eid+'\', document.getElementById(\''+elem_id+'\'));</script>';
		else 
			return '<blockquote class="twitter-tweet" vp_lang="en"><a href="https://twitter.com/user/status/'+eid+'"></a></blockquote>';
    }
    else if( site == 'twitter_profile' ){
		if( typeof twttr != 'undefined' )
       		return '<script>twttr.widgets.createTimeline(\''+eid+'\', document.getElementById(\''+elem_id+'\'));</script>';
		else 
			return '<a class="twitter-timeline" data-widget-id="'+eid+'"></a>';
    }
    else if( site == 'pinterest_pin' ){
        return '<a data-pin-do="embedPin" data-pin-width="large" href="'+eid+'"></a>';
    }
    else if( site == 'pinterest_board' ){
        return '<a data-pin-do="embedBoard" data-pin-board-width="'+w+'" data-pin-scale-height="'+h+'" data-pin-scale-width="80" href="'+eid+'"></a>';
    }
    else if( site == 'pinterest_profile' ){
        return '<a data-pin-do="embedUser" data-pin-board-width="'+w+'" data-pin-scale-height="'+h+'" data-pin-scale-width="80" href="'+eid+'"></a>';
    }
    else if( site == 'gplus' ){
        return '<div class="g-post" style="width:'+w+'px;height:'+h+'px" data-href="'+eid+'"></div>';
    }
	else if( site == 'youtube' ){
        return '<iframe width="'+w+'" height="'+h+'" src="https://www.youtube.com/embed/'+eid+'" frameborder="0"  webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }
	else if( site == 'ted' ){
        return '<iframe width="'+ w +'" height="'+ h +'" src="'+ eid +'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
	else if( site == 'bbc' ){
        return '<iframe width="'+ w +'" height="'+ h +'" src="'+ eid +'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
	else if( site == 'liveleak' ){
        return '<iframe width="'+ w +'" height="'+ h +'" src="'+ eid +'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
    else if( site == 'dailymotion' ){
        return '<iframe '+
                'src="//www.dailymotion.com/embed/video/'+eid+'" '+
                'width="'+w+'" '+
                'height="'+h+'" '+
                'frameborder="0" '+
                'allowfullscreen></iframe>';
    }
    else if( site == 'vimeo' ){
        return '<iframe src="https://player.vimeo.com/video/'+eid+'" width="'+w+'" height="'+h+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }
    else if( site == 'vine' ){
        return '<iframe src="https://vine.co/v/'+eid+'/embed/simple" width="'+w+'" height="'+w+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }
    else if( site == 'soundcloud' ){
        return '<iframe width="'+w+'" height="'+h+'" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/'+eid+'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>';
    }
	else if( eid.match( vp_allowed_embeds_regex ) ) {
        return '<iframe src="'+eid+'" width="'+w+'" height="'+h+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';	
	}
}

function get_video_embed_code( site, vid, small, w, h )
{
	var vp_allowed_embeds_regex = new RegExp( vp_allowed_embeds, 'ig' );
	
    if( (w == null || h == null ) ){
        w = 600;
        h = 400;
    }
    if( site == 'youtube' ){
        return '<iframe width="'+w+'" height="'+h+'" src="https://www.youtube.com/embed/'+vid+'" frameborder="0" allowfullscreen></iframe>';
    }
	else if( site == 'ted' ){
        return '<iframe width="'+ w +'" height="'+ h +'" src="'+ vid +'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
	else if( site == 'bbc' ){
        return '<iframe width="'+ w +'" height="'+ h +'" src="'+ vid +'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
	else if( site == 'liveleak' ){
        return '<iframe width="'+ w +'" height="'+ h +'" src="'+ vid +'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
    else if( site == 'ytaudio' ){
        //none
    }
    else if( site == 'facebook' ){
        return '<div class="fb-video" data-href="'+vid+'" data-allowfullscreen="true" data-width="'+w+'" data-height="'+h+'"></div>';	
    }
    else if( site == 'dailymotion' ){
        return '<iframe '+
                'src="//www.dailymotion.com/embed/video/'+vid+'" '+
                'width="'+w+'" '+
                'height="'+h+'" '+
                'frameborder="0" '+
                'allowfullscreen></iframe>';
    }
    else if( site == 'vimeo' ){
        return '<iframe src="https://player.vimeo.com/video/'+vid+'" width="'+w+'" height="'+h+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }
    else if( site == 'vine' ){
        return '<iframe src="https://vine.co/v/'+vid+'/embed/simple" width="'+w+'" height="'+w+'" frameborder="0"></iframe><script src="https://platform.vine.co/static/scripts/embed.js"></script>';
    }
    else if( site == 'soundcloud' ){
        return '<iframe width="'+w+'" height="'+h+'" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/'+vid+'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>';
    }
	else if( vid.match( vp_allowed_embeds_regex ) ) {
        return '<iframe src="'+vid+'" width="'+w+'" height="'+h+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';	
	}
}

if (!Date.now) {
    Date.now = function() { return new Date().getTime(); }
}

function scroll_to_last_additem( elem ) {
	if( typeof prevent_item_scroll != 'undefined' ) {
		if( prevent_item_scroll == 1 ) return;	
	}
	if( elem == null )elem = 'more_items';
	var hq = jQuery("."+elem+":last").offset().top
    jQuery("html, body").animate({ scrollTop: hq }, 500);
}

function scroll_to_next_additem( elem ) {
	if( typeof prevent_item_scroll != 'undefined' ) {
		if( prevent_item_scroll == 1 ) return;	
	}
	var hq = jQuery("."+elem).eq(0).offset().top
    jQuery("html, body").animate({ scrollTop: hq }, 500);
}


function add_news_entry( parent, title, desc )
{
	var elem = parent.parents('.more_items:first');
    elem.find('.entry_text').val( title );
	elem.find('.entry_desc').val( desc );
}

function add_thumb_image(parent, media_id, media_url, data)
{
	var type = '';
	if( parent.hasClass( 'list_img') ) type = 'list';
	else if( parent.hasClass( 'list_video') ) type = 'video';
	else if( parent.hasClass( 'list_audio') ) type = 'audio';
	else if( parent.hasClass( 'ques_img') ) type = 'ques';
	else if( parent.hasClass( 'ans_img') ) type = 'ans';
	else if( parent.hasClass( 'exp_img') ) type = 'exp';
	else if( parent.hasClass( 'sum_img') ) type = 'sum';
	else if( parent.hasClass( 'pref_img') ) type = 'pref';
	
	var vp_uploader = parent.find('.vp-uploader-'+type).eq(0);
    var vp_uploader_nopad = parent.find('.vp-uploader-nopad-'+type).eq(0);
	
	if( vp_uploader.length <= 0 ){
    	vp_uploader = parent.parents('.more_items_x:first').find('.vp-uploader-'+type).eq(0);
    	vp_uploader_nopad = parent.parents('.more_items_x:first').find('.vp-uploader-nopad-'+type).eq(0);	
	}
	if( vp_uploader.length <= 0 ){
		vp_uploader = parent.parents('.more_items:first').find('.vp-uploader-'+type).eq(0);
    	vp_uploader_nopad = parent.parents('.more_items:first').find('.vp-uploader-nopad-'+type).eq(0);
	}
	
    vp_uploader.hide();
    var target = vp_uploader.attr( 'data-target' );
    
    var html = '<input type="hidden" name="'+target+'" value="'+media_id+'"/>';
	
	if( type == 'video' ) {
		if( data != null ) html += data;
		vp_uploader_nopad.find('.vp-uploader-image-'+type).eq(0).html('<video controls src="'+media_url+'"></video><br/>'+html);	
	}
	else if( type == 'audio' ) {
		if( data != null ) html += data;
		vp_uploader_nopad.find('.vp-uploader-image-'+type).eq(0).html('<audio style="width:100%" controls src="'+media_url+'"></audio><br/>'+html);
	}
	else if( type != 'ans' )vp_uploader_nopad.find('.vp-uploader-image-'+type).eq(0).html('<img src="'+media_url+'"/><br/><br/>'+html);
	else {
		vp_uploader_nopad.find('.vp-uploader-lesspad-2-img').eq(0).css( 'background-image', 'url('+media_url+')');
		vp_uploader_nopad.find('.vp-uploader-image-'+type).eq(0).html(html);
	}
	vp_uploader_nopad.show();
	
	if(jQuery('.open_list_desc_more').length > 0){
		/***
		 * only single editor exists, so no need to relate with parents
		 */
		jQuery('.more_details_btn').hide();
		jQuery('.hide_details_btn').show();
		jQuery('.open_list_desc_more').slideDown( function(){
			scroll_to_next_additem( 'open_list_desc_more' );	
		});
	}
	
	parent.parents('.more_items:first').find('.more_details_btn').click();
}

function show_thumb_downloading( parent )
{
	var vp_uploader = parent.parents('.more_items_x:first').find('.vp-uploader').eq(0);
    var vp_uploader_nopad = parent.parents('.more_items_x:first').find('.vp-uploader-nopad').eq(0);	
    
	if( vp_uploader.length <= 0 ){
		var vp_uploader = parent.parents('.more_items:first').find('.vp-uploader').eq(0);
    	var vp_uploader_nopad = parent.parents('.more_items:first').find('.vp-uploader-nopad').eq(0);
	}
	
	vp_uploader.find('.default_text').hide();
	vp_uploader.find('.dload_text').show();
	
	vp_uploader_nopad.find('.default_text').hide();
	vp_uploader_nopad.find('.dload_text').show();
}

function hide_thumb_downloading( parent )
{
	var vp_uploader = parent.parents('.more_items_x:first').find('.vp-uploader').eq(0);
    var vp_uploader_nopad = parent.parents('.more_items_x:first').find('.vp-uploader-nopad').eq(0);	
    
	if( vp_uploader.length <= 0 ){
		var vp_uploader = parent.parents('.more_items:first').find('.vp-uploader').eq(0);
    	var vp_uploader_nopad = parent.parents('.more_items:first').find('.vp-uploader-nopad').eq(0);
	}
	
	vp_uploader.find('.dload_text').hide();
	vp_uploader.find('.default_text').show();
	
	vp_uploader_nopad.find('.dload_text').hide();
	vp_uploader_nopad.find('.default_text').show();
}

function add_embed(parent, code)
{
    var ecode = code.split('|');
    var type = ecode[0];
    var site = ecode[1];
    var vid = ecode[2];
    var w = ecode[3];
    var h = ecode[4];
    var rand_id = Date.now() + Math.floor((Math.random() * 100) + 1);
    
   // if( type == 'audio' || type == 'video' )
        //var embed_code = get_video_embed_code( site, vid, 1, w, h );
    //else 
    var embed_code = get_pin_code( site, vid, 1, w, h, rand_id);
        
    var vp_uploader = parent.parents('.more_items:first').find('.vp-uploader').eq(0);
    var vp_uploader_nopad = parent.parents('.more_items:first').find('.vp-uploader-nopad').eq(0);
    
    vp_uploader.hide();
    var target = vp_uploader.attr( 'data-target' );
    var html = '<input type="hidden" name="'+target+'" value="'+code+'"/>';
    vp_uploader_nopad.find('.vp-uploader-image').eq(0).html( '<div id="'+rand_id+'">'+embed_code + '</div><br/>'+html );
    vp_uploader_nopad.show();
	
	var ch = vp_uploader_nopad.find('.change-item-btn');
	ch.attr('data-config', code);
    
	if( (site == 'facebook' || site == 'fbpage') && typeof FB != 'undefined' ){
		FB.XFBML.parse( document.getElementById(rand_id) );	
	}
	else if( site.match( /pinterest/g)  && typeof refreshPin != 'undefined' ){
		refreshPin( document.getElementById(rand_id) );	
	}
	else if( site == 'gplus' && typeof gapi != 'undefined' ){
		gapi.post.go( document.getElementById(rand_id) );	
	}	 
}

function reverse_embed(parent, code) {
	var ecode = code.split('|');
    var type = ecode[0];
    var site = ecode[1];
    var vid = ecode[2];
    var w = ecode[3];
    var h = ecode[4];
	
	var url = '';
	
	var vp_allowed_embeds_regex = new RegExp( vp_allowed_embeds, 'ig' );
	
	if( site == 'youtube' )url = 'https://www.youtube.com/watch?v='+vid;
	else if( site == 'facebook' || site == 'fbpage' )url = vid;
	else if( site == 'ted' )url = vid;
	else if( site == 'bbc' )url = vid;
	else if( site.match(/pinterest/g) )url = vid;
	else if( site == 'instagram' )url = 'https://instagram.com/p/'+vid+'/';
	else if( site == 'twitter' )url = 'https://twitter.com/user/status/'+vid+'/';
	else if( site == 'twitter_profile' )url = 'https://twitter.com/widget/'+vid+'/';
	else if( site == 'vine' )url = 'https://vine.co/v/'+vid;
	else if( site == 'dailymotion' )url = 'https://dailymotion.com/video/'+vid+'_video';
	else if( site == 'vimeo' )url = 'https://vimeo.com/'+vid;
	else if( site == 'soundcloud' )url = 'http://soundcloud.com/'+vid+'/';
	else if( vid.match( vp_allowed_embeds_regex ) ) url = vid;
	
	parent.find('.w').val( parseInt(w) );
	parent.find('.h').val( parseInt(h) );
	parent.find('.url').val( url );
}

function resize_modal(w , h)
{
	if( w == null )var TB_WIDTH = 600;
	else var TB_WIDTH = w;
	
	if( h == null )var TB_HEIGHT = 500;
	else var TB_HEIGHT = h;
	
	var w = jQuery( window ).width();
	var h = jQuery( window ).height();
	
	if( TB_WIDTH > w-30 )TB_WIDTH = w-30;
	if( TB_HEIGHT > h-30 )TB_HEIGHT = h-30;
	
	jQuery("#TB_ajaxContent").animate({
		width: TB_WIDTH + 'px',
		height: ( TB_HEIGHT - 30 ) + 'px',
	});
	jQuery("#TB_window").animate({
		marginLeft: '-' + parseInt((TB_WIDTH / 2), 10) + 'px',
		width: TB_WIDTH + 'px',
		height: TB_HEIGHT + 'px',
		marginTop: '-' + parseInt( (TB_HEIGHT / 2), 10 ) + 'px'
	});
}

function check_poll_answers()
{
	var ok = 1;
	var c = null;
	
	jQuery('.quiz_ans_list').each( function() {
		c = jQuery(this).find('input[type="checkbox"]:checked');
		if( c.length == 0 )ok = 0;
	});
	
	if( ok == 1 ) {
		jQuery('.poll_submit').show();
		scroll_to_next_additem( 'poll_submit' );
	}
}

function check_quiz_answers( elem )
{
	var ok = 1;
	var score = 0 ;
	var t_score = 0;
	var total_marks = 0;
	var c = null;
	var score_elem = null;
	var sc = jQuery.parseJSON(score_sheets);
	var correct_ans_class = 'vp_correct_ans';
	var wrong_ans_class = 'vp_wrong_ans';
	var p = elem.parents('.vp-entry:first');
	var sc_occ = [];
	
	if( vp_share_quiz_force == 1 ) {
		correct_ans_class = 'vp_correct_ans_t';
		wrong_ans_class = 'vp_wrong_ans_t';
	}
	
	jQuery('.quiz_ans_list').each( function() {
		c = jQuery(this).find('input[type="checkbox"]:checked');
		if( c.length == 0 )ok = 0;
	});
	
	if( quiz_type == 'trivia' && p.find( '.quiz_ans_exp' ).length > 0 ) {
		p.find('.quiz_ans_exp').slideDown();	
		d = parseInt( elem.find('input[type="checkbox"]').val() );
		if( d%2 ) {
			elem.addClass( correct_ans_class );
		}
		else {
			elem.addClass( wrong_ans_class );
			elem.parents('.quiz_ans_list:first').find( '.quiz_row' ).each( function() {
				c = parseInt( jQuery(this).find('input[type="checkbox"]').val() );
				if( c%2 ) {
					jQuery(this).addClass( correct_ans_class );
					return false;						
				}
			});
		}
		p.find('.quiz_ans_list_obs').show();
	}
	
	if( ok == 1 ) {
		
		if( quiz_type == 'person1' ) {
			
			for( i = 0; i < sc.length; i++)sc_occ[i] = 0;
			
			jQuery('.quiz_ans_list').each( function() {
				c = jQuery(this).find('input[type="checkbox"]:checked');
				ddd = parseInt(c.attr( 'data-personal' ) ) - 1;
				sc_occ[ddd]++;
			} );
			
			max_occ_at = 0;
			max_occ = 0;
			for( i = 0; i < sc_occ.length; i++){
				v = sc_occ[i];
				if( v > max_occ ) {
					max_occ = v;
					max_occ_at = i;	
				}
			}
			
			score_elem = sc[max_occ_at];
			//console.log(max_occ_at, score_elem, sc_occ)
		}
		else {
			jQuery('.quiz_ans_list').each( function() {
				c = jQuery(this).find('input[type="checkbox"]:checked');
				d = parseInt( c.val() );
				total_marks++;
				if( score_sheet_fixed_ans == 1 ) {
					if( d%2 ) {
						score++;
						c.parents('.quiz_row:first').addClass( correct_ans_class );
					}
					else {
						c.parents('.quiz_row:first').addClass( wrong_ans_class );
						c.parents('.quiz_ans_list:first').find( '.quiz_row' ).each( function() {
							k = parseInt( jQuery(this).find('input[type="checkbox"]').val() );
							if( k%2 ) {
								jQuery(this).addClass( correct_ans_class );
								return false;						
							}
						});
					}
					if( quiz_type == 'mcq' && c.parents('.vp-entry:first').find( '.quiz_ans_exp' ).length > 0 ) {
						c.parents('.vp-entry:first').find( '.quiz_ans_exp' ).slideDown();
					}
				}
				else {
					score += d;
				}
			});
			
			t_score = score;
			
			for(i = 0; i < sc.length; i++ ) {
				if( sc[i].min_score <= score && sc[i].max_score >= score ) {
					score = -1;	
					score_elem = sc[i];
					break;
				}	
			}
			
			if( score >= 0 ) {
				score_elem = sc[ sc.length - 1];	
			}
		}
		
		if( score_sheet_fixed_ans == 1 )jQuery( '.score_score ' ).show();
		else jQuery( '.score_score ' ).hide();
		jQuery('.score_score').html( ' <span class="entry-no-lg">' + vp_lang.you_score + ' : ' + t_score + ' ' + vp_lang.out_of + ' ' + total_marks + '</span>' );
		if( score_sheet_fixed_ans == 1 )jQuery('.score_title').html( score_elem.title );
		else jQuery('.score_title').html( vp_lang.you_got + ' : ' + score_elem.title );
		
		if( score_elem.image != '' )jQuery('.score_image').html( '<img src="'+ score_elem.image+'" style="max-height:200px !important"/>' );
		jQuery('.score_desc').html( jQuery("<textarea/>").html( score_elem.desc ).text() + jQuery("<textarea/>").html( score_elem.source ).text() );
		
		if( vp_share_quiz_force == 1 )jQuery('.quiz_hhh').hide();
		
		modal_open( jQuery('.score_modal_link') );
		jQuery('#score_modal').show();
		window.location.href = '#score_modal';
		resize_modal( 600, 700 );
		jQuery('.shares').html( jQuery('.shares').html().replace( /\[myresult\]/g , score_elem.title ) );
		jQuery('.shares').html( jQuery('.shares').html().replace( /\[myscore\]/g , t_score ) );	
		jQuery('.quiz_ans_list_obs').show();
		
		if( vp_share_quiz_force == 0 ){
			jQuery.post( vp_ajaxurl, {
				'action': 'vp_quiz_taken',
				'post_id': jQuery('.quiz_title').attr('data-rel'),
				'_nonce': vp_ajax_nonce
			},function( response ){
			});	
		}
	}
}

function print_poll_results( data )
{
	jQuery('.poll-results').html( '' );
	data = jQuery.parseJSON( data );
	for( i = 0; i < data.length; i++ ) {
		tv = data[i].total_votes;
		
		jQuery('.poll-results').append( '<h4>' + data[i].title + '</h4>' );
		h = '<table style="width:100%" cellpadding="5" cellspacing="5">'; 
		for( j = 0; j < data[i].results.length; j++ ) {
			p = 0;
			if( tv > 0 ){ 
				p = ( data[i].results[j].votes / tv ) * 100;
				p = Math.round(p, 2);
			}
			if( p <= 25 ) {
				c = ' progress-bar-danger  ';		
			}
			else if( p <= 50 ) {
				c = ' progress-bar-warning  ';		
			}
			else if( p <= 75 ) {
				c = ' progress-bar-info  ';		
			}
			else {
				c = ' progress-bar-success  ';		
			}
			
			h += '<tr>'+
					( data[i].results[j].image == '' ? '' : '<td style="width:15%"><img style="width:75px" src="'+ data[i].results[j].image +'"/></td>' ) +
					'<td>'+ data[i].results[j].title +'  <div class="progress">'+
  					'<div class="progress-bar '+ c +' progress-bar-striped" role="progressbar" aria-valuenow="'+ p +'"'+
  					'aria-valuemin="0" aria-valuemax="100" style="width:'+ p +'%">'+
    					'<span class="sr-only">'+ p + '% '+ vp_lang.votes +'</span>'+
  					'</div>'+
				 '</div></td><td style="width:20%"><span class="entry-no">&nbsp;&nbsp;&nbsp;'+ data[i].results[j].votes +'</span> '+vp_lang.votes+'</td></tr>';
		}
		h += '</table>';
		jQuery('.poll-results').append( h );
	}
	jQuery('.poll-results-p').show();
}

function modal_open( elem )
{
	if( elem.length <= 0 ) {
		if( elem.selector == '.login_modal_link'  )alert( vp_lang.must_login );
		return false;	
	}
	
	var href = elem.attr('href');
	var title = elem.attr('title');
	title = title == null ? '' : title;
	tb_show( title, href, null );
}

function vp_help_slide()
{
	$ = jQuery;
	var speed = 5000;
	var run = setInterval('vp_slide_rotate()', speed);	
	
	var item_width = $('#vp-slides li').outerWidth(); 
	var left_value = item_width * (-1); 
	$('#vp-slides li:first').before($('#vp-slides li:last'));
	//$('#vp-slides ul').css({'left' : left_value});
	$('#vp-slides li').hide();  
	$('#vp-slides li:first').show();
			
	$('#vp-slides-prev').click(function() {
		 var left_indent = parseInt($('#vp-slides ul').css('left')) + item_width;
		$('#vp-slides ul:not(:animated)').animate({'left' : left_indent}, 200,function(){    
			$('#vp-slides li:first').before($('#vp-slides li:last'));  
			$('#vp-slides li').hide();  
			$('#vp-slides li:first').show();           
			//$('#vp-slides ul').css({'left' : left_value});
		});      
		return false; 
	});
	
	$('#vp-slides-next').click(function() {    
		var left_indent = parseInt($('#vp-slides ul').css('left')) - item_width;
		$('#vp-slides ul:not(:animated)').animate({'left' : left_indent}, 200, function () {
			$('#vp-slides li:last').after($('#vp-slides li:first'));                 	
			$('#vp-slides li').hide();  
			$('#vp-slides li:first').show();
			//$('#vp-slides ul').css({'left' : left_value});
		});
		return false;    
	});        
	
	$('#vp-slides').hover(    
		function() {
			clearInterval(run);
		}, 
		function() {
			run = setInterval('vp_slide_rotate()', speed);	
		}
	);
}

function vp_slide_rotate() {
	$ = jQuery;
	$('#vp-slides-next').click();
}

function normalize_login_page()
{
	$ = jQuery;
	var w = $(document).width();
	if( w >= 1100 ) {
		ww = check_viewport_config();
		if( ww.col4 < 350 ) {
			$('.vp_login').find('.col-lg-4').eq(1).removeClass('col-lg-4').addClass('col-lg-6');
			$('.vp_login').find('.col-lg-4').last().removeClass('col-lg-4').addClass('col-lg-3');
			$('.vp_login').find('.col-lg-4').first().removeClass('col-lg-4').addClass('col-lg-3');
		}
	}
}

function normalize_editor_page()
{
	$ = jQuery;
	var w = $(document).width();
	if( w >= 1100 ) {
		ww = check_viewport_config();
		if( ww.col3 < 225 ) {
			var style = jQuery('<style>div[class ^= "col-lg-"]{min-width:95%}</style>');
			jQuery('html > head').append(style);	
		}
	}
}

function normalize_profile_page()
{
	ww = check_viewport_config();
	if( ww.col3 > 225 ) {
		jQuery('.profile_post_search_form > .col-lg-2').removeClass( 'col-lg-2' ).addClass( 'col-lg-1' );
		jQuery('.profile_post_search_form > .col-lg-3').removeClass( 'col-lg-3' ).addClass( 'col-lg-2' );
		jQuery('.profile_post_search_form > ._blank').removeClass( 'col-lg-1' ).addClass( 'col-lg-3' );
	}
	else if( ww.col3 > 150 ) {
		jQuery('.profile_post_search_form > .col-lg-2').removeClass( 'col-lg-2' ).addClass( 'col-lg-1' );
		jQuery('.profile_post_search_form > .col-lg-3').removeClass( 'col-lg-3' ).addClass( 'col-lg-2' );
		jQuery('.profile_post_search_form > ._blank').removeClass( 'col-lg-1' ).addClass( 'col-lg-1' );
	}
}

function refresh_emoji_react()
{
	var sum = 0;
	$ = jQuery;
	$('.vbar_num').each(function(){
		n = parseInt( $(this).text() );
		if( !isNaN( n ) )sum += n;
	});
	$('.vbar_num').each(function(){
		n = parseInt( $(this).text() );

		if( !isNaN( n ) ) {
			if( sum == 0 ) {
				n = 0;
				sum = 1;	
			}
			h = ( Math.round( (n/sum) * 100 ) / 100 ) * 100;
			$(this).parents('.react_votes:first').find(".vbar").css("height", h + '%');
		}
	});
}

function check_viewport_config()
{
	$ = jQuery;
	$('.normalize_row1, .normalize_row2').remove();
	$('.vp_container_test').append('<div class="row normalize_row1" style="display:block;width:100%"><div class="col-lg-1"></div><div class="col-lg-2"></div><div class="col-lg-3"></div><div class="col-lg-6"></div></div>');
	$('.vp_container_test').append('<div class="row normalize_row2" style="display:block;width:100%"><div class="col-lg-4"></div><div class="col-lg-4"></div><div class="col-lg-4"></div></div>');
	
	var col1 = parseInt( $('.normalize_row1 > .col-lg-1').get(0).offsetWidth );
	var col2 = parseInt( $('.normalize_row1 > .col-lg-2').get(0).offsetWidth );
	var col3 = parseInt( $('.normalize_row1 > .col-lg-3').get(0).offsetWidth );
	var col4 = parseInt( $('.normalize_row2 > .col-lg-4').get(0).offsetWidth );
	var col6 = parseInt( $('.normalize_row1 > .col-lg-6').get(0).offsetWidth );
	
	$('.normalize_row1, .normalize_row2').hide();
	
	return { col1: col1, col2: col2, col3: col3, col4: col4, col6: col6 };
}

(function (original) {
  jQuery.fn.clone = function () {
	var result           = original.apply(this, arguments),
		my_textareas     = this.find('textarea').add(this.filter('textarea')),
		result_textareas = result.find('textarea').add(result.filter('textarea')),
		my_selects       = this.find('select').add(this.filter('select')),
		result_selects   = result.find('select').add(result.filter('select'));

	for (var i = 0, l = my_textareas.length; i < l; ++i) jQuery(result_textareas[i]).val(jQuery(my_textareas[i]).val());
	for (var i = 0, l = my_selects.length;   i < l; ++i) {
	  for (var j = 0, m = my_selects[i].options.length; j < m; ++j) {
		if (my_selects[i].options[j].selected === true) {
		  result_selects[i].options[j].selected = true;
		}
	  }
	}
	return result;
  };
}) (jQuery.fn.clone);

jQuery.fn.outerHTML = function(s) {
	return s
		? this.before(s).remove()
		: jQuery("<p>").append(this.eq(0).clone()).html();
};

function isFontAwesomeLoaded() {
    var span = document.createElement('span');
    span.className = 'fa';
    document.body.appendChild(span);
    var result = (span.style.fontFamily === 'FontAwesome');
    document.body.removeChild(span);
    return result;
}

function vp_open_list_tab_activate( elem )
{
	$ = jQuery;
	if( elem.hasClass( 'vp-op-list' )){
		c = '.vp-op-list';
		template = vp_openlist_list_template;
	}
	else if( elem.hasClass( 'vp-op-news' )){
		c = '.vp-op-news';
		template = vp_openlist_news_template;
	}
	else if( elem.hasClass( 'vp-op-embed' )){
		c = '.vp-op-embed';
		template = vp_openlist_embed_template;
	}
	else if( elem.hasClass( 'vp-op-video' )){
		c = '.vp-op-video';
		template = vp_openlist_video_template;
	}
	else if( elem.hasClass( 'vp-op-audio' )){
		c = '.vp-op-audio';
		template = vp_openlist_audio_template;
	}
	else if( elem.hasClass( 'vp-op-gallery' )){
		c = '.vp-op-gallery';
		template = vp_openlist_gallery_template;
	}
	else if( elem.hasClass( 'vp-op-playlist' )){
		c = '.vp-op-playlist';
		template = vp_openlist_playlist_template;
	}
	else if( elem.hasClass( 'vp-op-meme' )){
		c = '.vp-op-meme';
		template = vp_openlist_list_template;
	}
	
	try{
		remove_tinymce($('.tinymce'));
	}catch(e){}
	
	$('.op_editor').html('');
	elem = $('#vp-tab').find(c);
	elem.find('.op_editor').html(template);
	if($('.recap_openlist').length > 0){
		$($('.recap_openlist').html()).insertBefore(elem.find('.op_editor').find('.submit_open_list'));	
	}
	add_tinymce(elem.find('.op_editor').find('.tinymce'));
}

function get_gallery_editor_html( media_id, url, elem, size ) 
{
	var tt = elem.attr('data-target');
	return '<div class="vp_editor_gal_img vp-pull-left">'+
				'<div class="vp_editor_gal_img_overlay"><div><div class="vp_editor_gal_img_main" style="width:'+size+'px; height:'+size+'px; background-size:cover; background-image: url(\''+url+'\')"></div>'+
				'<input type="hidden" name="'+tt+'[]" value="'+media_id+'"/>'+
				'<div class="after">'+
					'<button class="btn btn-danger gal_img_remove" onclick="$(this).parents(\'.vp_editor_gal_img:first\').remove();return false;"><i class="glyphicon glyphicon-trash"></i></button><br/><br/>'+
					'<button class="btn btn-info gal_img_move_left"><i class="glyphicon glyphicon-arrow-left"></i></button>&nbsp;&nbsp;'+
					'<button class="btn btn-info gal_img_move_right"><i class="glyphicon glyphicon-arrow-right"></i></button>&nbsp;&nbsp;'+
				'</div>'+
			'</div>';
		
}

function get_playlist_editor_html( media_id, url, type, name, elem, size ) 
{
	var tt = elem.attr('data-target');
	return '<div class="vp_editor_gal_img vp-pull-left">'+
				'<div class="vp_editor_gal_img_overlay"><div><div class="vp_editor_gal_img_main" style="width:'+size+'px; height:'+size+'px; background-size:cover; background-image: url(\''+vp_img_dir_url+'/'+type+'.png\')"></div>'+
				'<input type="hidden" name="'+tt+'[]" value="'+media_id+'"/><br/>'+
				'<div class="vp_editor_gal_img_main" style="width:'+size+'px;overflow: hidden">'+
				name +
				'</div>'+
				'<div class="after">'+
					'<button class="btn btn-danger gal_img_remove" onclick="$(this).parents(\'.vp_editor_gal_img:first\').remove();return false;"><i class="glyphicon glyphicon-trash"></i></button><br/><br/>'+
					'<button class="btn btn-info gal_img_move_left"><i class="glyphicon glyphicon-arrow-left"></i></button>&nbsp;&nbsp;'+
					'<button class="btn btn-info gal_img_move_right"><i class="glyphicon glyphicon-arrow-right"></i></button>&nbsp;&nbsp;'+
				'</div>'+
			'</div>';
		
}

function vp_set_emo_action_done()
{
	vp_emo_pending = 0;
}

function vp_set_action_done( id )
{
	vp_action_pending[id] = 0;
}

function refresh_bp_noti()
{
	if( vp_user_logged_in == '' || vp_user_logged_in == 0 ) return;
	$ = jQuery;
	if( $('.vp-nav-welcome').length <= 0 )return;
	$.post( vp_ajaxurl, { 
		'action': 'vp_get_noti_count',
		'_nonce': vp_ajax_nonce
	}, function( response ){
		$('.open_list_editor_load_feed').hide();
		var data = $.parseJSON( response );
		$('.vp_save_loader').hide();
		$('.udata_form_btn').show();
		
		if( data.error != '' ) {
			//alert( data.error );
		}
		else {
			var tn = data.total_noti;
			var bn = data.bp_noti;
			var pn = data.post_noti;
			var cn = data.comment_noti;
			var on = data.op_noti;
			
			if( tn > 0 ) {
				$('.vp-nav-welcome').each(function(){
					if($(this).is('a'))$(this).addClass('vp_nbadge').attr('data-badge', tn);
					else $(this).find('a').eq(0).addClass('vp_nbadge').attr('data-badge', tn);
				})
			}
			if( bn > 0 ) {
				$('.vp-bp-noti').each(function(){
					if($(this).is('a'))$(this).addClass('vp_nbadge').attr('data-badge', bn);
					else $(this).find('a').eq(0).addClass('vp_nbadge').attr('data-badge', bn);
				})
			}
			if( pn > 0 ) {
				$('.vp-bp-posts').each(function(){
					if($(this).is('a'))$(this).addClass('vp_nbadge').attr('data-badge', pn);
					else $(this).find('a').eq(0).addClass('vp_nbadge').attr('data-badge', pn);
				})
			}
			if( cn > 0 ) {
				$('.vp-nav-post-comments').each(function(){
					if($(this).is('a'))$(this).addClass('vp_nbadge').attr('data-badge', cn);
					else $(this).find('a').eq(0).addClass('vp_nbadge').attr('data-badge', cn);
				})
			}
			if( on > 0 ) {
				$('.vp-bp-openlist').each(function(){
					if($(this).is('a'))$(this).addClass('vp_nbadge').attr('data-badge', on);
					else $(this).find('a').eq(0).addClass('vp_nbadge').attr('data-badge', on);
				})
			}
		}	
	});
}

function add_new_meme_gen( op )
{
	wp.media.view.l10n.insertIntoPost = vp_lang.gen_meme;
	wp.media.view.l10n.insertFromUrlTitle = vp_lang.upload_from_url;
	wp.media.view.l10n.insertMediaTitle = vp_lang.sel_img_meme;
	
	var frame = wp.media({
			frame: 'post',
			state: 'insert',
			library: {type: 'image'},
			multiple: false,
		});

	frame.on('insert', function() {
		var first = frame.state().get('selection').first().toJSON();
		
		if( first.type != 'image' ) return alert( 'image ' + vp_lang.vp_req );
		
		tb_show(vp_lang.gen_meme, vp_ajaxurl+'?action=load_meme_modal&media_id=' + first.id + '&elem=' + ( op == 1 ? 'open' : 'new' ) ); 
		setTimeout( function(){resize_modal( 1000, 550 )}, 500);
		
	});
	
	frame.open();
	
	$('.media-menu > a:nth-child(2)').hide();
	$('.media-menu > a:nth-child(3)').hide();
	$('.media-menu > a:nth-child(4)').hide();
	$('.media-menu > a:nth-child(6)').hide();
}

function vp_isMobile()
{
	var viewportWidth = jQuery(window).width();
	var viewportHeight = jQuery(window).height();

	var vp_isMobile = false;
    if( viewportWidth < 600 ) vp_isMobile = true;
	return vp_isMobile;	
}

function scale_jetpack_tiles()
{
	var viewportWidth = jQuery(window).width();
	var viewportHeight = jQuery(window).height();

	if( viewportWidth > 600 ) return;

	size = viewportWidth - 10;
	
	$ = jQuery;
	if( $('.tiled-gallery.type-square, .tiled-gallery.type-circle').length > 0 ) {
		var p = $('.tiled-gallery.type-square, .tiled-gallery.type-circle');
		p.attr( 'data-original-width', size );
		p.find( '.gallery-row' ).attr( 'data-original-width', size ).attr( 'data-original-height', size ).css( 'width', size+'px' ).css( 'height', size+'px');
		p.find( '.gallery-group' ).attr( 'data-original-width', size ).attr( 'data-original-height', size ).css( 'width', size+'px' ).css( 'height', size+'px');
		p.find( 'meta[itemprop="width"]' ).val( size - 5 );
		p.find( 'meta[itemprop="height"]' ).val( size - 5 );
		p.find( 'img' ).attr( 'startwidth', size - 5 ).attr( 'startheight', size - 5 ).css( 'width', (size-5)+'px' ).css( 'height', (size-5)+'px').css( 'max-width', (size-5)+'px' ).css( 'max-height', (size-5)+'px');	
	}
	if( $('.tiled-gallery.type-rectangular').length > 0 ) {
		var p = $('.tiled-gallery.type-rectangular');
		p.attr( 'data-original-width', size );
		p.find( '.gallery-row' ).attr( 'data-original-width', size ).attr( 'data-original-height', size ).css( 'width', size+'px' ).css( 'height', '');
		p.find( '.gallery-group' ).attr( 'data-original-width', size ).attr( 'data-original-height', size ).css( 'width', size+'px' ).css( 'height', '');
		p.find( 'meta[itemprop="width"]' ).val( size - 5 );
		p.find( 'meta[itemprop="height"]' ).val( size - 5 );
		p.find( 'img' ).attr( 'startwidth', size - 5 ).attr( 'startheight', size - 5 ).css( 'width', (size-5)+'px' ).css( 'height', '').css( 'max-width', (size-5)+'px' ).css( 'max-height', '');	
	}
}

function vp_confirm_exit()
{
	return vp_lang.sure_exit;
}

/**
 * tabs
 *
 * @description The Tabs component.
 * @param {Object} options The options hash
*/
var vp_tabs = function(options) {

	var el = document.querySelector(options.el);
	var tabNavigationLinks = el.querySelectorAll(options.tabNavigationLinks);
	var tabContentContainers = el.querySelectorAll(options.tabContentContainers);
	var activeIndex = 0;
	var initCalled = false;
	
	/**
	 * init
	 *
	 * @description Initializes the component by removing the no-js class from
	 *   the component, and attaching event listeners to each of the nav items.
	 *   Returns nothing.
	 */
	var init = function() {
	  if (!initCalled) {
		initCalled = true;
		el.classList.remove('no-js');
		
		for (var i = 0; i < tabNavigationLinks.length; i++) {
		  var link = tabNavigationLinks[i];
		  handleClick(link, i);
		}
	  }
	};
	
	/**
	 * handleClick
	 *
	 * @description Handles click event listeners on each of the links in the
	 *   tab navigation. Returns nothing.
	 * @param {HTMLElement} link The link to listen for events on
	 * @param {Number} index The index of that link
	 */
	var handleClick = function(link, index) {
	  link.addEventListener('click', function(e) {
		e.preventDefault();
		goToTab(index);
	  });
	};
	
	/**
	 * goToTab
	 *
	 * @description Goes to a specific tab based on index. Returns nothing.
	 * @param {Number} index The index of the tab to go to
	 */
	var goToTab = function(index) {
	  if (index !== activeIndex && index >= 0 && index <= tabNavigationLinks.length) {
		tabNavigationLinks[activeIndex].classList.remove('is-active');
		tabNavigationLinks[index].classList.add('is-active');
		tabContentContainers[activeIndex].classList.remove('is-active');
		tabContentContainers[index].classList.add('is-active');
		activeIndex = index;
	  }
	};
	
	/**
	 * Returns init and goToTab
	 */
	return {
	  init: init,
	  goToTab: goToTab
	};
}