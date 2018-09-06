<?php

namespace App\Command;

use App\Entity\Master;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdminCreateCommand extends Command
{
    protected static $defaultName = 'app:AdminCreate';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager= $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('firstname', InputArgument::REQUIRED, 'firstname description')
            ->addArgument('lastname', InputArgument::REQUIRED, 'lastname description')
            ->addArgument('email', InputArgument::REQUIRED, 'email description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');
        if (null === $email || null === $firstname || null === $lastname) {
            $io->error('Ta pas rentré d\'email et/ou de firstname et/ou lastname salaud');
            return;
        }
        $io->note(sprintf('Create a Master for email: %s, firstname: %s, lastname: %s', $email,$firstname,$lastname));
        $user = new Master();
        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);

        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $io->success(sprintf('You\'ve created an Admin-user with email: %s and firstname %s  and lastname %s', $email, $firstname,$lastname ));
    }
}
