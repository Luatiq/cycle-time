<?php

namespace App\Command;

use App\Repository\PeriodRepository;
use App\Service\ReminderService;
use DateTime;
use Exception;
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
    private ReminderService $reminderService;

    public function __construct(
        PeriodRepository $periodRepository,
        ReminderService $reminderService,
        string $name = null
    ) {
        $this->periodRepository = $periodRepository;
        $this->reminderService = $reminderService;
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

        $periodsDue = $this->periodRepository->getRemindersDue($dateTimeToRemindUntil);
        foreach ($periodsDue as $period) {
            try {
                $this->reminderService->remind($period);
                $io->success(sprintf('successfully reminded user %s', $period->getUser()->getUsername()));
            } catch (Exception $e) {
                $io->error(sprintf('user %s could not be reminded', $period->getUser()->getUsername()));
            }
        }

        return Command::SUCCESS;
    }
}
