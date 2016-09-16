<?hh // decl

namespace Caridea\Filter;

class Otherwise implements Reducer
{
    protected Chain $chain;
    protected array<string,mixed> $ignores;

    public function __construct(Chain $chain, array<string,mixed> $ignores)
    {
    }

    public function __invoke<Tk as arraykey,Tv>(array<Tk,Tv> $input): array<Tk,mixed>
    {
    }
}
