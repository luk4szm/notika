<?php

namespace App\Command;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CopyTeamsCommand extends Command
{
    protected static $defaultName = 'app:copy-teams';
    protected static $defaultDescription = 'Copy teams from old database';

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
            ->addArgument('isClub', InputArgument::REQUIRED, 'true when team is club')
            ->addArgument('idOffset', InputArgument::OPTIONAL, 'value add to id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Searching for teams...');

        $conn = $this->entityManager->getConnection();
        $rawQuery = "SELECT * FROM " . $input->getArgument('tableName');
        $stmt = $conn->prepare($rawQuery);
        $stmt->execute();
        $teams = $stmt->fetchAll();

        if (!count($teams)) {
            $io->success('No teams found!');

            return Command::SUCCESS;
        }

        $io->newLine();
        $io->writeln('Games found: ' . count($teams));
        $io->newLine();

        $progressBar = new ProgressBar($output, count($teams));
        $progressBar->start();

        foreach ($teams as $team) {
            $new = new Team();
            $new->setId($team['ID'] + $input->getArgument('idOffset'));
            $new->setIsClub($input->getArgument('isClub'));
            $new->setName($team['nazwa']);
            $new->setShortName($team['skrot']);
            $new->setSlug(null);
            if ($input->getArgument('isClub')) {
                $new->setCountry($team['country']);
                $new->setCity($team['miasto']);
            }

            $this->entityManager->persist($new);

            $progressBar->advance();
        }

        $this->entityManager->flush();

        $progressBar->finish();

        $io->newLine(2);
        $io->success('All teams have been successfully imported');

        return Command::SUCCESS;
    }
}
