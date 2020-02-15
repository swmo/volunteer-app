<?php

namespace App\Utils;

use App\Entity\Log;
use App\Manager\UserOrganisationManager;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class MonologDbHandler extends AbstractProcessingHandler
{

    protected $conn = null;

    public function setEntityManager(Connection $conn){
        $this->conn = $conn;
    }

    /**
     * Called when writing to our database
     * @param array $record
     */
    protected function write(array $record)
    {

      
        

        $logEntry = new Log();
        $logEntry->setMessage($record['message']);
        $logEntry->setLevel($record['level']);
        $logEntry->setLevelName($record['level_name']);
        $logEntry->setExtra($record['extra']);
        $logEntry->setContext($record['context']);
       //  $this->em->persist($logEntry);
        // $this->em->flush();

       // $result = $this->conn->query('select * from log');

       
       $this->conn->insert('log', array(
           'message' => $record['message'], 
           'level' => $record['level'],
           'level_name' => $record['level_name'],
           'extra' => serialize($record['extra']),
           'context' => serialize($record['context'])
        ));
        

    
/*
       $queryBuilder = $queryBuilder 
        ->insert('App\Entity\Log', 'l')
        ->set('l.message', 'banned');
        
        $queryBuilder->execute();
*/
       

    }
}