<?php

/*

* Yii extension LTI

* This class helps Yii to processing LTI Requests
* It uses LTI_Tool_Provider.php library
* http://projects.oscelot.org/gf/projects/php-basic-lti/
*
* @author KapustinAV
* @version 0.1
*
1. Download and upload files to /protected/extensions/yii-lti/;
2. Upload LTI_Tool_Provider.php from http://projects.oscelot.org/gf/projects/php-basic-lti/
    to /protected/vendors/lti_tool_provider/;
3. Upload your custom LTI Tool Provider class to /protected/components/ (if needed)
4. Include extension in config/main.php

You can setup options:

'lti' => array(
    'class' => 'application.extensions.yii-lti.YiiLTI',
    'options' => array(
        'lti_tool_provider_name' => 'LTI_Tool_Provider' // default. Change this to your custom LTI provider class name.
    )
),

*/

class YiiLTI extends CApplicationComponent
{
	/**
     * Options
     * @var array
     */
    public $options = array();
	
    /**
     * DB Connection
     * @var object
     */
    protected $db;
    
    /**
     * DB connection table prefix
     * @var string
     */
    protected $table_prefix;
    
    /**
     * LTI Tool Provider
     * @var LTI_Tool_Provider
     */
    public $tool;
	
	/**
     * LTI data connector
     * @var LTI_Data_Connector
     */
    private $data_connector;
    
    /**
     * Name of LTI Tool Provider
     * @var LTI_Tool_Provider
     */
    private $lti_tool_provider_name;

    /**
     * Initialize the extension
     */
    public function init()
    {
        Yii::import('application.vendors.*');
        require_once('lti_tool_provider/LTI_Tool_Provider.php');
        
        $this->defaults();
        
        $this->data_connector = LTI_Data_Connector::getDataConnector($this->table_prefix, $this->db);
        $this->tool = new $this->lti_tool_provider_name($this->data_connector);
        
        echo $this->lti_tool_provider_name;
                
        // TODO: create database, if tables not exist...
    }

    /**
     * Defaults for using
     */
    protected function defaults()
    {
        $this->db = Yii::app()->db->getPdoInstance();
        $this->table_prefix = Yii::app()->db->tablePrefix;
        
        $this->lti_tool_provider_name = ! isset($this->options['lti_tool_provider_name']) ? "LTI_Tool_Provider" : $this->options['lti_tool_provider_name'];
    }
    
    /**
	 * Demo function. Creates new LTI Consumer
     * @param string $key
     * @param string $name
     * @param string $secret
     * @param bool $enabled
     */
    public function createConsumer($key = 'jisc.ac.uk', $name = 'ceLTIc', $secret = 'secret', $enabled = TRUE)
    {
        $consumer = new LTI_Tool_Consumer($key, $this->data_connector);
        if (is_null($consumer->created)) {
            $consumer->name = $name;
            $consumer->secret = $secret;
            $consumer->enabled = $enabled;
            return $consumer->save();
        }
        return false;
    }
    
    /**
     * Executes LTI Tool Provider function 
     * 
     * @deprecated Use handle_request instead
     * @see LTI_Tool_Provider::$handle_request
     *
     * @param string $callback function name
     */
    public function execute_tool($callback) {
        return $this->handle_request($callback);
    }
    
    /**
     * Process an incoming request
     * 
     * @param string $callback function name
     * @return mixed Returns TRUE or FALSE, a redirection URL or HTML
     */
    public function handle_request($callback) {
        $tool = new $this->lti_tool_provider_name($callback, $this->data_connector);
        return $tool->handle_request();
    }
}