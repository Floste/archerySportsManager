<?php

namespace Sf\ArcherySportsManagerBundle\Command;

use Sf\ArcherySportsManagerBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 14/11/2017
 * Time: 14:31
 */
class AddUserCommand extends ContainerAwareCommand
{
    /** @var  \Doctrine\ORM\EntityManager */
    private $manager;

    protected function configure()
    {
        $this
            ->setName('archerySportsManager:security:addAdmin')
            ->setDescription('Ajout d\'un administrateur')
            ->addArgument('addressMail', InputArgument::REQUIRED, 'address mail')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('doctrine')->getEntityManager();

        $user = new User();
        $user->setEmail($input->getArgument('addressMail'));
        $user->setDenomination($input->getArgument('addressMail'));
        $user->setIsActive(true);
        $user->setIsAdmin(true);

        $newPwd = $this->getRandomPwd(8);
        $encodePassword = $this->getContainer()->get('security.password_encoder')->encodePassword($user, $newPwd);
        $user->setPlainPassword($newPwd);
        $user->setPassword($encodePassword);
        $this->manager->persist($user);
        $this->manager->flush();
        $output->writeln("Mot de passe : " . $newPwd);
    }

    private function getRandomPwd($length){
        $a = str_split("abcdefghijklmnpqrstuvwxyABCDEFGHJKLMNPQRSTUVWXY23456789");
        shuffle($a);
        return substr( implode($a), 0, $length );
    }

}