<?php

/**
 * OuzelGuides - Ouzel Outfitters Guide Portal
 *
 * @author Will Sharp
 */

/**
 * Class for sending emails.
 */

class SparkPostApi
{
    public $spark;
    public $options;
    
    protected $auth;
    protected $http_adapter;

    public function __construct($marketing_email = true)
    {
        $this->auth = config('services.sparkpost.secret');
        $this->http_adapter = new Guzzle6HttpAdapter(new Client());
        $this->spark = new SparkPost($this->http_adapter, $this->auth);
        
        $this->setDefaultOptions($marketing_email);
    }
    
    protected function setDefaultOptions($marketing_email)
    {
        // Set default options
        if($marketing_email) {
            $this->options = [
                'trackOpens'    => true,
                'trackClicks'   => true,
                'inlineCss'     => true
            ];
        }
        else {
            // Transactional
            $this->options = [
                'from'          => config('mail.from.name') . '<' . config('mail.from.address') . '>',
                'trackOpens'    => true,
                'trackClicks'   => false,
                'inlineCss'     => true,
                'transactional' => true
            ];
        }
        
        return;
    }
    
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);
    }
    
    public function sendEmail()
    {
        try {
            $results = $this->spark->transmission->send($this->options);
        } catch (\Exception $e) {
            Log::error('SparkPost API: ' . $exception->getMessage());
        }
        
        return $results;
    }
}
?>