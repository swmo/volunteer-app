<?php

namespace App\Doctrine;

use App\Entity\Enrollment;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Utils\MergePerson;
use Doctrine\ORM\Events;

class MergePersonListener implements EventSubscriber
{
    private $mergePerson = null;

    public function __construct(MergePerson $mergePerson)
    {
        $this->mergePerson = $mergePerson;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        //Der Event wird bei jeder Entity abgefeuert, dies stellt sicher das nur bei der Enrollment Class weitergearbeitet wird.
        if(!$entity instanceof Enrollment){
            return;
        }
        $this->mergePerson->mergeEnrollemnt($entity);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        //Der Event wird bei jeder Entity abgefeuert, dies stellt sicher das nur bei der Enrollment Class weitergearbeitet wird.
        if(!$entity instanceof Enrollment){
            return;
        }

        $this->mergePerson->mergeEnrollemnt($entity);
    }

    public function getSubscribedEvents()
    {
        // postPerists wird nachdem die Entity geinserted wird aufgerufen
        // postUpdate wird nachdem die Entity geupdated wird aufgerufen
        return [
            Events::postPersist,
            Events::postUpdate
        ];
    }
}