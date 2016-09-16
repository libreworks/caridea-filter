<?hh // decl

namespace Caridea\Filter;

class Registry
{
    protected array<string,mixed> $definitions;

    public function __construct()
    {
    }

    public function register(array $definitions): this
    {
    }

    public function factory(string $name, array $args): (function(mixed): mixed)
    {
    }

    public function builder(): Builder
    {
    }
}
