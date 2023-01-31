<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-admin', description: 'Creates a new admin.')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED)
             ->addArgument('password', InputArgument::REQUIRED);
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        // TODO: add email validation.
//        if ($email) {
//            return Command::INVALID;
//        }

        $output->writeln('Creating a user..');

        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);
        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        $output->writeln('Admin user was created.');

        return Command::SUCCESS;
    }
}