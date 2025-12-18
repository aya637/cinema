<?php
// models/SupportTicket.php

require_once __DIR__ . '/Model.php';

class SupportTicket extends Model
{
    /**
     * Get all tickets with user information
     */
    public function getAllTickets()
    {
        $sql = "SELECT t.id, t.subject, t.status, t.priority, t.created_at,
                       u.name as requester_name, u.email as requester_email
                FROM support_tickets t
                LEFT JOIN users u ON t.user_id = u.id
                ORDER BY t.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get ticket by ID with full details
     */
    public function getTicketById($ticket_id)
    {
        $sql = "SELECT t.*, u.name as requester_name, u.email as requester_email
                FROM support_tickets t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE t.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $ticket_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get ticket thread/messages
     */
    public function getTicketThread($ticket_id)
    {
        $sql = "SELECT tm.*, 
                       CASE WHEN tm.is_staff = 1 THEN s.name ELSE u.name END as sender_name
                FROM ticket_messages tm
                LEFT JOIN staff s ON tm.is_staff = 1 AND tm.sender_id = s.id
                LEFT JOIN users u ON tm.is_staff = 0 AND tm.sender_id = u.id
                WHERE tm.ticket_id = :ticket_id
                ORDER BY tm.created_at ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':ticket_id' => $ticket_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Save a reply to a ticket
     */
    public function saveReply($ticket_id, $message, $staff_id, $new_status = null)
    {
        // Insert message
        $sql = "INSERT INTO ticket_messages (ticket_id, sender_id, is_staff, message, created_at)
                VALUES (:ticket_id, :staff_id, 1, :message, NOW())";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':ticket_id' => $ticket_id,
            ':staff_id' => $staff_id,
            ':message' => $message
        ]);

        if (!$success) {
            return false;
        }

        // Update status if provided
        if ($new_status && $new_status !== '') {
            $update_sql = "UPDATE support_tickets 
                          SET status = :status, updated_at = NOW()
                          WHERE id = :id";
            $update_stmt = $this->db->prepare($update_sql);
            $update_stmt->execute([
                ':status' => $new_status,
                ':id' => $ticket_id
            ]);
        }

        return true;
    }

    public function closeTicket($ticket_id, $staff_id)
    {
        $sql = "UPDATE support_tickets 
                SET status = 'Closed', updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $ticket_id]);
    }

    /**
     * Create a new support ticket
     */
    public function createTicket($userId, $subject, $description, $priority = 'Medium')
    {
        $sql = "INSERT INTO support_tickets (user_id, subject, description, priority, status, created_at, updated_at) 
                VALUES (:user_id, :subject, :description, :priority, 'Open', NOW(), NOW())";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':user_id' => $userId,
            ':subject' => $subject,
            ':description' => $description,
            ':priority' => $priority
        ]);

        if (!$success) {
            return false;
        }

        return $this->db->lastInsertId();
    }
}