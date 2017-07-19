<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:migration_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln("<info>Begin clear Prices for transfer.</info>");

        $em = $this->getContainer()->get('doctrine')->getManager();

        $invoice = $em->getRepository('AppBundle:Invoice')->findOneBy(['number'=>'1500397386']);

        if(!$invoice){
            $output->writeln("<info>invoice not found.</info>");
            exit;
        }
        $i = 0;

        foreach ($invoice->getBooking() as $booking){

            $rexDate = $booking->getExpiryDate();
            $shipDate = $booking->getShipDate();

            $booking->setExpiryDate($shipDate);
            $booking->setShipDate($rexDate);

            $em->persist($booking);

            $i++;

            $output->writeln("<info>{$i} Products has been transferit.</info>");
        }

        $em->flush();

        /*$i = 0;

        foreach($prodictItems as $prodictItem){

            $prodictItem->setPricingCode($prodictItem->getPrice() + ($prodictItem->getPrice() * 0.09));

            $em->persist($prodictItem);

            $i++;

            $output->writeln("<info>{$i} Products has been transferit.</info>");
        }

        $em->flush();*/
        $output->writeln("<info>{$i} Products transfer finished.</info>");

    }

}
