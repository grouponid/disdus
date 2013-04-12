<link rel="stylesheet" href="<?php echo base_url(); ?>/files/css/sunny/jqueryui.css">
<script src="<?php echo base_url(); ?>/files/script/jquery.js"></script>
<script src="<?php echo base_url(); ?>/files/script/jqueryui.js"></script>
<div style="width:953px;margin-left:auto;margin-right:auto;background-color:#edeeee;height:600px;position:relative">

<script>
	$(function() {
		$( "#survey_progress" ).progressbar({
			value: <?php echo (isset($survey_progress))? $survey_progress : 0; ?>
		});
	});
</script>
<div style="float:right;margin-right:20px;margin-top:20px;font-size:12px;font-weight:bold;">
<div style="text-align:left;width:200px">Garis Kemajuan Survei<br /><div id="survey_progress" style="height:15px"></div></div>
</div>
<div style="clear:both;height:60px"></div>
<?php echo form_open('survey/submit_page/' . $survey['survey_code']); ?>
<div style="float:left;margin-left:20px;height:auto;font-family:'arial',georgia,serif;padding-right:5px;font-size:12px">



<?php 
	if(isset($survey_page['page_instructions']) && !empty($survey_page['page_instructions'])) 
	{
		echo "<div style='color:#00471c;height:auto;margin-bottom:30px'>" . $survey_page['page_instructions'] . "</div>";
	}
?>
<table>
<?php 
	$current_choices = array();
	$choices = array();
	$table_header = '<tr><td style="width:420px;margin-right:50px"></th>';
	$table_rows = '';
	foreach($questions as $key => $question)
	{
		$table_rows .=  "<tr>";
		$table_rows .= "<td style='width:420px;padding:5px;padding-right:100px;font-weight:bold;font-size:13px;'>" . $question['question_text'] . "</td>";
		if($question['question_type'] == 'choice_single')
		{
			$choices = json_decode($question['choices_text'], true);
			if(empty($current_choices))
			{
				$current_choices = $choices;
				$table_header .= "<td style='width:50px;color:#00471c;font-size:13px;font-weight:bold;text-align:center'>" . join("</td><td style='width:50px;color:#00471c;font-size:13px;text-align:center;font-weight:bold'>", $choices) . "</td>";
			}
			
			foreach($choices as $choice)
			{
				$table_rows .= "<td style='text-align:center'>" . form_radio($question['question_id'], $choice, '') . "</td>";
			}
		}
		$table_rows .= "</tr>";
	}
	$table_header .= '</tr>';
	echo $table_header . $table_rows;
?>
</table>
	<input type="hidden" name="next" value="<?php echo (isset($survey_page['page_next'])) ? $survey_page['page_next'] : 1; ?>" /> <input type="hidden" name="previous" value="<?php echo (isset($survey_page['page_previous'])) ? $survey_page['page_previous'] : 'instructions'; ?>" />
</div>
<div style="position: absolute; bottom:20px; left:20px"><input type="submit" name="goto" value="< Sebelumnya" style="border:0px;cursor:pointer;font-weight:bold;font-size:12px"/><input type="submit" name="goto" value="Berikutnya >" style="border:0px;cursor:pointer;font-weight:bold;font-size:12px"/></div>
<div style="position: absolute; bottom:20px; right:20px"><a href="<?php echo base_url(); ?>index.php/survey/take_survey/<?php echo (isset($survey_page['survey_id'])) ? $survey_page['survey_id'] : 0; ?>" style="font:bold 12px 'arial',georgia,serif; color:#000000; text-decoration:none">Cancel</a></div>
<?php echo form_close(); ?>
</div>