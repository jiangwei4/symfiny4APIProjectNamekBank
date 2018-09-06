<?php

namespace App\Command;

use App\Repository\CreditcardRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreditcardCountCommand extends Command
{
    protected static $defaultName = 'app:CreditcardCount';
    private $creditcardRepository;

    public function __construct(CreditcardRepository $creditcardRepository)
    {
        $this->creditcardRepository= $creditcardRepository;
        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $count = $this->creditcardRepository->findAll();
        $io->success(sprintf('il y a %s creditcard(s)', sizeof($count)));
    }
}
