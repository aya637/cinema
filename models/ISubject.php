<?php
// models/ISubject.php

/**
 * The ISubject interface defines the contract for an Observable object.
 * It allows Observers to register, unregister, and receive notifications.
 */
interface ISubject {
    
    /**
     * Attach an observer to the subject.
     */
    public function attach(IObserver $observer);

    /**
     * Detach an observer from the subject.
     */
    public function detach(IObserver $observer);

    /**
     * Notify all attached observers about an event/change.
     */
    public function notify();
}