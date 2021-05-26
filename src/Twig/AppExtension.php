<?php

namespace App\Twig;

use App\Entity\Round;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            // new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('round_name', [$this, 'getRoundName']),
        ];
    }

    public function getRoundName(Round $round)
    {
        if ($round->getName()) {
            return $round->getName();
        } else {
            return '#' . $round->getOrdinal();
        }
    }
}
