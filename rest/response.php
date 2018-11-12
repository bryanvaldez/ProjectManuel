<?php

class App_Response
{

    public $success;
    public $message;
    public $data;

    public function __construct () {
        $this->success = false;
        $this->message = null;
        $this->data = null;
    }

    public function render () {
        echo json_encode( $this );
    }

}
