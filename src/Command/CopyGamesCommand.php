<?php

namespace App\Command;

use App\Entity\Game;
use App\Entity\Season;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CopyGamesCommand extends Command
{
    protected static $defaultName        = 'app:copy-games';
    protected static $defaultDescription = 'Copy games from old database';

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
        $io->writeln('Searching for games...');

        $conn = $this->entityManager->getConnection();
        $rawQuery = "SELECT * FROM " . $input->getArgument('tableName');
        $stmt = $conn->prepare($rawQuery);
        $stmt->execute();
        $games = $stmt->fetchAll();

        if (!count($games)) {
            $io->success('No games found!');

            return Command::SUCCESS;
        }

        $io->newLine();
        $io->writeln('Games found: ' . count($games));
        $io->newLine();

        $progressBar = new ProgressBar($output, count($games));
        $progressBar->start();

        foreach ($games as $game) {
            $new = new Game();
            $new->setId($game['ID'] + $input->getArgument('idOffset'))
                ->setCreatedAt(new \DateTime($game['dAddM']))
                ->setUpdatedAt(new \DateTime($game['dAddR']))
                ->setCreatedBy($this->entityManager->getRepository(User::class)->find($game['uAddM']))
                ->setUpdatedBy($this->entityManager->getRepository(User::class)->find($game['uAddR']))
                ->setHome($this->entityManager->getRepository(Team::class)->find($game['home']))
                ->setGuest($this->entityManager->getRepository(Team::class)->find($game['guest']))
                ->setSeason($this->entityManager->getRepository(Season::class)->findOneBy(['year' => $game['season']]))
                ->setRoundNr($game['kol'])
                ->setIsCounted($game['counted'])
                ->setIsAwarded($game['bonus'])
                ->setDate(new \DateTime($game['data']))
                ->setGoalsHome($game['goalsHome'])
                ->setGoalsGuest($game['goalsGuest'])
                ->setDescription($game['uwagi']);

            $this->entityManager->persist($new);

            $progressBar->advance();
        }

        $this->entityManager->flush();

        $progressBar->finish();

        $io->newLine(2);
        $io->success('All games have been successfully imported');

        return Command::SUCCESS;
    }
}
