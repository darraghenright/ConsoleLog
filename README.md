#README

##Description

`Console` is a simple static console logger class for 
dumping PHP variables to the console of your WebKit browser.

You should dump be able to dump any PHP type:

* boolean
* integer
* float
* string
* null
* array
* object
* resource

Depending on the variable type `Console` will choose 
`var_export()` or `print_r()` where appropriate.

##Usage

Import the Console class into your script:

    require_once __DIR__ . '/path/to/Console.php'

Use static method `Console::log()` to dump variable to console. Easy!

Dump an integer:

    $int = 42;
    Console::log($int); 

Output:

    42

Dump an array:

    $threeTimesTable = range(3, 30, 3);
    Console::log($threeTimesTable);

Output:

    Array
    (
        [0] => 3
        [1] => 6
        [2] => 9
        [3] => 12
        [4] => 15
        [5] => 18
        [6] => 21
        [7] => 24
        [8] => 27
        [9] => 30
    )
    
Dump a resource:

    $resource = imagecreatetruecolor(600, 400);
    Console:log($resource);
    
Output:

    Resource id #1: gd

You can optionally add a message; the originating 
file name and line number are included in the output:

    $now = new DateTime();
    Console::log($now, 'Dumping DateTime object $now to console');

Output:

    [file.php:~49] Dumping DateTime object $now to console:
    DateTime Object
    (
        [date] => 2012-02-16 20:55:15
        [timezone_type] => 3
        [timezone] => Europe/Dublin
    )

You can also toggle output at any point in your script. This 
is handy if you need to turn on/off logging while debugging:

    Console::on();
    Console::log('foo'); // outputs: 'foo'

    Console::off();
    Console::log('bar'); // output is suppressed

That's it! :)

##Support and limitations

* Should be fine in Chrome/Safari (webkit)
* Need to test FireBug functionality
* Will not work in IE (IE does not support `console.log()`)
* Double quoted strings with interpolated variables will trigger a warning `$str = "Hello $name";`

##TODOs

* Add tests
* Add option to aggregate log messages while `Console::off()` is set
* Show type 
* More?

