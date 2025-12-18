<?php
// models/IObserver.php

/**
 * The IObserver interface defines the contract for an Observer object.
 * It provides a method for the Subject to notify the Observer of a change.
 */
interface IObserver {
    
    /**
     * Receive update from subject.
     * The observer typically pulls the state change information from the subject passed as an argument.
     * * @param ISubject $subject The subject instance that triggered the update.
     */
    public function update(ISubject $subject);
}