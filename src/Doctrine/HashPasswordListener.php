<?php

namespace App\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class HashPasswordListener implements EventSubscriber
{

    private $passwordEncoder;

    /**
     * HashPasswordListener constructor.
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        //Der Event wird bei jeder Entity abgefeuert, dies stellt sicher das nur bei der User Class weitergearbeitet wird.
        if(!$entity instanceof User){
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        //Der Event wird bei jeder Entity abgefeuert, dies stellt sicher das nur bei der User Class weitergearbeitet wird.
        if(!$entity instanceof User){
            return;
        }

        $this->encodePassword($entity);

        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function getSubscribedEvents()
    {
        // prePerists wird bevor die Entity geinserted wird aufgerufen
        // preUpdate wird bevor die Entity geupdated wird aufgerufen
        return ['prePersist','preUpdate'];
    }

    /**
     * @param User $entity
     * @description Methode wird fürs encode des Passwortes benötigt
     */
    public function encodePassword(User $entity)
    {
        if (!$entity->getPlainPassword()) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());

        $entity->setPassword($encoded);
    }

}