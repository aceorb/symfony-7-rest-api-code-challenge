<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use App\Service\CustomerImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'app:import-customers')]
class ImportCustomersCommand extends Command
{

    private $importer;

    public function __construct(CustomerImporter $importerService)
    {
        $this->importer = $importerService;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Imports customers from a third-party API')
            ->setHelp('This command allows you to import customers from a third-party API');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importer->importCustomers();
        $output->writeln('100 Customers with nationality AU have been imported successfully.');

        return Command::SUCCESS;
    }
}