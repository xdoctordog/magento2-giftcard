<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\File\Csv;
use Magento\Framework\Exception\LocalizedException;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;
use SwiftOtter\GiftCard\Api\GiftCardRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SwiftOtter\GiftCard\Model\Import\Processor as ImportProcessor;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;

class ImportGiftCards extends Command
{
    public const FILE = 'file';

    /**
     * @var State
     */
    private $state;

    /**
     * @var Csv
     */
    private $csv;

    /**
     * @var ImportProcessor
     */
    private $importProcessor;

    /**
     * @var GiftCardRepositoryInterface
     */
    private $giftCardRepository;

    /**
     * @var GiftCardInterface
     */
    private $giftCardInterface;

    /**
     * @param State $state
     * @param Csv $csv
     * @param ImportProcessor $importProcessor
     * @param GiftCardRepositoryInterface $giftCardRepository
     * @param GiftCardInterface $giftCardInterface
     * @param string|null $name
     */
    public function __construct(
        State $state,
        Csv $csv,
        ImportProcessor $importProcessor,
        GiftCardRepositoryInterface $giftCardRepository,
        GiftCardInterface $giftCardInterface,
        string $name = null
    ) {
        parent::__construct($name);
        $this->state = $state;
        $this->csv = $csv;
        $this->importProcessor = $importProcessor;
        $this->giftCardRepository = $giftCardRepository;
        $this->giftCardInterface = $giftCardInterface;
    }

    /**
     * {@inheritdoc}
     */
    protected function co__nfigure()
    {
        $this->setName('swiftotter:giftcard:import');
        $this->setDescription('Import Gift Card data from csv file.');
    }


    protected function configure()
    {
        $options = [
            new InputOption(
                self::FILE,
                null,
                InputOption::VALUE_REQUIRED,
                'The CSV file to import'
            )
        ];

        $this->setName('swiftotter:giftcard:import')
            ->setDescription('Import Gift Card data from csv file.')
            ->setDefinition($options);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->setAreaCode(Area::AREA_FRONTEND);
        } catch (LocalizedException $exception) {
            return $output->writeln("<error>{$exception->getMessage()}</error>");
        }

        $filePath = $input->getOption(self::FILE);
        if ($filePath) {
            try {
                $csvData = $this->csv->getData($filePath);
                $rows = $this->importProcessor->prepareArray($csvData);

                foreach ($rows as $row) {
                    try {
                        $giftCard = $this->giftCardInterface->setData($row);
                        $this->giftCardRepository->save($giftCard);
                    } catch (\Exception $exception) {
                        $output->writeln("<error>{$exception->getMessage()}</error>");
                        return \Magento\Framework\Console\Cli::RETURN_FAILURE;
                    }
                }

            } catch (\Exception $exception) {
                $output->writeln("<error>{$exception->getMessage()}</error>");
                return \Magento\Framework\Console\Cli::RETURN_FAILURE;
            }
        } else {
            $output->writeln("<error>" . __('You must include the file name') . "</error>");
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }
        $output->writeln("<info>" . __('Data was sucessfully imported') . "</info>");
        return \Magento\Framework\Console\Cli::RETURN_FAILURE;
    }
}
