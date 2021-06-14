<?php

namespace App\Twig;

use App\Entity\Game;
use App\Entity\Round;
use App\Entity\Season;
use App\Service\GameService;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $seasonRepo;
    private $gameService;

    public function __construct(EntityManagerInterface $em, GameService $gameService)
    {
        $this->seasonRepo  = $em->getRepository(Season::class);
        $this->gameService = $gameService;
    }

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
            new TwigFunction('get_active_seasons', [$this, 'getActiveSeasons']),
            new TwigFunction('count_missing_bets', [$this, 'countMissingBets']),
            new TwigFunction('get_user_bet', [$this, 'getUserBet']),
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

    public function getActiveSeasons()
    {
        return $this->seasonRepo->findActiveSeasons();
    }

    public function countMissingBets(Season $season)
    {
        return $this->gameService->countUserMissingBets($season);
    }

    public function getUserBet(Game $game)
    {
        return $this->gameService->getUserBet($game);
    }
}
