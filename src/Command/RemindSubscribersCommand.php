<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:remind-subscribers',
    description: 'Alert the users who subscribed for precipitation updates, whose reminders are due in the next quarters * 15 minutes',
)]
class RemindSubscribersCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('quarters', InputArgument::OPTIONAL, 'Amount of quarters (15 minutes) from now to remind subscribers, omit for 1')
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $quarters = $input->getArgument('quarters') ?? 1;
        $dateTimeToRemindUntil = new \DateTimeImmutable(sprintf('+%d minutes', $quarters * 15));

        $io->note($dateTimeToRemindUntil->format('Y-m-d H:i:s'));

        $io->success('Successfully sent out reminders.');

        // @TODO remind subscribers

        return Command::SUCCESS;
    }
}
