<!-- Main Container -->
<div class="container">
    <h1><? print_r($tasks);
    if (empty($tasks)) {
        echo implode(', ', $tasks);
    } ?></h1>
</div>

<!-- Main Container -->
<div class="container">
    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= $stats['total'] ?></div>
            <div class="stat-label">Total Tasks</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['pending'] ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['in_progress'] ?></div>
            <div class="stat-label">In Progress</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['completed'] ?></div>
            <div class="stat-label">Completed</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= count($overdue_tasks ?? []) ?></div>
            <div class="stat-label">Overdue</div>
        </div>
    </div>

    <!-- Actions and Filters -->
    <div class="actions">
        <a href="/tasks/create" class="btn btn-primary">+ Create New Task</a>

        <div class="filters">
            <a href="/tasks" class="btn <?= empty($current_status) ? 'btn-primary' : 'btn-secondary' ?>">All</a>
            <a href="/tasks?status=pending"
                class="btn <?= $current_status === 'pending' ? 'btn-primary' : 'btn-secondary' ?>">Pending</a>
            <a href="/tasks?status=in_progress"
                class="btn <?= $current_status === 'in_progress' ? 'btn-primary' : 'btn-secondary' ?>">In Progress</a>
            <a href="/tasks?status=completed"
                class="btn <?= $current_status === 'completed' ? 'btn-primary' : 'btn-secondary' ?>">Completed</a>
        </div>
    </div>

    <!-- Tasks -->
    <div class="tasks-grid">
        <?php if (empty($tasks)): ?>
            <div class="empty-state">
                <h3>No tasks found</h3>
                <p>Create your first task to get started! <? print_r($tasks[1]['title']) ?></p>
                <a href="/tasks/create" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Create
                    Task</a>
            </div>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <h1>
                    <pre>
                            <? echo $task['isCompleted'] ?>
                        </pre>
                </h1>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>