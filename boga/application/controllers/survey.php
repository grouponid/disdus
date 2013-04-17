<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Survey extends CI_Controller {
	var $t;
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->t['logged_user'] = $this->session->userdata('username');
		
		$function = $this->router->fetch_method();
		
		
		$without_login = array(
			'login' => 1,
			'do_login' => 1,
			'logout' => 1,
			'do_logout' => 1,
			'take_survey' => 1,
			'submit_page' => 1,
			'redeem' => 1,
			'check_voucher' => 1,
			'enter_code' => 1,
		);
		
		if(empty($this->t['logged_user']) && !array_key_exists($function, $without_login))
		{
			//$this->login();
			redirect('survey/login');
		}
	}
	
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function admin_survey()
	{
		$this->load->view('surveys');
	}
	
	public function enter_code($session_id=0)
	{
		if(empty($session_id))
		{
			echo "Failed!";
			return;
		}
		
	}
	
	public function redeem($voucher_code=0)
	{
		$voucher_query = $this->db->get_where('session', array('voucher_code' => $voucher_code));
		$voucher = $voucher_query->row_array();
		if(empty($voucher))
		{
			echo "Kode voucher tidak di temukan!";
			return;
		}
		
		if($voucher['session_status'] != 1)
		{
			echo "Tidak berhasil!";
		}
		else
		{
			$this->db->where('voucher_code', $voucher_code);
			$this->db->update('session', array('session_status' => 2));
			echo "Done";
		}
	}
	
	public function check_voucher($voucher_code=0)
	{
		$voucher_query = $this->db->get_where('session', array('voucher_code' => $voucher_code));
		$voucher = $voucher_query->row_array();
		if(empty($voucher))
		{
			echo "Kode voucher tidak di temukan!";
			return;
		}
		
		if($voucher['session_status'] == 1)
		{
			echo "Valid";
		}
		else if($voucher['session_status'] == 2)
		{
			echo "Sudah di redeem!";
		}
		else if($voucher['session_status'] == 0)
		{
			echo "Belum menyelesaikan survei!";
		}
	}
	
	public function login()
	{
		$this->load->view('login', $this->t);
	}
	
	public function do_login()
	{
		$input = $this->input->post(NULL, TRUE);
		
		if($input['username'] != 'michael' && $input['password'] != 'michael')
		{
			$this->t['err']['auth_fail'] = true;
			$this->login();
		}
		else 
		{
			$this->session->set_userdata('username', 'michael');
		}
		redirect('survey/admin_survey');
	}
	
	public function do_logout()
	{
		$this->session->unset_userdata('username');
		redirect('survey/logout');
	}
	
	public function logout()
	{
		$this->load->view('logout');
	}
	
	public function	create_survey()
	{
		$this->load->library('javascript');
		$this->load->view('create_survey');
	}
	
	public function do_create_survey()
	{
		$input = $this->input->post(NULL, TRUE);
		
		print_r($input);
	}
	
	public function take_survey($survey_code='', $page=0)
	{
		//$page = $this->uri->segment(4, 0);
		
		$this->load->driver('cache');
		$this->load->helper('cookie');
		
		if(!empty($page) && (get_cookie($survey_code) === FALSE) )
		{
      if($page != 'finish')
      {
        redirect('survey/take_survey/' . $survey_code);
      }
		}
		//if ( ! $survey_page = $this->cache->file->get($survey_code . "_" . $page))
		{
			 $survey_page = $this->render_survey_page($survey_code, $page);

			 // Save into the cache for 5 minutes
			 //$this->cache->file->save($survey_code . "_" . $page, $survey_page, 3600);
		}

		echo $survey_page;
	}
	
	private function render_survey_page($survey_code='', $page=0)
	{
		if(empty($survey_code))
		{
			echo show_404();
		}
		$query = $this->db->get_where('survey', array('survey_code' => $survey_code));
		$survey = $query->row_array();
		
		if(empty($survey))
		{
			show_404();
		}
		
		//$page = $this->uri->segment(4, 0);
		$get  = $this->input->get(NULL, TRUE);

		$content = array();
		$content['survey'] = $survey;
		
		// landing page
		if(empty($page))
		{
      $err = (isset($_GET['err'])) ? $_GET['err'] : '';
      $content['err'] = $err;
			$content['landing_page'] = true;
		}
		// instruction page
		elseif($page == 'instructions') 
		{
			$survey_page_query = $this->db->get_where('survey_page', array('survey_id' => $survey['survey_id']), 1);
			$survey_page = $survey_page_query->row_array();
			
			if(empty($survey_page)) // if survey page not found
			{
				show_404();
			}
			
			$content['current_page'] 		= $page;
			$content['survey_page'] = $survey_page;
			$content['instruction_page'] = true;
			$content['progress_bar'] = 0;
			$content['instructions'] = $survey['instructions'];
		}
		// question page
		elseif( is_numeric($page) && $page > 0)
		{
			$this->db->where('survey_id', $survey['survey_id']);
			$this->db->from('survey_page');
			$num_survey_page = $this->db->count_all_results();
		
			$survey_page_query = $this->db->get_where('survey_page', array('survey_id' => $survey['survey_id'], 'page_num' => $page), 1);
			$survey_page = $survey_page_query->row_array();
			
			if(empty($survey_page)) // if survey page not found
			{
				show_404();
			}
			
			$this->db->from('question');
			$this->db->where(array('survey_id' => $survey['survey_id'], 'page_id' => $survey_page['page_id']));
			$this->db->order_by('question_id', 'ASC');
			$questions_query = $this->db->get();
			$questions = $questions_query->result_array();
			
			$content['current_page'] 		= $page;
			$survey_page['page_next'] 		= (isset($get['next'])) ? $get['next'] : $survey_page['page_next'];
			$survey_page['page_previous'] 	= (isset($get['prev'])) ? $get['prev'] : $survey_page['page_previous'];
			$content['survey_page']			= $survey_page;
			$content['questions'] 			= $questions;
			$content['question_page'] 		= true;
			$content['survey_progress'] 	= ($num_survey_page) ? $page * 100 / $num_survey_page : 0;
			
			$session_path 					= $this->config->item('session_path');
			$session_id 					= get_cookie($survey_code);
			
			$this->load->helper('file');
			
			if(file_exists($session_path . "/" . $session_id))
			{
				$content['response'] = json_decode(read_file($session_path . "/" . $session_id), TRUE);
			}
		}
		// finish page
		elseif($page == 'finish')
		{
      $voucher_code = (isset($_GET['voucher_code'])) ? $_GET['voucher_code'] : '';
      
      $this->db->from('session');
			$this->db->where('voucher_code', $voucher_code);
			$this->db->where('survey_id', $survey['survey_id']);
			$voucher_query = $this->db->get();
			$voucher = $voucher_query->row_array();
      
      if(empty($voucher) || empty($voucher_code))
      {
        show_404();
      }
      
      $content['voucher_code'] = $voucher_code;
			$content['finish_page'] = true;
			$content['progress_bar'] = 100;
		}
		
		// show error
		if(isset($this->t['err']) || isset($_GET['err']))
		{
			$content['err'] = TRUE;
		}
    
		if(isset($this->t['error_msg']))
		{
			$content['error_msg'] = $this->t['error_msg'];
		}
    
		// decide template used
		$template = 'take_survey';
		
		if(isset($content['survey_page']['page_orientation']) && $content['survey_page']['page_orientation'] == 'rating')
		{
			$template .= "_rating";
		}
		
		return $this->load->view($template, $content, TRUE);
	}
	
	public function submit_page($survey_code='', $page=0)
	{
		$this->load->helper('file');
		$this->load->helper('cookie');
		
		if(empty($survey_code))
		{
			show_404();
		}
		
		// verify survey code
		$query = $this->db->get_where('survey', array('survey_code' => $survey_code));
		$survey = $query->row_array();
		
		if(empty($survey))
		{
			show_404();
		}
    
    if($survey['correspondence'] >= $survey['limit'])
    {
      redirect('survey/take_survey/' . $survey_code . '?err=reach_limit');
    }
		
		// posted param $_POST
		$input = $this->input->post(NULL, TRUE);
		$post = $input;
		$session_path =  $this->config->item('session_path');
		
		// required param to process
		if(!isset($input['goto']) || (!isset($input['next']) && !isset($input['previous'])))
		{
			redirect('survey/take_survey/' . $survey_code);
		}
		
		// if landing page
		if(empty($page))
		{
			// do checking here
			$query = $this->db->get_where('session', array('session_status' => '0', 'session_code' => $post['user_code']));
			$session_exists = $query->row_array();

      if(!empty($session_exists['survey_id']))
      {
        if($session_exists['survey_id'] != $survey['survey_id'])
        {
          $session_exists = FALSE;
        }
      }
      
			// success verify
			if($session_exists)
			{
				$session_id = $this->session->userdata('session_id');
				
				$cookie = array(
					'name'   => $survey_code,
					'value'  => $session_id,
					'expire' => '21600',
				);
				
				set_cookie($cookie);
				
				$this->db->where('session_code', $post['user_code']);
				$update	= $this->db->update('session', array('session_cookie' => $session_id));
				
				$response = array();
				$response['user_code'] = $post['user_code'];
				write_file($session_path . "/" . $session_id, json_encode($response));
			}
			else
			{
				redirect('survey/take_survey/' . $survey_code . "?err=verify");
			}
		}
		else
		{
			$filter = array('next', 'previous', 'goto');
			
			foreach($post as $key => $val)
			{
				if(in_array($key, $filter))
				{
					unset($post[$key]);
				}
			}

			$session_id = get_cookie($survey_code);
		}
    
    // if next is finish and go to next
    if($input['next'] == 'finish')
		{
      if(is_numeric(strrpos($input['goto'], '>')))
      {
        $input['goto'] = 'Finish';
      }
		}
		
		$finish_btn = array('Kirim' , 'Finish');
		
    
    //////////////////////////////////////////////
    //                                          //
    // FUNCTION TO REDIRECT TO NEXT PAGE        //
    //                                          //
    //////////////////////////////////////////////
    
		// goto next
		if(is_numeric(strrpos($input['goto'], '>')))
		{
			// if page is question page
			if(is_numeric($page) && $page > 0)
			{	
				$cached_response = array();
				if(file_exists($session_path . "/" . $session_id))
				{
					$cached_response = json_decode(read_file($session_path . "/" . $session_id), TRUE);	
				}
				$response = array_merge((array) $cached_response, (array) $post);
        
				// get all question on the page
				$this->db->select('question_id, question_required, question_goto, question_validation, question_name');
				$this->db->from('question');
				$this->db->join('survey_page', 'survey_page.page_id = question.page_id');
				$this->db->where(array('question.survey_id' => $survey['survey_id'], 'page_num' => $page));
				$this->db->order_by('question_id', 'ASC');
				$questions_query = $this->db->get();
				$questions = $questions_query->result_array();
				
        $error_msg = array();
				// go through checking all question
				foreach($questions as $idx => $question)
				{
          $_user_response = $response["question_" . $question['question_id']];
          if(is_string($_user_response))
          {
            $_user_response = trim($_user_response);
          }
          
					// if parameter skip exists then skip this question
					if(isset($response["skip"]))
          {
            foreach($response["skip"] as $question_id => $questions_skipped)
            {
              if(in_array($question['question_id'], $questions_skipped))
              {
                continue 2;
              }
            }
          }
          
					// if goto condition exists then add skip parameter
					if(!empty($question['question_goto']))
					{
						$goto = json_decode($question['question_goto'], TRUE);
						$add_new = FALSE;
            
						
            // if posted response is an array (checkboxes may have multiple value)
						if(is_array($post["question_" . $question['question_id']]))
						{
							$choices = array();
							foreach($post["question_" . $question['question_id']] as $choice)
							{
								if(array_key_exists($choice, $goto))
								{
									$add_new = TRUE;
									$choices[] = $choice;
								}
								
							}
							
							if($add_new)
							{
								foreach($choices as $choice)
								{
                  $response['skip'][$question['question_id']] = $goto[$choice]['skip'];
								}
							}
						}
						// if posted response is string (radio button)
						elseif(is_string($post["question_" . $question['question_id']]))
						{
              $choice = $post["question_" . $question['question_id']];
							if(array_key_exists($choice, $goto))
							{
								$add_new = TRUE;
							}
							if($add_new)
							{
								$response['skip'][$question['question_id']] = $goto[$choice]['skip'];
							}
						}
            
						// if no addition
						if(!$add_new && !empty($response['skip'][$question['question_id']]))
						{
              unset($response['skip'][$question['question_id']]);
						}
					} // end if goto condition exists then add skip parameter
					
					// if question required then start checking posted response
					if($question['question_required'] == 1)
					{
						// if posted response empty
						if(!isset($post["question_" . $question['question_id']]) || empty($_user_response))
						{
							$this->t['err'] = TRUE;
							echo $this->render_survey_page($survey_code, $page);
							exit;
						}
					}
          
          if(isset($question['question_validation']) && !empty($question['question_validation']))
          {
            $rules = json_decode($question['question_validation'], TRUE);
            foreach($rules as $rule)
            {
              if($rule == "valid_email")
              {
                if(!valid_email($_user_response))
                {
                  $error_msg[] = $question['question_name'] . ' dimasukkan tidak sah!';
                }
              }
              elseif($rule == "numeric")
              {
                if(!is_numeric($_user_response))
                {
                  $error_msg[] = $question['question_name'] . ' hanya menerima angka (0-9)';
                }
              }
              elseif(is_numeric(strrpos($rule, "min_length")))
              {
                list($text, $threshold) = explode(":", $rule);
                if(strlen($_user_response) < $threshold)
                {
                  $error_msg[] = 'Minimum panjang ' . $question['question_name'] . ' adalah ' . $threshold;
                }
              }
              elseif(is_numeric(strrpos($rule, "max_length")))
              {
                list($text, $threshold) = explode(":", $rule);
                if(strlen($_user_response) > $threshold)
                {
                  $error_msg[] = 'Maksimum panjang ' . $question['question_name'] . ' adalah ' . $threshold;
                }
              }
            } // go through every rule
            
          } // end if validation not empty
          
				} // end foreach question 
        
        if(!empty($error_msg))
        {
          $this->t['error_msg'] = $error_msg;
          echo $this->render_survey_page($survey_code, $page);
          exit;
        }
        
				write_file($session_path . "/" . $session_id, json_encode($response));
			}
			
			redirect('survey/take_survey/' . $survey_code . "/" . $input['next']  . '?prev=' . $page);
		}
    
    //////////////////////////////////////////////
    //                                          //
    // FUNCTION TO REDIRECT TO PREVIOUS PAGE    //
    //                                          //
    //////////////////////////////////////////////
		elseif(is_numeric(strrpos($input['goto'], '<'))) 
		{
			if(is_numeric($page) && $page > 0)
			{	
				if(file_exists($session_path . "/" . $session_id))
				{
					$response = array_merge((array)$post, (array) json_decode(read_file($session_path . "/" . $session_id), TRUE));
				}
				write_file($session_path . "/" . $session_id, json_encode($response));
			}
			redirect('survey/take_survey/' . $survey_code . "/" . $input['previous'] . '?next=' . $page);
		}
    
    //////////////////////////////////////////////
    //                                          //
    // FUNCTION TO REDIRECT TO FINISH PAGE      //
    //                                          //
    //////////////////////////////////////////////
		elseif(in_array($input['goto'], $finish_btn))
		{
			$session_id = get_cookie($survey_code);
			$response_file = $session_path . "/" . $session_id;
			if(empty($session_id) || !file_exists($response_file))
			{
				redirect('survey/take_survey/' . $survey_code . "?err=session");
			}
			
      $cached_response = array();
      if(file_exists($response_file))
      {
        $cached_response = json_decode(read_file($response_file), TRUE);	
      }
      $response = array_merge((array) $cached_response, (array) $post);
      
			// get all question of survey
			$this->db->select('question_id, question_required, question_goto, question_validation, question_name, page_num');
			$this->db->from('question');
			$this->db->join('survey_page', 'survey_page.page_id = question.page_id');
			$this->db->where('question.survey_id', $survey['survey_id']);
			$this->db->order_by('page_num', 'ASC');
			$questions_query = $this->db->get();
			$questions = $questions_query->result_array();
			
			// go through checking all question
			foreach($questions as $idx => $question)
			{
        $_user_response = $response["question_" . $question['question_id']];
        if(is_string($_user_response))
        {
          $_user_response = trim($_user_response);
        }
        
        // if parameter skip exists then skip this question
				if(isset($response["skip"]))
				{
          foreach($response["skip"] as $question_id => $questions_skipped)
          {
            if(in_array($question['question_id'], $questions_skipped))
            {
              continue 2;
            }
          }
        }
				
        // if goto condition exists then add skip parameter
        if(!empty($question['question_goto']))
        {
          $goto = json_decode($question['question_goto'], TRUE);
          $add_new = FALSE;
          
          // if posted response is an array (checkboxes may have multiple value)
          if(is_array($response["question_" . $question['question_id']]))
          {
            $choices = array();
            foreach($response["question_" . $question['question_id']] as $choice)
            {
              if(array_key_exists($choice, $goto))
              {
                $add_new = TRUE;
                $choices[] = $choice;
              }
              
            }
            
            if($add_new)
            {
              foreach($choices as $choice)
              {
								$response['skip'][$question['question_id']] = $goto[$choice]['skip'];
              }
            }
          }
          // if posted response is string (radio button)
          elseif(is_string($response["question_" . $question['question_id']]))
          {
            $choice = $response["question_" . $question['question_id']];
            if(array_key_exists($choice, $goto))
            {
              $add_new = TRUE;
            }
            if($add_new)
            {
              $response['skip'][$question['question_id']] = $goto[$choice]['skip'];
            }
          }
          
          // if no addition then delete 
          if(!$add_new && !empty($response['skip'][$question['question_id']]))
          {
            unset($response['skip'][$question['question_id']]);
          }
        } // end if goto condition exists then add skip parameter
        
				// if question required then start checking posted response
				if($question['question_required'] == 1)
				{
					// if posted response empty
					if(!isset($_user_response) || empty($_user_response))
					{
						redirect('survey/take_survey/' . $survey_code . '/' . $question['page_num'] . "?err");
					}
				}
        
        if(isset($question['question_validation']) && !empty($question['question_validation']))
        {
          $rules = json_decode($question['question_validation'], TRUE);
          foreach($rules as $rule)
          {
            if($rule == "valid_email")
            {
              if(!valid_email($_user_response))
              {
                $error_msg[] = $question['question_name'] . ' dimasukkan tidak sah!';
              }
            }
            elseif($rule == "numeric")
            {
              if(!is_numeric($_user_response))
              {
                $error_msg[] = $question['question_name'] . ' hanya menerima angka (0-9)';
              }
            }
            elseif(is_numeric(strrpos($rule, "min_length")))
            {
              list($text, $threshold) = explode(":", $rule);
              if(strlen($_user_response) < $threshold)
              {
                $error_msg[] = 'Minimum panjang ' . $question['question_name'] . ' adalah ' . $threshold;
              }
            }
            elseif(is_numeric(strrpos($rule, "max_length")))
            {
              list($text, $threshold) = explode(":", $rule);
              if(strlen($_user_response) > $threshold)
              {
                $error_msg[] = 'Maksimum panjang ' . $question['question_name'] . ' adalah ' . $threshold;
              }
            }
          } // go through every rule
          
        } // end if validation not empty
        
      } // end foreach question 
      
      if(!empty($error_msg))
      {
        $this->t['error_msg'] = $error_msg;
        echo $this->render_survey_page($survey_code, $page);
        exit;
      }
      
			$user_code = $response['user_code'];
      
      if(empty($user_code))
      {
        redirect('survey/take_survey/' . $survey_code . "?err=no_user");
      }
			
			// generate voucher code here
      
      $idx = 0;
      $response_add = FALSE;
      $session_update = FALSE;
      $is_session_avail = FALSE;
      while(!$is_session_avail)
      {
        $characters = str_split('ABCDEFGHIJKLMNOPQRSTUVXWYZ1234567890');
        $voucher_code = $survey['prefix_voucher'];
        $rand_index = array_rand($characters, 6);
        foreach($rand_index as $idx)
        {
          $voucher_code .= $characters[$idx];
        }
        
        $this->db->select('session_id');
        $this->db->from('session');
        $this->db->where('voucher_code', $voucher_code);
        $this->db->where('session_status', 1); 
        $session_query = $this->db->get();
        $session = $session_query->row_array();
        
        if(empty($session))
        {
          $is_session_avail = TRUE;
          break;
        }
        $idx++;
      }
      
			if($is_session_avail)
      {
        // check response is exists
        $this->db->select('response_id');
        $this->db->from('survey_response');
        $this->db->where('user_code', $user_code);
        $response_query = $this->db->get();
        $response_exists = $response_query->row_array();
        unset($response['user_code']);
        
        // if empty then insert
        if(empty($response_exists))
        {
          $user_response = array(
            'user_code' => $user_code,
            'survey_id' => $survey['survey_id'],
            'finish_time' => date('Y-m-d H:i:s'),
            'user_response' => json_encode($response),
          );
          
          $response_add = $this->db->insert('survey_response', $user_response);
        }
        // if empty then update
        else
        {
          $user_response = array(
            'survey_id' => $survey['survey_id'],
            'finish_time' => date('Y-m-d H:i:s'),
            'user_response' => json_encode($response),
          );
          $this->db->where('user_code', $user_code);
          $response_add	= $this->db->update('survey_response', $user_response);
        }
        
        // update the session
        $this->db->where('session_code', $user_code);
        $session_update	= $this->db->update('session', array('session_status' => '1', 'voucher_code' => $voucher_code, 'survey_id' => $survey['survey_id']));
        
        $this->db->where('survey_id', $survey['survey_id']);
        $this->db->set('correspondence', '(correspondence + 1)', FALSE);
        $survey_update	= $this->db->update('survey');
      }
			
			if($response_add && $session_update && $survey_update)
			{
        // delete cookies
        delete_cookie($survey_code);
        
        redirect('survey/take_survey/' . $survey_code . "/" . $input['next']  . '?voucher_code=' . $voucher_code);
			}
			else
      {
				echo "Error: inserting DB";exit;
      }
		}
		
		
	}
}

/* End of file survey.php */
/* Location: ./application/controllers/survey.php */
