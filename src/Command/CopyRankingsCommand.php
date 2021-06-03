<?php

namespace App\Command;

use App\Entity\Ranking;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CopyRankingsCommand extends Command
{
    protected static $defaultName = 'app:copy-rankings';
    protected static $defaultDescription = 'Copy rankings from old database';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Searching for rankings...');

        $conn = $this->entityManager->getConnection();
        $rawQuery = "SELECT * FROM mensa_rankings";
        $stmt = $conn->prepare($rawQuery);
        $stmt->execute();
        $rankings = $stmt->fetchAll();

        if (!count($rankings)) {
            $io->success('No rankings found!');

            return Command::SUCCESS;
        }

        $io->newLine();
        $io->writeln('Rankings found: ' . count($rankings));
        $io->newLine();

        $progressBar = new ProgressBar($output, count($rankings));
        $progressBar->start();

        foreach ($rankings as $ranking) {
            $new = new Ranking();
            $new->setId($ranking['ID'])
                ->setSeason($this->entityManager->getRepository(Season::class)->find($ranking['seasonID']))
                ->setName($ranking['name'])
                ->setSlug(null)
                ->setIsActive($ranking['active'])
                ->setShortName($ranking['skrot'])
                ->setMark($ranking['sub'])
                ->setStartRound($ranking['kolStart'])
                ->setEndRound($ranking['kolEnd'])
                ->setCreatedAt(new \DateTime($ranking['dtStart']))
                ->setCloseAt(new \DateTime($ranking['dtEnd']));

            $this->entityManager->persist($new);

            $progressBar->advance();
        }

        $this->entityManager->flush();

        $progressBar->finish();

        $io->newLine(2);
        $io->success('All rankings have been successfully imported');

        return Command::SUCCESS;
    }
}
