<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CleanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('urls:clean')
            ->setDescription('Delete URLs created more than 14 days ago');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $repository = $this->getContainer()->get('doctrine')->getRepository(\AppBundle\Entity\ShortenUrl::class);
        $oldUrls = $repository->getOldUrls();
        $count = count($oldUrls);

        foreach ($oldUrls as $oldUrl) {
            $em->remove($oldUrl);
        }
        $em->flush();

        $logger = $this->getContainer()->get('logger');
        $message = $count . ' URLs successfully deleted.';

        $logger->info($message);
        $output->writeln($message);
    }
}