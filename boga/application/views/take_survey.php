<!DOCTYPE html>
<html>
<head>
	<title>Boga Survey</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>/files/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/files/css/sunny/jqueryui.css">
	<script src="<?php echo base_url(); ?>/files/script/jquery.js"></script>
	<script src="<?php echo base_url(); ?>/files/script/jqueryui.js"></script>
	<!--[if !IE]> -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>/files/css/custominput.css">
	<!-- <![endif]-->
	<!--[if IE]>
	<link rel="stylesheet" href="<?php echo base_url(); ?>/files/css/custominput-ie.css">
	<![endif]-->
</head>
<body>
<div id="background">
  <div id="container">
<?php 

  /////////////////////////////////////////
  //                                     //
  // LANDING PAGE                        //
  //                                     //
  /////////////////////////////////////////
	if(isset($landing_page))
	{
?>		
    <div id="header">
      <div style="position:absolute;margin-left:153px;float:left;width:240px">
        <div id="banner_container">
        <?php 
          if(isset($survey['survey_image']) && !empty($survey['survey_image']))
          {
        ?>
          <img id="survey_banner" src="<?php echo base_url(); ?>/files/img/<?php echo $survey['survey_image']; ?>"  />
        <?php
          }
        ?>
        </div>
      </div>
      <div id="survey_title_container">
        <?php echo (isset($survey['survey_name']))? $survey['survey_name'] : ''; ?>
      </div>
    </div>
    <div style="height:60px;padding-top:20px">
		<?php
		if(!empty($err))
    {
      $mesg = '';
      if($err == 'verify'){
        $mesg = "Kode pelanggan tidak valid!";
      }
      else if($err == 'reach_limit'){
        $mesg = "Kuota survei telah tercukupi!";
      }
      else if($err == 'session'){
        $mesg = "Sesi Anda telah habis!";
      }
      else if($err == 'no_user'){
        $mesg = "Sesi Anda telah habis!";
      }
      ?>
      <div style="position:absolute;margin:15px 20px 10px 20px;border: 1px solid red;border-radius:5px;width:910px;height:25px;background-color:#FAAFAF; ">
        <div style="margin-top:5px;margin-left:10px;color:#FF1F1F;font: bold 12px Arial;"><?php echo $mesg; ?></div>
      </div>
      <?php
    }
    ?>
    </div>
    <?php
    $survey_bonus = (isset($survey['survey_bonus'])) ? $survey['survey_bonus'] : '';
    
    $landing_page_instructions = '<div style="width:470px;margin-top:40px;margin-left:auto;margin-right:auto;">
    <p style="font: 19px Arial;">
      <strong>Terima kasih telah meluangkan waktu untuk memberikan pendapat mengenai kunjungan Anda di %BRAND%.</strong>
    </p>
    <p style="font: 19px Arial;">
      Isilah survey dengan lengkap dan dapatkan FREE ' . $survey_bonus . ' selama periode berlangsung.
    </p>
    <p style="font: 19px Arial;">
      Mohon masukkan nomor kode yang tertera pada struk transaksi Anda untuk dapat memulai survey.
    </p>
    <div style="font: 19px Arial;margin-top:38px;display:table">
      <div style="float:left">Kode Pelanggan: </div>
      <div style="float:left;margin-left:5px;height:21px;width:139px"><input type="text" name="user_code" id="input_user_code" /></div>
      <div id="input_btn" style="float:left;margin-left:10px;text-align:center;"><div style="font: bold 12px Arial;margin-top:8px">Masukkan</div></div>
    </div>
    </div>';
    
    echo form_open('survey/submit_page/' . $survey['survey_code']);
    echo str_replace('%BRAND%', $survey['survey_brand'], $landing_page_instructions);
    

		echo '<input type="hidden" name="goto" value="Berikutnya >" />';
		echo '<input type="hidden" name="next" value="1" />';
		
		//echo '<div style="position:absolute;bottom:211px;left:379px;height:21px;width:139px"></div>';
		//echo '<div id="input_btn" style="text-align:center;"><div style="font: bold 12px Arial;margin-top:8px">Masukkan</div></div>';
		echo form_close();
		?> 
		<script>
			$(function () {
				
				var banner = new Image();
				banner.src = $("#survey_banner").attr("src");
				
				banner.onload = function () {
					var newLeft = -banner.width/2;
					$("#banner_container")[0].style.left = newLeft+"px";
					console.log($("#banner_container")[0].style.left);
				}
			
				$('#input_btn').click(function () { 
					$('form').submit();
				});
			});
			
			
		</script>
		<?php
	}
  /////////////////////////////////////////
  //                                     //
  // FINISH PAGE                         //
  //                                     //
  /////////////////////////////////////////
	elseif(isset($finish_page))
	{
    ?>
		<div id="header">
      <div style="position:absolute;margin-left:153px;float:left;width:240px">
        <div id="banner_container">
        <?php 
          if(isset($survey['survey_image']) && !empty($survey['survey_image']))
          {
        ?>
          <img id="survey_banner" src="<?php echo base_url(); ?>/files/img/<?php echo $survey['survey_image']; ?>"  />
        <?php
          }
        ?>
        </div>
      </div>
      <div id="survey_title_container">
        <?php echo (isset($survey['survey_name']))? $survey['survey_name'] : ''; ?>
      </div>
    </div>
		<?php
    $survey_bonus = (isset($survey['survey_bonus'])) ? $survey['survey_bonus'] : '';
    $voucher_code = (isset($voucher_code)) ? $voucher_code : '';
    
    $finish_text = '<div style="width:470px;margin-top:100px;margin-left:auto;margin-right:auto;">
    <p style="font: 17px Arial;">
      <strong>Terima kasih atas bantuan Anda.</strong>
    </p>
    <p style="font: 14px Arial;">
      Yakinlah bahwa kami akan menggunakan komentar Anda untuk membuat %BRAND% menjadi tempat yang lebih baik untuk dikunjungi. Kami mengundang Anda untuk singgah di restaurant %BRAND% untuk menikmati <b>'.$survey_bonus.'</b>.
    </p>
    <p style="font: 14px Arial;">
      Cukup tuliskan nomor kode Voucher pada ruang yang disediakan pada tanda terima Anda dan serahkan ke staf bersama pesanan Anda.<br />
      
    </p>
    <p style="font: 17px Arial;">
      <strong>nomor kode Voucher:' . $voucher_code . '</strong>
    </p>
    <p style="font: 14px Arial;">
      Sampai jumpa lagi.
    </p>
    <p style="font: 14px Arial;">
      <strong>Hubungi Kami:</strong> Maukah Anda berhubungan langsung dengan perwakilan Layanan Pelanggan kami ? Harap ' . safe_mailto('support@boga.co.id', 'kirim email kepada kami') . ' bersama komentar Anda.
    </p>';
    echo str_replace('%BRAND%', $survey['survey_brand'], $finish_text);
		?>
      </div>
      <div style="clear:both;height:100px;"></div>
      <!--<div style="margin-bottom: 20px;padding:20px 20px 0px 20px;width:915px;height:25px">
        <div style="float:right;">
          <input type="button" onclick="window.open('', '_self', ''); window.close();" style="cursor:pointer;font:bold 12px 'Arial',Georgia,Serif; border:0px" value="Close" />
        </div>
      </div>-->
			<script>
        var banner = new Image();
        banner.src = $("#survey_banner").attr("src");
        
        banner.onload = function () {
          var newleft = -banner.width/2;
          $("#banner_container")[0].style.left = newleft+"px";
          console.log($("#banner_container")[0].style.left);
        }
				$('#input_btn').click(function () { 
					
					$('form').submit();
				});
			</script>
		<?php
	}
  /////////////////////////////////////////
  //                                     //
  // INSTRUCTION PAGE                    //
  //                                     //
  /////////////////////////////////////////
	elseif(isset($instruction_page))
	{
?>
	<div id="header">
		<div style="position:absolute;margin-left:153px;float:left;width:240px">
			<div id="banner_container">
			<?php 
				if(isset($survey['survey_image']) && !empty($survey['survey_image']))
				{
			?>
				<img id="survey_banner" src="<?php echo base_url(); ?>/files/img/<?php echo $survey['survey_image']; ?>"  />
			<?php
				}
			?>
			</div>
		</div>
		<div id="survey_title_container">
			<?php echo (isset($survey['survey_name']))? $survey['survey_name'] : ''; ?>
		</div>
	</div>
	<script>
		$(function() {
			var banner = new Image();
			banner.src = $("#survey_banner").attr("src");
			
			banner.onload = function () {
				var newleft = -banner.width/2;
				$("#banner_container")[0].style.left = newleft+"px";
				console.log($("#banner_container")[0].style.left);
			}
			$( "#survey_progress" ).progressbar({
				value: <?php echo (isset($survey_progress))? $survey_progress : 0; ?>
			});
		});
	</script>
	<div style="float:right;margin-right:20px;margin-top:20px;font-size:12px;font-weight:bold">
    <div style="text-align:left;width:200px">Garis Kemajuan Survei<br /><div id="survey_progress" style="height:15px"></div></div>
  </div>
	<div style="clear:both;height:100px"></div>
	<div style="float:left;margin-left:20px;height:445px">
    <?php echo $instructions; ?>
	</div>
	<?php echo form_open('survey/submit_page/' . $survey['survey_code'] . "/" . $current_page); ?>
    <div>
      <input type="hidden" name="next" value="1" /> 
      <input type="hidden" name="previous" value="0" />
    </div>
    <div style="clear:both;"></div>
    <div style="margin-bottom: 20px;padding:20px 20px 0px 20px;width:915px;height:25px">
      <div style="float:left;">
        <input type="submit" name="goto" value="Berikutnya >" id="next_btn" />
        <input type="submit" name="goto" value="< Sebelumnya" id="prev_btn" />
      </div>
      <div style="float:right;">
        <a href="<?php echo site_url('survey/take_survey/' . (isset($survey['survey_code'])) ? $survey['survey_code'] : ''); ?>" style="font:bold 12px 'Arial',Georgia,Serif; color:#000000; text-decoration:none">Cancel</a>
      </div>
    </div>
	<?php echo form_close(); ?>
<?php
	}
  /////////////////////////////////////////
  //                                     //
  // QUESTION PAGE                       //
  //                                     //
  /////////////////////////////////////////
	elseif(isset($question_page))
	{
?>
	<div id="header">
		<div style="position:absolute;margin-left:153px;float:left;width:240px">
			<div id="banner_container">
			<?php 
				if(isset($survey['survey_image']) && !empty($survey['survey_image']))
				{
			?>
				<img id="survey_banner" src="<?php echo base_url(); ?>/files/img/<?php echo $survey['survey_image']; ?>"  />
			<?php
				}
			?>
			</div>
		</div>
		<div id="survey_title_container">
			<?php echo (isset($survey['survey_name']))? $survey['survey_name'] : ''; ?>
		</div>
	</div>
	<script>
		$(function() {
			var banner = new Image();
			banner.src = $("#survey_banner").attr("src");
			
			banner.onload = function () {
				var newleft = -banner.width/2;
				$("#banner_container")[0].style.left = newleft+"px";
				console.log($("#banner_container")[0].style.left);
			}
			$( "#survey_progress" ).progressbar({
				value: <?php echo (isset($survey_progress))? $survey_progress : 0; ?>
			});
		});
	</script>
	<div style="float:right;margin-right:20px;margin-top:30px;font-size:12px;font-weight:bold;">
		<div style="text-align:left;width:200px">Garis Kemajuan Survei<br /><div id="survey_progress" style="height:15px"></div></div>
	</div>
	<?php 
    if(!isset($err) && !isset($error_msg))
    {
      ?>
      <div style="clear:both;height:60px;position:relative">
      </div>
      <?php
    }
    else
    {
      ?>
      <div style="clear:both;height:30px;position:relative">
      </div>
      <?php
      if(isset($err))
      {
        ?>
        <div style="margin:15px 20px 20px 20px;border: 1px solid red;border-radius:5px;width:910px;height:25px;background-color:#FAAFAF; ">
          <div style="margin-top:5px;margin-left:10px;color:#FF1F1F;font: bold 12px Arial;">Please fill in all question</div>
        </div>
        <?php 
      }
      if(isset($error_msg))
      {
        foreach($error_msg as $msg)
        {
          ?>
          <div style="margin:15px 20px 20px 20px;border: 1px solid red;border-radius:5px;width:910px;height:25px;background-color:#FAAFAF; ">
            <div style="margin-top:5px;margin-left:10px;color:#FF1F1F;font: bold 12px Arial;"><?php echo $msg; ?></div>
          </div>
          <?php
        }
      }
      ?>
      <div style="clear:both;height:10px;position:relative">
      </div>
      <?php
    }
  ?>
	<?php echo form_open('survey/submit_page/' . $survey['survey_code'] . '/' . $current_page); ?>
	<div style="float:left;width:910px;margin-left:20px;height:auto;min-height:400px;font-family:'Arial',Georgia,Serif;padding-right:5px;font-size:12px">
	<?php 
		if(isset($survey_page['page_instructions']) && !empty($survey_page['page_instructions'])) 
		{
			echo "<div style='color:#00471c;height:auto;margin-bottom:30px'>" . $survey_page['page_instructions'] . "</div>";
		}
    
		foreach($questions as $key => $question)
		{
			$name = "question_" . $question['question_id'];
			$value = set_value($name, (isset($response[$name])) ? $response[$name] : '');
			echo "<div id='" . $name . "'>";
			
			if($question['question_type'] == 'choice_single')
			{
				echo "<div class='question_title'>" . str_replace('%BRAND%', $survey['survey_brand'], $question['question_text']) . "</div>";
				$choices = json_decode($question['choices_text'], TRUE);
        $choice_str = '';
        
				foreach($choices as $key => $choice)
				{
					$choice_txt = $choice;
					if(is_numeric(strrpos($choice, "%TEXT%")))
					{
						$choice_txt = str_replace("%TEXT%", '', $choice_txt);
						$choice = str_replace("%TEXT%", form_input("choice_id:" . $question['question_id'] . '_' . $key, ''), $choice);
					}
					$value = (set_value($name, (isset($response[$name])) ? $response[$name] : '') == $choice_txt);
					
          if($question['question_orientation'] == 'horizontal') 
          {
            $choice_str .= "<td style='width:140px'>" .
            form_radio($name, $choice_txt,  $value, ' class="regular-radio" id="' . $name . '_' . $key . '"') . 
            "<label for='" . $name . "_" . $key . "'><span></span>" . $choice . "</label>" .
            "</td>";
          }
          else
          {
            $choice_str .= "<div style='margin-left:50px;margin-bottom:10px;height:25px;vertical-align:middle'>" . 
            form_radio($name, $choice_txt,  $value, ' class="regular-radio" id="' . $name . '_' . $key . '"') . 
            "<label for='" . $name . "_" . $key . "'><span></span>" . $choice . "</label></div>";
          }
				}
        
        if($question['question_orientation'] == 'horizontal')
        {
          echo "<table style='width:auto;margin-bottom:20px;margin-left:30px;'><tr>" . $choice_str . "</tr></table>";
        }
        else
        {
          echo "<div style='margin-bottom:20px;'>" . $choice_str . "</div>";
        }
			}
			if($question['question_type'] == 'choice_multiple')
			{
				echo "<div class='question_title'>" . str_replace('%BRAND%', $survey['survey_brand'], $question['question_text']) . "</div>";
				$choices = json_decode($question['choices_text'], TRUE);
        $choice_str = '';
        
				foreach($choices as $key => $choice)
				{
					$choice_txt = $choice;
					
					if(is_numeric(strrpos($choice, "%TEXT%")))
					{
						$choice_txt = str_replace("%TEXT%", '', $choice_txt);
						$choice = str_replace("%TEXT%", form_input("choice_id:" . $question['question_id'] . '_' . $key, set_value("choice_id:" . $question['question_id'] . '_' . $key, (isset($response["choice_id:" . $question['question_id'] . '_' . $key])) ? $response["choice_id:" . $question['question_id'] . '_' . $key] : '')), $choice);
					}
					$value = (in_array($choice_txt, set_value($name, (isset($response[$name])) ? $response[$name] : array())));
					
          
          if($question['question_orientation'] == 'horizontal') 
          {
            $choice_str .= "<td style='width:140px'>" . 
            form_checkbox($name . "[]", $choice_txt, $value, ' class="regular-checkbox" id="' . $name . '_' . $key . '"') .
            "<label for='" . $name . "_" . $key . "'><span></span>" . $choice . "</label></div>" .
            "</td>";
          }
          else
          {
            $choice_str .= "<div style='margin-left:50px;margin-bottom:10px;height:25px;vertical-align:middle'>" . 
            form_checkbox($name . "[]", $choice_txt, $value, ' class="regular-checkbox" id="' . $name . '_' . $key . '"') . 
            "<label for='" . $name . "_" . $key . "'><span></span>" . $choice . "</label></div>";
          }
				}
        if($question['question_orientation'] == 'horizontal')
        {
          echo "<table style='width:auto;margin-bottom:20px;margin-left:50px;'><tr>" . $choice_str . "</tr></table>";
        }
        else
        {
          echo "<div style='margin-bottom:20px;'>" . $choice_str . "</div>";
        }
			}
			if($question['question_type'] == 'short_text')
			{
				echo "<div class='question_title' style='width:30%;float:left'>" . str_replace('%BRAND%', $survey['survey_brand'], $question['question_text']) . "</div>";
				echo "<div style='width:60%;margin-left:20px;margin-bottom:10px;height:25px;vertical-align:middle;float:left'>" . form_input($name, $value, 'style="width:100%" id="' . $name . '"') . "</div>";
				echo "<div class='clear'></div>";
			}
			if($question['question_type'] == 'long_text')
			{
				echo "<div class='question_title' style='height:160px;width:35%;float:left'>" . str_replace('%BRAND%', $survey['survey_brand'], $question['question_text']) . "</div>";
				echo "<div style='width:60%;margin-left:20px;margin-bottom:10px;height:25px;float:left'>" . form_textarea($name, $value, 'style="width:100%" id="' . $name . '"') . "</div>";
				echo "<div class='clear'></div>";
			}
			echo "</div>";
		}
	?>
		<input type="hidden" name="next" id="next_page" value="<?php echo (isset($survey_page['page_next'])) ? $survey_page['page_next'] : 1; ?>" /> <input type="hidden" name="previous" id="prev_page" value="<?php echo (isset($survey_page['page_previous'])) ? $survey_page['page_previous'] : 'instructions'; ?>" />
	</div>
	<div style="clear:both;height:80px;"></div>
	<div style="margin-bottom: 20px;padding:20px 20px 0px 20px;width:915px;height:25px">
		<div style="float:left;">
			<input type="submit" name="goto" value="Berikutnya >" id="next_btn" />
			<input type="submit" name="goto" value="< Sebelumnya" id="prev_btn" />
		</div>
		<div style="float:right;">
			<a href="<?php echo site_url('survey/take_survey/' . (isset($survey['survey_code'])) ? $survey['survey_code'] : ''); ?>" style="font:bold 12px 'Arial',Georgia,Serif; color:#000000; text-decoration:none">Cancel</a>
		</div>
	</div>
	<?php echo form_close(); ?>
	
	<?php 
		if(isset($survey_page['page_script']) && !empty($survey_page['page_script'])) 
		{
			echo "<script>" . $survey_page['page_script'] . "</script>";
		}
	?>
<?php		
	}
?>
      </div>
    </div>
  </body>
</html>
