<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:create-admin', description: 'Creates a new admin.')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ValidatorInterface $validator
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

        $output->writeln('Creating a user..');

        $user = $this->userRepository->create([
            'email' => $email,
            'password' => $password,
            'firstName' => 'admin',
            'lastName' => 'admin',
            'phone' => '+1111111',
            'roles' => ['ROLE_ADMIN']
        ]);

        $constraintViolationList = $this->validator->validate($user);
        if (count($constraintViolationList) > 0) {
            $output->writeln((string) $constraintViolationList);

            return Command::INVALID;
        }

        $this->userRepository->save($user, true);
        $output->writeln('Admin user was created.');

        return Command::SUCCESS;
    }
}