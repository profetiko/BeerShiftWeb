<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Api extends REST_Controller {

    function user_get() {
        if (!$this->get('username')) {
            $this->response(NULL, 400);
        }
		$username = strtolower($this->get('username'));
		
        $user = $this->usermodel->getUser($username);

        if ($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(NULL, 404);
        }
    }

    function user_post() {
        if (!$this->post('username')) {
            $this->response(NULL, 400);
        }
        if (!$this->post('password')) {
            $this->response(NULL, 400);
        }

		$username = strtolower($this->post('username'));
        $result = $this->usermodel->createUser($username, $this->post('password'));

        if ($result === FALSE) {
            $this->response(array('status' => 'failed'));
        } else {
            $this->response(array('status' => 'success'));
        }
    }
    
    function userbeers_get() {
        if (!$this->get('username')) {
            $this->response(NULL, 400);
        }
		$username = strtolower($this->get('username'));
        $user = $this->usermodel->getUserBeers($username);

        if ($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(NULL, 404);
        }
    }
    
    function firehose_get() {
        
        $beers = $this->usermodel->getFirehoseBeers();

        if ($beers) {
            $this->response($beers, 200); // 200 being the HTTP response code
        } else {
            $this->response(NULL, 404);
        }
    }
    
    function beers_get() {
        if (!$this->get('name')) {
            $this->response(NULL, 400);
        }
        
        $this->load->library('curl');
        $this->curl->create($this->config->item('pintlab_url')."?key=".$this->config->item('pintlab_key')."&q=".$this->get('name')."&type=beer&withBreweries=Y");
        
        $beers = json_decode($this->curl->execute());
        if (isset($beers->status) && $beers->status == 'success') {
           $this->response($beers, 200); // 200 being the HTTP response code
        } else {
            $this->response(NULL, 404);
        }
    }
    
    function beers_post() {
        if (!$this->post('username')) {
            $this->response(NULL, 400);
        }
        if (!$this->post('beerName')) {
            $this->response(NULL, 400);
        }
        if (!$this->post('when')) {
            $this->response(NULL, 400);
        }

		$username = strtolower($this->post('username'));
        $result = $this->usermodel->drinkBeer($username, $this->post('beerName'), $this->post('when'));

        if ($result === FALSE) {
            $this->response(array('status' => 'failed'));
        } else {
            $this->response(array('status' => 'success'));
        }
    }

}

?>
