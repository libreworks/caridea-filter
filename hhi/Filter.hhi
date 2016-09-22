<?hh // decl

namespace Caridea\Filter;

class Filter implements Reducer
{
    protected array<string,Chain> $chains = [];
    protected array<Reducer> $reducers = [];
    protected ?Chain $otherwise;

    public function __construct(array<string,Chain> $chains, array<Reducer> $reducers = [], ?Chain $otherwise = null)
    {
    }

    public function __invoke<Tk as arraykey,Tv>(array<Tk,Tv> $values): array<Tk,mixed>
    {
    }
}
