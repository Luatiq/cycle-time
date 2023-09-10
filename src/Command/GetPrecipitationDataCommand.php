<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\BuienradarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:get-precipitation-data',
    description: 'Get precipitation data for all unique locations from Buienradar',
)]
class GetPrecipitationDataCommand extends Command
{
    private BuienradarService $buienradarService;
    private LocationRepository $locationRepository;

    public function __construct(
        BuienradarService $buienradarService,
        LocationRepository $locationRepository
    ) {
        $this->buienradarService = $buienradarService;
        $this->locationRepository = $locationRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // @TODO improve by grouping by lat/long seperately
        $coords = $this->locationRepository->getUniqueCoords();
        $io->info(sprintf('Found %d unique coordinate combinations', count($coords)));

        foreach ($coords as $coord) {
            $this->buienradarService->updatePrecipitation(
                $coord['latitude'],
                $coord['longitude']
            );
        }

        $io->success('Success');

        return Command::SUCCESS;
    }
}
