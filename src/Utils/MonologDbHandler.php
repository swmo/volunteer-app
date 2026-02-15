<?php

namespace App\Utils;

use App\Entity\Log;
use Doctrine\DBAL\Connection;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class MonologDbHandler extends AbstractProcessingHandler
{

    protected $conn = null;

    public function setEntityManager(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Called when writing to our database
     */
    protected function write(LogRecord $record): void
    {

        $logEntry = new Log();
        $logEntry->setMessage($record->message);
        $logEntry->setLevel($record->level->value);
        $logEntry->setLevelName($record->level->name);
        $logEntry->setExtra($record->extra);
        $logEntry->setContext($record->context);
       //  $this->em->persist($logEntry);
        // $this->em->flush();

       // $result = $this->conn->query('select * from log');

       
        $this->conn->insert('log', [
            'message' => $record->message,
            'level' => $record->level->value,
            'level_name' => $record->level->name,
            'extra' => serialize($record->extra),
            'context' => serialize($record->context),
        ]);
        

    
/*
       $queryBuilder = $queryBuilder 
        ->insert('App\Entity\Log', 'l')
        ->set('l.message', 'banned');
        
        $queryBuilder->execute();
*/
       
    }
}
