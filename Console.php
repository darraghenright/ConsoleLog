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
     * @staticvar string $file The file name and line number of the log call
     */
    private static $traceData;

    /**
     * Format and dump output string to console
     *
     * @param mixed       $data    The value to dump to the console
     * @param string|null $message An optional message to dump alongside the value
     */
    public static function echoConsole($data, $message = null, $type = 'log')
    {
        self::setTraceData(debug_backtrace());

        if (self::$isOn) {
            self::addMessage($message, $type);
            self::addData($data, $type);
            self::output();
        }
    }

    /**
     * Format and dump output string to console.log
     *
     * @param mixed       $data    The value to dump to the console
     * @param string|null $message An optional message to dump alongside the value
     */
    public static function log($data, $message = null)
    {
        self::echoConsole($data, $message, 'log');
    }

    /**
     * Format and dump output string to console.info
     *
     * @param mixed       $data    The value to dump to the console
     * @param string|null $message An optional message to dump alongside the value
     */
    public static function info($data, $message = null)
    {
        self::echoConsole($data, $message, 'info');
    }

    /**
     * Format and dump output string to console.debug
     *
     * @param mixed       $data    The value to dump to the console
     * @param string|null $message An optional message to dump alongside the value
     */
    public static function debug($data, $message = null)
    {
        self::echoConsole($data, $message, 'debug');
    }

    /**
     * Format and dump output string to console.warn
     *
     * @param mixed       $data    The value to dump to the console
     * @param string|null $message An optional message to dump alongside the value
     */
    public static function warn($data, $message = null)
    {
        self::echoConsole($data, $message, 'warn');
    }

    /**
     * Format and dump output string to console.error
     *
     * @param mixed       $data    The value to dump to the console
     * @param string|null $message An optional message to dump alongside the value
     */
    public static function error($data, $message = null)
    {
        self::echoConsole($data, $message, 'error');
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
        $file = $trace[0]['file'];
        $line = $trace[0]['line'];

        self::$traceData = sprintf('[%s:~%d]', basename($file), $line);
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
     * @param string $type
     * @param string $message
     */
    protected static function addMessage($message, $type)
    {
        self::$output = $message ? sprintf('console.%s("%s %s:");', $type, self::$traceData, $message) : null;
    }

    /**
     * Add data to output string
     *
     * @param string $message
     */
    protected static function addData($data, $type)
    {
        self::$output .= sprintf('console.%s(%s);', $type, json_encode(self::formatData($data), JSON_NUMERIC_CHECK));
    }

    /**
     * Print output as javascript
     */
    protected static function output()
    {
        printf('<script type="text/javascript">%s</script>', self::$output);
    }
}
