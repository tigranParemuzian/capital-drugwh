<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigratePricesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:migrate_prices_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        //todo: not finished
        $output->writeln("<info>Begin clear Prices for transfer.</info>");

        $em = $this->getContainer()->get('doctrine')->getManager();

        $prodictItems = $em->getRepository('AppBundle:Product')->findAll();

        $i = 0;

        foreach($prodictItems as $prodictItem){

            $prodictItem->setPricingCode($prodictItem->getPrice() + ($prodictItem->getPrice() * 0.09));

            $em->persist($prodictItem);

            $i++;

            $output->writeln("<info>{$i} Products has been transferit.</info>");
        }

        $em->flush();
        $output->writeln("<info>{$i} Products transfer finished.</info>");

    }
}
