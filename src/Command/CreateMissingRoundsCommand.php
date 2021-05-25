<?php

namespace App\Command;

use App\Entity\Game;
use App\Entity\Round;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateMissingRoundsCommand extends Command
{
    private $entityManager;

    protected static $defaultName        = 'app:create-missing-rounds';
    protected static $defaultDescription = 'Create missing rounds entities for every season';

    public function __construct(EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Searching for seasons...');

        $seasons = $this->entityManager->getRepository(Season::class)->findAll();

        if (!count($seasons)) {
            $io->success('No seasons found!');

            return Command::SUCCESS;
        }

        $io->newLine();
        $io->writeln('Seasons found: ' . count($seasons));
        $io->newLine();

        $progressBar = new ProgressBar($output, count($seasons));
        $progressBar->start();

        foreach ($seasons as $season) {
            $this->createMissingRound($season);
            $progressBar->advance();
        }

        $progressBar->finish();

        $io->newLine(2);
        $io->success('All missing rounds have been successfully created');

        return Command::SUCCESS;
    }

    private function createMissingRound(Season $season): int
    {
        $games = $this->entityManager->getRepository(Game::class)->findSeasonGames($season);

        /** @var Game $game */
        foreach ($games as $game) {
            $round = $this->entityManager
                ->getRepository(Round::class)
                ->findOneBy([
                                'season'  => $season,
                                'ordinal' => $game->getRoundNr(),
                            ]);

            if (!$round instanceof Round) {
                $round = new Round();
                $round->setSeason($season)
                      ->setOrdinal($game->getRoundNr())
                      ->setGamesCount($season->getRoundGames())
                      ->setCreatedAt($game->getCreatedAt());
                $this->entityManager->persist($round);
                $this->entityManager->flush();
            }
        }

        return true;
    }
}
