<?php
/**
 * Handles all routing and business logic for the Support Ticket management system.
 */
class SupportController extends Controller
{
    private $ticketModel;

    public function __construct()
    {
        // In a real application, we would initialize the model here.
        $this->ticketModel = new SupportTicket();
    }

    /**
     * Shows the main list of all support tickets.
     * Corresponds to the view: views/support/tickets.php
     */
    public function index()
    {
        // Fetch tickets from database
        $tickets = $this->ticketModel->getAllTickets();

        // Format tickets for view
        $formattedTickets = [];
        foreach ($tickets as $ticket) {
            $formattedTickets[] = [
                'id' => $ticket['id'],
                'subject' => $ticket['subject'],
                'requester' => $ticket['requester_name'] ?? 'Unknown',
                'priority' => $ticket['priority'] ?? 'Medium',
                'status' => $ticket['status'] ?? 'New',
                'created_at' => $ticket['created_at']
            ];
        }

        $data['tickets'] = $formattedTickets;
        $data['title'] = 'Support Ticket Management';

        // Load the view, passing the ticket data
        $this->view('support/tickets', $data);
    }

    /**
     * Shows the detailed thread for a single ticket.
     * Corresponds to the view: views/support/thread.php
     *
     * @param string $ticketId The ID of the ticket to display.
     */
    public function thread($ticketId = null)
    {
        // Get ticket ID from URL parameter or function parameter
        $ticketId = $ticketId ?? ($_GET['id'] ?? null);

        if (!$ticketId) {
            $this->redirect('/support/tickets');
            return;
        }

        // 1. Fetch the main ticket details from database
        $ticket = $this->ticketModel->getTicketById($ticketId);

        if (!$ticket) {
            $this->view('support/thread', [
                'ticket' => null,
                'title' => 'Ticket Not Found'
            ]);
            return;
        }

        // 2. Fetch the conversation thread from database
        $thread = $this->ticketModel->getTicketThread($ticketId);

        // Format thread messages for view
        $formattedThread = [];
        foreach ($thread as $message) {
            $formattedThread[] = [
                'message' => $message['message'],
                'sender_id' => $message['sender_id'],
                'is_staff' => $message['is_staff'],
                'sender_name' => $message['sender_name'] ?? ($message['is_staff'] ? 'Staff' : 'User'),
                'created_at' => $message['created_at']
            ];
        }

        $data['ticket'] = [
            'id' => $ticket['id'],
            'subject' => $ticket['subject'],
            'description' => $ticket['description'] ?? '',
            'status' => strtolower($ticket['status'] ?? 'open'),
            'priority' => $ticket['priority'] ?? 'Medium',
            'user_id' => $ticket['user_id'],
            'requester_name' => $ticket['requester_name'] ?? 'Unknown',
            'requester_email' => $ticket['requester_email'] ?? '',
            'created_at' => $ticket['created_at'],
            'thread' => $formattedThread
        ];
        $data['title'] = 'Ticket #' . $ticket['id'] . ' - ' . $ticket['subject'];

        // Load the ticket thread view
        $this->view('support/thread', $data);
    }

    /**
     * Handles the form submission to post a new reply to a ticket.
     */
    public function reply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/support/tickets');
            return;
        }

        $ticketId = $_POST['ticket_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $newStatus = $_POST['new_status'] ?? null;

        // Get staff ID from session (you may need to adjust this based on your auth system)
        $staffId = $_SESSION['staff_id'] ?? 1; // Default to 1 if no session

        if (!$ticketId || $message === '') {
            $this->view('support/thread', [
                'ticket' => null,
                'title' => 'Error',
                'errors' => ['Ticket ID and message are required.']
            ]);
            return;
        }

        // Save reply to database
        $saved = $this->ticketModel->saveReply($ticketId, $message, $staffId, $newStatus);

        if ($saved) {
            // Redirect back to thread
            header('Location: ' . BASE_URL . '/public/index.php?url=support/thread&id=' . $ticketId);
            exit;
        } else {
            $this->view('support/thread', [
                'ticket' => null,
                'title' => 'Error',
                'errors' => ['Failed to save reply. Please try again.']
            ]);
        }
    }

    /**
     * Shows the create ticket form.
     * Corresponds to: views/support/request.php
     */
    public function create()
    {
        $this->view('support/request', [
            'title' => 'Submit a Support Request',
            'errors' => []
        ]);
    }

    /**
     * Handles POST request to create a new ticket.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/support/create');
            return;
        }

        $subject = trim($_POST['subject'] ?? '');
        $priority = $_POST['priority'] ?? 'Medium';
        $description = trim($_POST['description'] ?? '');

        // Mock User ID - In reality, this comes from session
        $userId = $_SESSION['user_id'] ?? 1;

        $errors = [];
        if ($subject === '')
            $errors[] = 'Subject is required.';
        if ($description === '')
            $errors[] = 'Description is required.';

        if (!empty($errors)) {
            $this->view('support/request', [
                'title' => 'Submit a Support Request',
                'errors' => $errors,
                'old' => $_POST
            ]);
            return;
        }

        $ticketId = $this->ticketModel->createTicket($userId, $subject, $description, $priority);

        if ($ticketId) {
            // Redirect to the new ticket thread
            header('Location: ' . BASE_URL . '/public/index.php?url=support/thread&id=' . $ticketId);
            exit;
        } else {
            $this->view('support/request', [
                'title' => 'Submit a Support Request',
                'errors' => ['Database error: Failed to create ticket.'],
                'old' => $_POST
            ]);
        }
    }

}