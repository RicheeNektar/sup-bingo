<?php

namespace App\Command\User;

use App\Entity\User\Role;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('bingo:user:elevate')]
final class ElevateCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface  $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();
        $this->addArgument('username', InputArgument::REQUIRED, 'Username who gets admin access');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $newAdmin = $this->userRepository->findOneBy(['username' => $input->getArgument('username')]);
        if ($newAdmin) {
            $newAdmin->setRole(Role::Admin);

            $this->entityManager->flush();
            $output->writeln(sprintf('%s is now the admin', $input->getArgument('username')));
            return Command::SUCCESS;
        }

        $output->writeln(sprintf('%s not found', $input->getArgument('username')));
        return Command::FAILURE;
    }
}