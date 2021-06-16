<?php

namespace App\Command;

use App\Entity\Bet;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CopyBetsCommand extends Command
{
    protected static $defaultName        = 'app:copy-bets';
    protected static $defaultDescription = 'Copy bets from old database';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('tableName', InputArgument::REQUIRED, 'dbName with data')
            ->addArgument('idOffset', InputArgument::REQUIRED, 'value add to id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Searching for bets...');

        $conn = $this->entityManager->getConnection();
        $rawQuery = "SELECT * FROM " . $input->getArgument('tableName');
        $stmt = $conn->prepare($rawQuery);
        $stmt->execute();
        $bets = $stmt->fetchAll();

        if (!count($bets)) {
            $io->success('No games found!');

            return Command::SUCCESS;
        }

        $io->newLine();
        $io->writeln('Bets found: ' . count($bets));
        $io->newLine();

        $progressBar = new ProgressBar($output, count($bets));
        $progressBar->start();

        foreach ($bets as $bet) {
            /** @var Game $game */
            $game = $this->entityManager->getRepository(Game::class)->find($bet['matchID'] + $input->getArgument('idOffset'));
            $hit = $game->getGoalsHome() == $bet['gHome'] && $game->getGoalsGuest() == $bet['gGuest'];

            $new = new Bet();
            $new->setCreatedAt(new \DateTime($bet['modify']))
                ->setUser($this->entityManager->getRepository(User::class)->find($bet['typer']))
                ->setGame($game)
                ->setGoalsHome($bet['gHome'])
                ->setGoalsGuest($bet['gGuest'])
                ->setHit($hit)
                ->setPts($bet['pkt']);

            $this->entityManager->persist($new);

            $progressBar->advance();
        }

        $this->entityManager->flush();

        $progressBar->finish();

        $io->newLine(2);
        $io->success('All bets have been successfully imported');

        return Command::SUCCESS;
    }
}
