<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
		height:20px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	
	#questions p{
		margin: 20px;
	}
	</style>

</head>
<body>
	<script src="<?php echo base_url(); ?>/files/script/jquery.js"></script>
	<script src="<?php echo base_url(); ?>/files/script/jqueryui.js"></script>
<div id="container">
	<h1><div style="float:left;"><?php echo anchor('survey/admin_survey', '<', 'style="text-decoration:none"'); ?> Create Survey</div><div style="float:right;"><?php echo anchor('survey/do_logout', 'Logout'); ?></div></h1>

	<div id="body">
		<?php echo form_open('survey/do_create_survey'); ?>
		<p><div style="width:150px">Survey Name :</div> <input type="text" name="name" /></p>

		<p><div style="width:150px">URL :</div> <input type="text" name="url" style="width:350px" readonly="readonly" value="<?php echo site_url('survey/take_survey/starbucks01'); ?>" /></p>
		
		<p><div style="width:150px">Limit Correspondence :</div> <input type="text" name="limit" /></p>
		<div id="questions">
			<p>
				<div style="width:150px;margin-bottom:20px"><b>Question 1</b> <input type="checkbox" name="is_required_1" checked=""/> Required</div> 
				<div style="float:left">
					Text: <input type="text" name="question_text_1" class="question_text" style="width:250px" /> <br />
					<input type="radio" name="question_type_1" value="short_text" class="text" /> Short Text Response (max. 250 char)<br />
					<input type="radio" name="question_type_1" value="long_text" class="text" /> Long Text Response<br />
					<input type="radio" name="question_type_1" value="choice_single" id="choices_sr_1" class="multiple_choice" /> Multiple Choice Single Response<br />
					<div id="question_choicessr_1" style="padding:10px 0px 10px 25px; display:none"><input type="text" name="question_choicessr_1" class="choices" /><br />
					separates choices in semicolons (eg. Banana; Grape; etc)</div>
					<input type="radio" name="question_type_1" value="choice_multiple" id="choices_mr_1" class="multiple_choice" /> Multiple Choice Multiple Response<br />
					<div id="question_choicesmr_1" style="padding:10px 0px 10px 25px; display:none"><input type="text" name="question_choicesmr_1" class="choices" /><br />
					separates choices in semicolons (eg. Banana; Grape; etc)</div>
				</div>
				<div id="question_preview_1" style="margin-left:50px; float:left">
					<div id="question_text_preview_1"></div>
					<div id="question_type_preview_1" style="margin: 10px 0px 0px 10px;"></div>
				</div>
				<div style="height:10px;clear:both"></div>
			</p>
			
			<p id="question_2">
				<div style="width:150px;margin-bottom:20px"><b>Question 2</b> <input type="checkbox" name="is_required_2" checked=""/> Required</div> 
				<div style="float:left">
					Text: <input type="text" name="question_text_2" class="question_text" style="width:250px" /> <br />
					<input type="radio" name="question_type_2" value="short_text" class="text" /> Short Text Response (max. 250 char)<br />
					<input type="radio" name="question_type_2" value="long_text" class="text" /> Long Text Response<br />
					<input type="radio" name="question_type_2" value="choice_single" id="choices_sr_2" class="multiple_choice" /> Multiple Choice Single Response<br />
					<div id="question_choicessr_2" style="padding:10px 0px 10px 25px; display:none"><input type="text" name="question_choicessr_2" class="choices" /><br />
					separates choices in semicolons (eg. Banana; Grape; etc)</div>
					<input type="radio" name="question_type_2" value="choice_multiple" id="choices_mr_2" class="multiple_choice" /> Multiple Choice Multiple Response<br />
					<div id="question_choicesmr_2" style="padding:10px 0px 10px 25px; display:none"><input type="text" name="question_choicesmr_2" class="choices" /><br /> 
					separates choices in semicolons (eg. Banana; Grape; etc)</div>
				</div>
				<div id="question_preview_2" style="margin-left:50px; float:left">
					<div id="question_text_preview_2"></div>
					<div id="question_type_preview_2" style="margin: 10px 0px 0px 10px;"></div>
				</div>
				<div style="height:10px;clear:both"></div>
			</p>
		</div>
		<div style="clear:both"></div>
		<input type="hidden" id="num_questions" name="num_question" value="2" />
		<p><input type="button" id="add_question" style="cursor:pointer" value="Add Question" /></p>
		
		<p><div style="width:150px"></div> <input type="submit" value="Save and Continue" /></p>
		<?php echo form_close(); ?>

		
		<script>
			$(document).ready(function () {
				$('#add_question').click(function () {
					var num_questions = document.getElementById('num_questions');
					var next = parseInt(num_questions.value) + 1;
					var last_p = $("#questions p:last-child");
					last_p.after("<p id=\"question_" + next + "\">" + "<div style=\"width:150px;margin-bottom:20px\"><b>Question " + next + "</b> <input type=\"checkbox\" name=\"is_required_" + next + "\" checked=\"\"/> Required</div>" + 
					"<div style=\"float:left\">Text: <input type=\"text\" name=\"question_text_" + next + "\" class=\"question_text\" style=\"width:250px\" /> <br />" + 
					"<input type=\"radio\" name=\"question_type_" + next + "\" value=\"short_text\" class=\"text\" /> Short Text Response (max. 250 char)<br />" +
					"<input type=\"radio\" name=\"question_type_" + next + "\" value=\"long_text\" class=\"text\" /> Long Text Response<br />" + 
					"<input type=\"radio\" name=\"question_type_" + next + "\" value=\"choice_single\" id=\"choices_sr_" + next + "\" class=\"multiple_choice\" /> Multiple Choice Single Response<br />"+
					"<div id=\"question_choicessr_" + next + "\" style=\"padding:10px 0px 10px 25px; display:none\"><input type=\"text\" name=\"question_choicessr_" + next + "\" class=\"choices\" /><br /> separates choices in semicolons (eg. Banana; Grape; etc)</div>" +
					"<input type=\"radio\" name=\"question_type_" + next + "\" value=\"choice_multiple\" id=\"choices_mr_" + next + "\" class=\"multiple_choice\" /> Multiple Choice Multiple Response<br />"+
					"<div id=\"question_choicesmr_" + next + "\" style=\"padding:10px 0px 10px 25px; display:none\"><input type=\"text\" name=\"question_choicesmr_" + next + "\" class=\"choices\" /><br /> separates choices in semicolons (eg. Banana; Grape; etc)</div></div>"+
					"<div id=\"question_preview_" + next + "\" style=\"margin-left:50px; float:left\">"+
					"<div id=\"question_text_preview_" + next + "\"></div>"+
					"<div id=\"question_type_preview_" + next + "\" style=\"margin: 10px 0px 0px 10px;\"></div>"+
					"</div><div style=\"height:10px;clear:both\"></div>"+
					"</p>");
					num_questions.value = next;
					var last_p_position = last_p.position();
					
					window.scrollTo(0, last_p_position.top);
				});
				
				
				
				
			});
			
			$(document).on('change keyup keydown click focus blur', '.question_text', function() {
				var this_id = this.name.replace('question_text_','');
				if(this.value != '')
				{
					document.getElementById('question_text_preview_' + this_id).innerHTML = this_id + ". "+ this.value;
				}
			});
			
			$(document).on('change keyup keydown click', '.choices', function() {
				var this_id = this.name.replace('question_choicessr_','').replace('question_choicesmr_', '');
				var selected_input = $('input[name="question_type_' + this_id + '"]:checked');
				var type_input = "<input type='radio' name='preview_"+this_id+"'/>";
				console.log(selected_input[0]);
				if(selected_input[0].value == 'choice_multiple')
				{
					type_input = "<input type='checkbox' name='preview_"+this_id+"'/>";
				}
				
				if(this.value != '')
				{
					var choices = this.value.split(';');
					var choices_html = new Array();
					for(idx in choices)
					{
						var choice = $.trim(choices[idx]);
						if(choice != '')
						{
							choices_html[idx] =  type_input + $.trim(choices[idx]);
						}
					}
					document.getElementById('question_type_preview_' + this_id).innerHTML = choices_html.join('<br />');
				}
			});
			
			$(document).on('click', '.multiple_choice', function() {
				var this_id = this.name.replace('question_type_','');
				if(this.value == 'choice_single') {
					$('#question_choicesmr_'+this_id).slideUp();
					$('#question_choicessr_'+this_id).slideDown();
					$('.choices').click();
				}
				else
				{
					$('#question_choicessr_'+this_id).slideUp();
					$('#question_choicesmr_'+this_id).slideDown();
					$('.choices').click();
				}
			});
			
			$(document).on('click', '.text', function () {
				var this_id = this.name.replace('question_type_','');
				$('#question_choicessr_'+this_id).slideUp();
				$('#question_choicesmr_'+this_id).slideUp();
				if(this.value == 'short_text') 
				{
					document.getElementById('question_type_preview_' + this_id).innerHTML = "<input type='text' />";
				}
				else
				{
					document.getElementById('question_type_preview_' + this_id).innerHTML = "<textarea style='resize:none;height:70px'></textarea>";
				}
			});
			
		</script>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>