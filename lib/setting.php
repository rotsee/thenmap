<?php
/* Initiate like this */
/* $mySetting = new Setting( array( "available"    => array ('sv', 'en'),      */
/*									"fallback"     => 'en',                    */
/*									"type"         => Setting::LANGUAGE, ) );  */
class Setting {

	const LANGUAGE = 1;
	const YEAR     = 2;
	const STRING   = 3;
	
	private $_value;

	private $available;
	private $aliases;
	private $fallback;
	private $type;
	
    function  __construct( $params ) {

    	if ( isset( $params["available"] ) ) {
    		if( is_array( $params["available"] ) ){
	    		$this->available = $params["available"];
	    	} else {
	    		$this->available = array( $params["available"] );
	    	}
    	}
   
		if ( isset( $params["aliases"] ) ) {
			if( is_array( $params["aliases"] ) ){
				$this->aliases = $params["aliases"];
			}
		}

    	if ( isset( $params["fallback"] ) ) {
    		$this->fallback = $params["fallback"];
    		$this->set($params["fallback"]);
    	} else {
    		$this->fallback = null;
    	}

    	if ( isset( $params["type"] ) ) {
    		if( in_array(	$params["type"], 
    						array ( self::LANGUAGE, self::STRING, ) 
    					) ) {
	    		$this->type = $params["type"];
	    	}
    	}
    	
    	if ( null === $this->type ) {
    		$this->type = self::STRING;
    	}
    }
    
	public function set( $val ) {
	
		if (isset( $this->aliases) ) {
			if ( array_key_exists( $val, $this->aliases ) ) {
				$val = $this->aliases[$val];
			}
		}

		if ( $this->validate( $val ) ) {
			$this->_value = $val;
		} else {
			$this->_value = $this->fallback;
		}

	}
    
	public function get() {

		return $this->_value;
	}
    
    public function validate ( $val ) {
    
	 	if ( isset( $this->available ) ) {
	 		if (!in_array( $val, $this->available ) ){
	 			return false;
	 		}
	 	}

    	switch ( $this->type ) {
    		case self::LANGUAGE:
    							if (    is_string( $val )
    								 && preg_match('/[a-z]{2,3}/', $val) ) {
    								 	return true;
    								} else {
    									return false;
    								}
    							break;
    		case self::YEAR:
    							if (   is_int( $val )
    								&& ( 0 < $val )
    								&& ( $val < 7999 ) ) {
    								 	return true;
    								} else {
    									return false;
    								}
    							break;
    		case self::STRING:
					    		if ( is_string( $val ) ) {
    								 	return true;
    								} else {
    									return false;
    								}
    							break;
    	}

    }
}
