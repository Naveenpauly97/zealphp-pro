<?

use ZealPHP\App;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

$app = App::instance();

$app->route('/tasks/update/{taskId}', ['methods' => ['POST']], function ($taskId) {

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

        $taskModel = new TaskService();
        $existingTask = $taskModel->getTask($taskId, 1);

        if (!$existingTask) {
            throw new Exception('Task not found for User ID : 1');
        }

        // Prepare update data
        $updateData = [];
        $allowedFields = ['title', 'description', 'status', 'priority', 'due_date'];

        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updateData[$field] = $input[$field];
            }
        }

        // Validate status
        if (isset($updateData['status'])) {
            $validStatuses = ['pending', 'in_progress', 'completed'];
            if (!in_array($updateData['status'], $validStatuses)) {
                throw new Exception('Invalid status. Must be: pending, in_progress, or completed');
            }
        }

        // Validate priority
        if (isset($updateData['priority'])) {
            $validPriorities = ['low', 'medium', 'high'];
            if (!in_array($updateData['priority'], $validPriorities)) {
                throw new Exception('Invalid priority. Must be: low, medium, or high');
            }
        }

        if (empty($updateData)) {
            throw new Exception('No valid fields to update');
        }

        $success = $taskModel->update($taskId, $updateData);

        if ($success) {
            $updatedTask = $taskModel->getTask($taskId, 1);
            ?>
            <script>
                alert('Task updated successfully for task ID: <?= $updatedTask->id ?>');
            </script>
            <?
            header('Location: /tasks');
            exit;
        } else {
            throw new Exception('Failed to update task');
        }

    } catch (\Exception $e) {
        elog("Task update error: " . $e->getMessage(), "error");
        ?>
        <script>
            alert('Error: <?= $e->getMessage() ?>');
        </script>
        <?
    }


});

$app->route('/tasks/{taskId}/edit', ['methods' => ['GET']], function ($taskId) {

    $taskModel = new TaskService();
    // TODO : replace with actual user ID from authentication
    $task = $taskModel->getTask($taskId, 1);
    if (!$task) {
        elog('No tasks found for user ID 1', 'info : TaskService getAllTasks');
        $task = [];
    }
    App::render('/tasks/editPage', ['task' => $task]);
});
