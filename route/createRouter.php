<?

use ZealPHP\App;
use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

$app = App::instance();

$app->route('/tasks/create/{userId}', ['methods' => ['POST']], function ($userId) {
    try {
        $authService = new AuthService();
        $userId = $authService->getCurrentUser()->id;
        $isValidUser = $userId ? $authService->validateUserOwnership($userId) : false;

        if (!$isValidUser) {
            elog("Unauthorized access attempt by user ID: $userId", "error");
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $input = $_POST; // Fallback to POST data if JSON is not provided
        }

        $taskData = [
            'user_id' => $userId,
            'title' => $input['title'],
            'description' => $input['description'] ?? '',
            'status' => $input['status'] ?? 'pending',
            'priority' => $input['priority'] ?? 'medium',
            'due_date' => $input['due_date'] ?? null
        ];

        // Validate status
        $validStatuses = ['pending', 'in_progress', 'completed'];
        if (!in_array($taskData['status'], $validStatuses)) {
            $taskData['status'] = 'pending';
        }

        // Validate priority
        $validPriorities = ['low', 'medium', 'high'];
        if (!in_array($taskData['priority'], $validPriorities)) {
            $taskData['priority'] = 'medium';
        }

        $taskModel = new TaskService();
        $taskId = $taskModel->create($taskData);
        $task = $taskModel->getTask($taskId, $userId);

        if ($task) {
            header('Location: /tasks');
            exit;
        } else {
            throw new Exception('Failed to create task');
        }

    } catch (\Exception $e) {
        elog("Task creation error: " . $e->getMessage(), "error");
    }

});

$app->route('/tasks/create', ['methods' => ['GET']], function () {
    $authService = new AuthService();
    $userId = $authService->getCurrentUser()->id;
    $isValidUser = $userId ? $authService->validateUserOwnership($userId) : false;

    if (!$isValidUser) {
        elog("Unauthorized access attempt by user ID: $userId", "error");
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }
    elog("Rendering create task page");
    App::render('/tasks/createPage');
});