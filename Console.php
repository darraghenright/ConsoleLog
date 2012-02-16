<?php

/** 
 * Console logger
 *
 * Wrap a PHP value in <script> elements and 
 * output to WebKit's console with console.log()
 * 
 * Usage:
 *
 * Dump any type:
 * <code>
 *   $int = 42;
 *   Console::log($int);
 * </code>
 *
 * Optionally include a message:
 * <code>
 *   $str = 'foo';
 *   Console::log($str, 'dumping $str on line ' . __LINE__);
 * </code>
 * 
 * Toggle output anywhere in the script:
 * <code>
 *   Console::off(); // suppress output
 *   Console::on();
 * </code>
 *
 * @author Darragh Enright <darraghenright@gmail.com>
 */
class Console
{
    /** 
     * @staticvar string $output The output string to dump to the console
     */ 
    private static $output = null;
    
    /** 
     * @staticvar string $output Toggle value for log output
     */ 
    private static $isOn = true;

    /**
     * @staticvar string $file The log filename
     */
    private static $file;

    /**
     * @staticvar integer $traceData The log line number
     */
    private static $line;

    /**
     * Format and dump output string to console
     *  
     * @param mixed       $data    The value to dump to the console
     * @param string|null $message An optional message to dump alongside the value
     */
    public static function log($data, $message = null)
    {   
        if (null !== $message) {
            self::setTraceData(debug_backtrace());
        }

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
     * Set trace data (file and line number)
     *
     * @param array $trace
     */   
    public static function setTraceData(array $trace)
    {
        self::$file = basename($trace[0]['file']);
        self::$line = $trace[0]['line'];
    }

    /** 
     * Format data to appropriate string format
     *
     * @param  mixed $data
     * @return string
     */ 
    protected static function formatData($data)
    {
        return is_scalar($data) || is_null($data) ? var_export($data, true) : self::formatComposite($data);
    }

    /** 
     * Format composite data; i.e: objects, arrays and resources
     *
     * @param  mixed $data
     * @return string
     */
    protected static function formatComposite($data)
    {
        return !is_resource($data) ? print_r($data, true) : self::formatResource($data);
    }
    
    /** 
     * Format a resource type
     *
     * @param  mixed $data
     * @return string
     */    
    protected static function formatResource($data)
    {
        return sprintf('%s: %s', print_r($data, true), get_resource_type($data));
    }
    
    /**
     * Add message to output string
     *
     * @param string $message
     */     
    protected static function addMessage($message)
    {
        self::$output = ($message) ? sprintf('console.log("[%s:~%d] %s:");', self::$file, self::$line, $message) : null;
    }

    /** 
     * Add data to output string
     *
     * @param string $message
     */     
    protected static function addData($data)
    {
        self::$output .= sprintf('console.log(%s);', json_encode(self::formatData($data), JSON_NUMERIC_CHECK));
    }

    /** 
     * Print output as javascript
     */
    protected static function output()
    {
        printf('<script type="text/javascript">%s</script>', self::$output);
    }
}
