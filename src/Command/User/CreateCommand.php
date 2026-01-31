<?php

namespace App\Command\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand('bingo:user:create')]
final class CreateCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();
        $this->addArgument('username', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');

        if ($this->userRepository->find($username)) {
            $output->writeln("$username already exists.");
            return Command::FAILURE;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $io->askHidden('Password:')));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("$username created");
        return Command::SUCCESS;
    }
}