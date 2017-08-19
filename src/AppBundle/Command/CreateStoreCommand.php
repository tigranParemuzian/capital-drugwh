<?php

namespace AppBundle\Command;

use AppBundle\Entity\ProductStorage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateStoreCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:create_store_command')
            ->setDescription('Create Product nullable store command');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        //todo: not finished
        $output->writeln("<info>Begin Crate Store for Products.</info>");

        $em = $this->getContainer()->get('doctrine')->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        foreach ($products as $kay=>$product){

            $output->writeln("<info>Begin Crate Store for Product. {$product->getName()}</info>");

            try{

                $productStore = new ProductStorage();
                $productStore->setProduct($product);
                $productStore->setCount(0);

                $em->persist($productStore);
                $output->writeln("<info>{$kay} Product Store has been created. {$product->getName()}</info>");

            }catch (\Exception $exception){
                $output->writeln("<info>{$exception->getMessage()} : Product Store can`t created for {$product->getName()}</info>");
            }

        }

        $em->flush();

        $output->writeln("<info>{$kay} Product Store has been created.</info>");
    }
}
