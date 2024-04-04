<?php

namespace App\Command;

use App\Repository\PeriodRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:remind-subscribers',
    description: 'Alert the users who subscribed for precipitation updates, whose reminders are due in the next X quarters (arg: int * 15 minutes)',
)]
class RemindSubscribersCommand extends Command
{
    private PeriodRepository $periodRepository;

    public function __construct(
        PeriodRepository $periodRepository,
        string $name = null
    ) {
        $this->periodRepository = $periodRepository;
        parent::__construct($name);
    }

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

        $remindersDue = $this->periodRepository->getRemindersDue(new \DateTime('-24 hours'));

        // @TODO remind subscribers
        $io->note($dateTimeToRemindUntil->format('Y-m-d H:i:s'));

        $io->success('Successfully sent out reminders.');

        return Command::SUCCESS;
    }
}
