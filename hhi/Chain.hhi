<?hh // decl

namespace Caridea\Filter;

class Chain implements \IteratorAggregate<(function(mixed): mixed)>, \Countable
{
    protected Registry $registry;
    protected array<(function(mixed): mixed)> $filters = [];
    protected bool $required;

    public function __construct(Registry $registry, bool $required = false)
    {
    }

    public function __invoke(mixed $value): mixed
    {
    }

    public function isEmpty(): bool
    {
    }

    public function isRequired(): bool
    {
    }

    public function count(): int
    {
    }

    public function getIterator(): \Iterator<(function(mixed): mixed)>
    {
    }

    public function then(string $name, mixed ...$args): this
    {
    }

    public function each(string $name, mixed ...$args): this
    {
    }
}
