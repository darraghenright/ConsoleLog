<?php 

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', true);

require_once __DIR__ . '/Console.php';

class Stub
{
    private $id;

    private $value;
    
    public function __construct($value)
    {
        $this->id = time();
        $this->value = $value;
    }
}

$mysql = mysql_connect('localhost', '', '');

if (is_resource($mysql)) {
    echo get_resource_type($mysql);
} else {
    echo 'cannae connect';
}

var_dump(is_scalar(false));

$types = array(
    'null'           => null,
    'bool_true'      => true,
    'bool_false'     => false,
    'int_unsigned'   => 42,
    'int_signed'     => -123,
    'float_unsigned' => 1.0,
    'float_signed'   => -2.9,
    'str_single'     => 'hello',
    'str_double'     => "\thello \n", 
    'array_std'      => array(1, 2, 3),
    'array_assoc'    => array('foo' => 'FOO', 'bar' => 'BAR', 'baz' => 1),
    'array_nested'   => array('foo' => 'FOO', 'two' => 2, 'nested' => range(1,3), 'hi'),
    'object'         => new Stub("Hello, World!\n"),
    'resource'       => $mysql,
);

foreach ($types as $k => $v) {
    Console::log($v, $k);
}
