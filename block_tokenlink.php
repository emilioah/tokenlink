<?php
/**
 * This block creates and sends the user token along with email and username to an external URL.
 * The data is sent encrypted. The target script must use a shared key to unencrpt the data.
 *
 * @package    block_tokenlink
 * @copyright  2015 onwards. Centro de Enseñanzas Virtuales de la UGR (http://cevug.ugr.es)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Antonio Mingorance <amingo@ugr.es>, Jose López <josef267@gmail.com>, Emilio Arjona <emilio.ah@gmail.com>
 */

class block_tokenlink extends block_base {
    public function init() {
        $this->title = get_string('tokenlink', 'block_tokenlink');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.    
	
	public function specialization() {
	    
	    if (isset($this->config)) {
	        if (empty($this->config->title)) {
	            $this->title = get_string('blocktitle', 'block_tokenlink');            
	        } else {
	            $this->title = $this->config->title;
	        }
	    }
	}

	public function get_content() {
		global $USER, $CFG;

		if ($this->content !== null) {
			return $this->content;
		}
		#https://tracker.moodle.org/browse/MDL-45664
		#In main page (user not login) if not check the $user context_user::instance($USER->id) return ERROR
		if(empty($USER->id))return;

		$usercontext = context_user::instance($USER->id);
		$token_string = '';
		if ( !is_siteadmin($USER->id) && !empty($CFG->enablewebservices) && has_capability('moodle/webservice:createtoken', $usercontext ) && $this->config->tokencheck) {
			require($CFG->dirroot.'/webservice/lib.php');		
			// Create tokens
			$webservice = new webservice(); //load the webservice library
			$tokens = $webservice->get_user_ws_tokens($USER->id);	

			foreach ($tokens as $token){
				if ($token->name == $this->config->webservicename){
					$token_string = $token->token;
				}
			}

			if ($token_string == ''){
				$webservice->generate_user_ws_tokens($USER->id);
				foreach ($tokens as $token){
					if ($token->name = $this->config->webservicename){
						$token_string = $token->token;
					}
				}
			}
		}		
		$this->content         =  new stdClass;

		#sanity: show message if no found URL
		if(empty($this->config->targeturl)){
			$this->content->text = "URL no found";
			return $this->content->text;
		}

		#sanity: show message if image is empty
		if(empty($this->config->urlimage)){
			$this->content->text = "IMAGE no found";
			return $this->content->text;
		}

		#sanity: if GET param is empty use "data"  
		if(empty($this->config->nameparamget))
			$this->config->nameparamget='data';

		#parameters that we need to send
		$encrypt_params='';
		if($this->config->usernamecheck) $encrypt_params .= '&username='.$USER->username;
		if($this->config->emailcheck) $encrypt_params .= '&email='.$USER->email;
		if($this->config->tokencheck && $token_string!='') $encrypt_params .= '&token='.$token_string;
		if($encrypt_params!=''){
			#delete first &
			$encrypt_params=substr($encrypt_params,1);
			#is cipherkey is not empty
			if($this->config->cipherkey!=''){
				#encrypt 
				$encrypt_params = $this->m_encrypt($encrypt_params, $this->config->cipherkey);
				$encrypt_params = urlencode($encrypt_params);
				$url_link = $this->config->targeturl."?".$this->config->nameparamget."=".$encrypt_params;
			}else{
				#send witout ?data= and params visibles
				$url_link = $this->config->targeturl."?".$encrypt_params;
			}
		}else{
			#dont have params to send, only send targeturl
			$url_link = $this->config->targeturl;
		}

		#bulid imag and a href
		$image_style = " style = 'width:".$this->config->imagewidth."; height:".$this->config->imageheight.";' ";
		$url_link = "<a href='".$url_link."' target='_blank'>"."<img src='".$this->config->urlimage."'".$image_style."></a>";

		$this->content->text   = $url_link;

		#$this->content->footer = 'Pie'; 
		return $this->content;
  	}

  	/**
  	*Encryp a string "data" with MCRYPT_RIJNDAEL_128
  	*Key: 16,24 or 32 character
  	*http://php.net/manual/es/function.mcrypt-encrypt.php
  	*/
  	public function m_encrypt($data, $key){
 		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}

	/*
	*FUNCTION for testing
	*
	*
  	public function m_decrypt($data, $key){
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($data), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND)));
  	}
  	*/

}
