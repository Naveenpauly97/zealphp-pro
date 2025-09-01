<?php

namespace ZealPHP\WebSocket;

use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

class TaskWebSocketHandler
{
    private TaskService $taskService;
    private AuthService $authService;
    private array $wsSession = [];
    private array $connections = [];

    public function __construct()
    {
        $this->taskService = new TaskService();
        $this->authService = new AuthService();
    }

    public function onOpen(Server $server, Request $request): void
    {
        $fd = $request->fd;
        $userId = $this->getUserIdFromRequest($request);
        elog("Session TEstingg----- " . $fd . " --UID - $userId-- " . json_encode($request->cookie), "debug");
        if (!$userId) {
            elog("WebSocket: Unauthorized connection attempt from fd: $fd", "warning");
            $server->close($fd);
            return;
        }

        $this->connections[$fd] = [
            'user_id' => $userId,
            'connected_at' => time()
        ];

        //elog"WebSocket: User $userId connected with fd: $fd");

        // Send initial task data
        $this->sendTaskUpdate($server, $fd, $userId);
    }

    public function onMessage(Server $server, Frame $frame): void
    {
        // elog("WebSocket: Received message from fd {$frame->fd}", "debug");
        $fd = $frame->fd;
        $data = json_decode($frame->data, true);
        // echo "Received onMessage: {$frame}\n";
        // print_r($frame);
        // echo "Received onMessage 2: {$frame}\n";
        // print_r($data);
        //elog'$this->connections[$fd]' . ": Received message from fd " . json_encode($this->connections), "debug");
        if (!isset($this->connections[$fd])) {
            //elog"WebSocket: Message from unknown connection fd: $fd", "warning");
            return;
        }

        $userId = $this->connections[$fd]['user_id'];
        // print ("Connections: ");
        // print_r($this->connections);

        // //elog"WebSocket: User $userId sent message: {$frame->data}", "debug");

        try {
            switch ($data['action'] ?? '') {
                case 'mark_complete':
                    $this->handleMarkComplete($server, $fd, $userId, $data);
                    break;

                case 'delete_task':
                    $this->handleDeleteTask($server, $fd, $userId, $data);
                    break;

                case 'get_tasks':
                    // $this->sendTaskUpdate($server, $fd, $userId, $data['status'] ?? null);
                    $this->sendTaskUpdate($server, $fd, $userId, $data['key'] ?? null, $data['filter'] ?? null);
                    break;

                default:
                    $this->sendError($server, $fd, 'Unknown action');
            }
        } catch (\Exception $e) {
            //elog"WebSocket error: " . $e->getMessage(), "error");
            $this->sendError($server, $fd, 'Internal server error');
        }
    }

    public function onClose(Server $server, int $fd): void
    {
        if (isset($this->connections[$fd])) {
            $userId = $this->connections[$fd]['user_id'];
            //elog"WebSocket: User $userId disconnected from fd: $fd");
            unset($this->connections[$fd]);
            $sessionData = TaskWebSocketHandler::getSession($this->wsSession[$userId]['session_id'] ?? '');
            // print("Session Data: \n");
            // print_r($sessionData);
            // print("Session Data: All closed \n");
            unset($sessionData[$userId]);
            // print_r($sessionData);
            // print("Session Data: All closed \n");
            // print($this->wsSession[$userId]['session_id'] . "\n");
            $isupdated = TaskWebSocketHandler::writeSession($this->wsSession[$userId]['session_id'], $sessionData);
            if ($isupdated) {
                print ("Session Data: All maked empty \n");
            }
            $this->wsSession = [];
            // print_r($this->wsSession);
        }
    }

