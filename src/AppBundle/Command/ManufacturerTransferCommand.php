<?php

namespace AppBundle\Command;

use AppBundle\Entity\Manufacturer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ManufacturerTransferCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:manufacturer_transfer_command')
            ->setDescription('Manufacturer transfer');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        //todo: not finished
        $output->writeln("<info>Begin clear Manufacturer for transfer.</info>");

        $em = $this->getContainer()->get('doctrine')->getManager();

        $prodictItems = $em->getRepository('AppBundle:ProductItem')->findAll();

        $i = 0;

        foreach($prodictItems as $prodictItem){

            $manufacturer = $prodictItem->getManufacturer();

            $manufacturers = $em->getRepository('AppBundle:Manufacturer')->findOneBy(array('name'=>$manufacturer));

            if(!$manufacturers){
                $manufacturers = new  Manufacturer();

                $manufacturers->setName($manufacturer);
                $em->persist($manufacturers);
            }

            $prodictItem->setManufacturers($manufacturers);
            $em->persist($prodictItem);
            $em->flush();
$i++;

            $output->writeln("<info>{$i} manufacturers has been transferit.</info>");
        }

        $output->writeln("<info>{$i} manufacturers transfer finished.</info>");
    }
}
