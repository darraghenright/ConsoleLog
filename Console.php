<?php

/** 
 * Console logger
 *
 * @author Darragh Enright <darraghenright@gmail.com>
 */
class Console
{
    /** 
     * @static string $output
     */ 
    private static $output = null;
    
    /** 
     * @static string $output
     */ 
    public static $isOn = true;

    /**
     * Format and dump output string to console
     *
     * @param mixed       $data
     * @param string|null $message
     */     
    public static function log($data, $message = null)
    {    
        if (self::$isOn) {
            self::addMessage($message);
            self::addData($data);
            self::output();
        }
    }

    /**
     * Turn console output on
     * 
     * @return boolean
     */    
    public static function on()
    {
        self::$isOn = true;
    }

    /**
     * Turn console output off
     *
     * @return boolean
     */
    public static function off()
    {
        self::$isOn = false;
    }
    
    /** 
     * Format data to appropriate string format
     *
     * @param  mixed $data
     * @return string
     */ 
    private static function formatData($data)
    {
        return is_scalar($data) || is_null($data) ? var_export($data, true) : self::formatComposite($data);
    }

    /** 
     * Format composite data; i.e: objects, arrays and resources
     *
     * @param  mixed $data
     * @return string
     */
    private static function formatComposite($data)
    {
        return !is_resource($data) ? print_r($data, true) : sprintf('%s: %s', print_r($data, true), get_resource_type($data));
    }
    
    /**
     * Add message to output string
     *
     * @param string $message
     */     
    private static function addMessage($message)
    {
        self::$output = ($message) ? sprintf('console.log(":: %s ::");', $message) : null;
    }

    /** 
     * Add data to output string
     *
     * @param string $message
     */     
    private static function addData($data)
    {
        self::$output .= sprintf('console.log(%s);', json_encode(self::formatData($data), JSON_NUMERIC_CHECK));
    }

    /** 
     * Print javascript output 
     */
    private static function output()
    {
        printf('<script type="text/javascript">%s</script>', self::$output);
    }
}
