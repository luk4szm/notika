<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CopyUsersCommand extends Command
{
    protected static $defaultName = 'app:copy-users';
    protected static $defaultDescription = 'Copy users from old database';

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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Searching for users...');

        $conn = $this->entityManager->getConnection();
        $query = "SELECT * FROM mensa_users";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll();

        if (!count($users)) {
            $io->success('No users found!');

            return Command::SUCCESS;
        }

        $io->newLine();
        $io->writeln('Users found: ' . count($users));
        $io->newLine();

        $progressBar = new ProgressBar($output, count($users));
        $progressBar->start();

        foreach ($users as $user) {
            $new = new User();
            $new->setUsername($user['login']);
            $new->setFirstName($user['imie']);
            $new->setLastName($user['nazwisko']);
            $new->setRoles(["ROLE_USER"]);
            $new->setIsVerified(false);
            $new->setActive(true);
            $new->setEmail($user['email'] ?? null);
            $new->setLastEmail($user['email_notWanted'] ?? null);
            $new->setPassword($user['password']);
            $new->setMember($user['member']);
            $new->setLocale($user['lang']);
            $new->setLanguage($user['lang']);
            $new->setCreatedAt(new \DateTime());

            $this->entityManager->persist($new);

            $progressBar->advance();
        }

        $this->entityManager->flush();

        $progressBar->finish();

        $io->newLine(2);
        $io->success('All users have been successfully imported');

        return Command::SUCCESS;
    }
}