    private function handleMarkComplete(Server $server, int $fd, int $userId, array $data): void
    {
        $userId = $this->authService->getCurrentUser()->id;
        $isValidUser = $userId ? $this->authService->validateUserOwnership($userId) : false;

        if (!$isValidUser) {
            //elog"Unauthorized access attempt by user ID: $userId", "error");
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        $taskId = (int) ($data['task_id'] ?? 0);

        if (!$taskId) {
            $this->sendError($server, $fd, 'Task ID is required');
            return;
        }

        // Verify task ownership
        $task = $this->taskService->getTask($taskId, $userId);
        if (!$task) {
            $this->sendError($server, $fd, 'Task not found');
            return;
        }

        // Update task status
        $success = $this->taskService->updateTaskByUserId($taskId, $userId, ['status' => 'completed']);

        if ($success) {
            // Send updated task list to user
            $this->sendTaskUpdate($server, $fd, $userId);

            $this->sendSuccess($server, $fd, 'Task marked as completed', [
                'task_id' => $taskId,
                'action' => 'mark_complete'
            ]);
        } else {
            $this->sendError($server, $fd, 'Failed to update task');
        }
    }

    private function handleDeleteTask(Server $server, int $fd, int $userId, array $data): void
    {
        $userId = $this->authService->getCurrentUser()->id;
        $isValidUser = $userId ? $this->authService->validateUserOwnership($userId) : false;

        if (!$isValidUser) {
            //elog"Unauthorized access attempt by user ID: $userId", "error");
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        $taskId = (int) ($data['task_id'] ?? 0);

        if (!$taskId) {
            $this->sendError($server, $fd, 'Task ID is required');
            return;
        }

        // Verify task ownership
        $task = $this->taskService->getTask($taskId, $userId);
        if (!$task) {
            $this->sendError($server, $fd, 'Task not found');
            return;
        }

        // Delete task
        $success = $this->taskService->deleteTaskByUserId($taskId, $userId);

        if ($success) {
            // Send updated task list to user
            $this->sendTaskUpdate($server, $fd, $userId);

            $this->sendSuccess($server, $fd, 'Task deleted successfully', [
                'task_id' => $taskId,
                'action' => 'delete_task'
            ]);
        } else {
            $this->sendError($server, $fd, 'Failed to delete task');
        }
    }

    private function sendTaskUpdate(Server $server, int $fd, int $userId, ?string $key = null, ?string $filter = null): void
    {
        $filters = [];
        if ($key && $filter) {
            $filters[$key] = $filter;
        }
        //elog"WebSocket: Sending task update for user $userId with filters: " . json_encode($filters), "debug");

        // echo "Server Task Update Testing : -------------------------";
        // elog("Filter Checking: " . json_encode($filters), "debug");
        // elog("Task condiftion checking: " . $key === 'task_id' && is_numeric($filter), "debug");
        $tasks = ($key === 'task_id' && is_numeric($filter))
            ? $this->taskService->getTask($filter, $userId)
            : $this->taskService->getAllTasks($userId, ($key === 'status' && $filter !== 'all') ? ['status' => $filter] : []);
        // $tasks = $this->taskService->getAllTasks($userId, $filters);
        $stats = $this->taskService->getTaskStats($userId);
        $overdue_tasks = $this->taskService->getOverdueTasks($userId);
        $current_status = $filters['status'] ?? 'all';
        // elog("current_status ---key --{$key} ---filter {$filter}--------current status-----".$current_status, "debug");
        // elog("Final Task data Checking: " . json_encode($tasks), "debug");

        ob_start(); // Start output buffering
        require __DIR__ . '/../../template/tasks/listPageContent.php';
        $html = ob_get_clean();
        // elog("HTML content generated for task update".$html, "debug");

        $response = [
            'type' => 'task_update',
            'data' => [
                'tasks' => array_map(fn($task) => $task->toArray(), $tasks),
                'stats' => $stats,
                'overdue_tasks' => array_map(fn($task) => $task->toArray(), $overdue_tasks),
                'current_status' => $current_status
            ],
            'html' => $html
        ];

        $server->push($fd, json_encode($response));
    }

    private function sendSuccess(Server $server, int $fd, string $message, array $data = []): void
    {
        $response = [
            'type' => 'success',
            'message' => $message,
            'data' => $data
        ];

        $server->push($fd, json_encode($response));
    }

    private function sendError(Server $server, int $fd, string $message): void
    {
        $response = [
            'type' => 'error',
            'message' => $message
        ];

        $server->push($fd, json_encode($response));
    }

    private function getUserIdFromRequest(Request $request): ?int
    {
        // Try to get user ID from session cookie
        $sessionId = $request->cookie[session_name()] ?? null;
        print ("SessionId : $sessionId\n");
        $this->wsSession = TaskWebSocketHandler::getSession($sessionId);
        $userId = $this->wsSession['user_id'] ?? null;
        elog("WebSocket: User ID from session: $userId", "debug");
        if ($userId) {
            if (isset($this->wsSession[$userId])) {
                elog("WebSocket: Session leakage detected User ID {$userId} exists", "warning");
            }
            $this->wsSession[$userId] = [
                'fd' => $request->fd,
                'session_id' => $sessionId,
                'created_at' => time()
            ];
            TaskWebSocketHandler::writeSession($sessionId, $this->wsSession);
        }
        return $userId ?? null;
        // $this->sessionId = $request->cookie[session_name()] ?? null;
        // print ("SessionId : $this->sessionId\n");
        // $sessionData = TaskWebSocketHandler::getSession($this->sessionId);
        // $userId = $sessionData['user_id'] ?? null;
        // elog("WebSocket: User ID from session: $userId", "debug");
        // if ($userId) {
        //     $sessionData['ws_data'] = [$userId => ['fd' => $this->fd]];
        //     TaskWebSocketHandler::writeSession($this->sessionId, $sessionData);
        // }
        // return $userId ?? null;
    }

    public static function getSession(string $sessionId): array
    {
        // print "WebSocket : Fetching Session Data";
        if (!$sessionId) {
            return [];
        }
        // Read session file to get user data
        $sessionFile = "/var/lib/php/sessions/sess_$sessionId";
        if (!file_exists($sessionFile)) {
            return [];
        }
        return unserialize(file_get_contents($sessionFile));
    }
    public static function writeSession(string $sessionId, array $sessionData): mixed
    {
        // print "WebSocket : Writting Session Data";
        // print($sessionId ."\n");
        // print_r($sessionData);
        // elog( . json_encode($sessionData['ws_data']));
        $sessionFile = "/var/lib/php/sessions/sess_$sessionId";
        return file_put_contents($sessionFile, serialize($sessionData));
    }
}