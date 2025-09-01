<?

use ZealPHP\Database\Connection;
use ZealPHP\Models\Task;
use ZealPHP\Repositories\TaskRepository;

$userId = 1; // Example user ID

$taskRepo = new TaskRepository();

$task = $taskRepo->findByUserIdAndStatus($userId, 'pending');

?>
<pre>
    <?
    print_r(Task::builder($task));
    ?>
</pre>
<?

$pg = Connection::getPostgres();

$stmt = $pg->prepare(
    'SELECT status, COUNT(*) AS count
     FROM tasks
     WHERE user_id = :uid
     GROUP BY status'
);
$stmt->execute([':uid' => $userId]);

$stats = [
    'pending' => 0,
    'in_progress' => 0,
    'completed' => 0,
    'total' => 0
];

while ($row = $stmt->fetch()) {
    $stats[$row['status']] = (int) $row['count'];
    $stats['total'] += (int) $row['count'];
}

?>
<pre>
    <?
    var_dump($stats);
    ?>
</pre>