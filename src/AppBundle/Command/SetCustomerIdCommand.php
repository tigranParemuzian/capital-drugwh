<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetCustomerIdCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:set_customer_id_command')
            ->setDescription('Set Customer Id');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        $i = 0;

        foreach($users as $user){

            $user->setCustomerId(rand(1000,10000).$user->getId());

            $em->persist($user);
            $i++;

            $output->writeln("<info>{$i} Users customer id updated .</info>");
        }

        $em->flush();
        $output->writeln("<info>{$i} Users customer id updated.</info>");
    }
}
