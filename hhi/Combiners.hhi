<?hh // decl

namespace Caridea\Filter;

class Combiners
{
    public static function appender(string $destination, string $prefix): Reducer
    {
    }

    public static function prefixed(string $destination, string $prefix): Reducer
    {
    }

    public static function datetime(string $destination, string $date, string $time, ?string $timezone = null): Reducer
    {
    }
}
