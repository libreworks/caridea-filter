<?hh // decl

namespace Caridea\Filter;

class Casts
{
    public static function toBoolean(): (function(mixed): bool)
    {
    }

    public static function toInteger(): (function(mixed): int)
    {
    }

    public static function toFloat(): (function(mixed): float)
    {
    }

    public static function toArray(): (function(mixed): array)
    {
    }

    public static function toDefault<T>(mixed $default = null): (function(mixed): mixed)
    {
    }
}
