<?hh // decl

namespace Caridea\Filter;

class Builder
{
    protected Registry $registry;
    protected array<string,Chain> $chains = [];
    protected array<Reducer> $reducers = [];
    protected ?Chain $otherwiseChain;

    public function __construct(Registry $registry)
    {
    }

    public function field(string $name): Chain
    {
    }

    public function always(string $name): Chain
    {
    }

    public function reducer(Reducer $multi): this
    {
    }

    public function otherwise(string $name, mixed ...$args): Chain
    {
    }

    public function build(): Filter
    {
    }
}
