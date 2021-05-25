<?php

namespace App\Command;

use App\Entity\Game;
use App\Entity\Round;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AssignRoundEntityToGameCommand extends Command
{
    private $entityManager;

    protected static $defaultName        = 'app:assign-round-entity-to-game';
    protected static $defaultDescription = 'Assign missing round entities to games';

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
        $io->writeln('Searching for games...');

        $games = $this->entityManager->getRepository(Game::class)->findBy(['round' => null]);

        if (!count($games)) {
            $io->success('No games found!');

            return Command::SUCCESS;
        }

        $io->newLine();
        $io->writeln('Games found: ' . count($games));
        $io->newLine();

        $progressBar = new ProgressBar($output, count($games));
        $progressBar->start();

        /** @var Game $game */
        foreach ($games as $game) {
            $round = $this->entityManager->getRepository(Round::class)
                                         ->findOneBy([
                                                         'season'  => $game->getSeason(),
                                                         'ordinal' => $game->getRoundNr(),
                                                     ]);

            $game->setRound($round);

            $progressBar->advance();
        }

        $this->entityManager->flush();

        $progressBar->finish();

        $io->newLine(2);
        $io->success('All games have been successfully updated');

        return Command::SUCCESS;
    }
}
