<?hh // decl

namespace Caridea\Filter;

interface Reducer
{
    public function __invoke<Tk as arraykey,Tv>(array<Tk,Tv> $input): array<Tk,mixed>;
}