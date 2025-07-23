<?

use ZealPHP\App;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

$app = App::instance();

$app->route('/tasks/create/{userId}', ['methods' => ['POST']], function ($userId) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $input = $_POST; // Fallback to POST data if JSON is not provided
        }

        // TODO: Handle /tasks/create error messages
        // if (!$input || empty($input['title'])) {
        //     $this->response($this->json([
        //         'success' => false,
        //         'message' => 'Title is required'
        //     ]), 400);
        //     return;
        // }

        $taskData = [
            'user_id' => 1,
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
        $task = $taskModel->getTask($taskId, 1);

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

    elog("Rendering create task page");
    App::render('/tasks/createPage');
});