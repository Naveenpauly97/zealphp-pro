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
                <p>Create your first task to get started!</p>
                <a href="/tasks/create" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Create
                    Task</a>
            </div>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <div
                    class="task-card <?= $task->isCompleted() ? 'completed' : '' ?> <?= $task->isOverdue() ? 'overdue' : '' ?>">
                    <div class="task-header">
                        <div>
                            <div class="task-title"><?= htmlspecialchars($task->title) ?></div>
                            <div class="task-meta">
                                <span
                                    class="status-badge status-<?= $task->status ?>"><?= ucfirst(str_replace('_', ' ', $task->status)) ?></span>
                                <span
                                    class="status-badge priority-<?= $task->priority ?>"><?= ucfirst($task->priority) ?></span>
                                <?php if ($task->due_date): ?>
                                    <span>Due: <?= date('M j, Y', strtotime($task->due_date)) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($task->description): ?>
                        <div class="task-description"><?= htmlspecialchars($task->description) ?></div>
                    <?php endif; ?>

                    <div class="task-actions">
                        <a href="/tasks/<?= $task->id ?>/edit" class="btn btn-secondary">Edit</a>
                        <?php if (!$task->isCompleted()): ?>
                            <button onclick="markCompleted(<?= $task->id ?>)" class="btn btn-primary">Mark Complete</button>
                        <?php endif; ?>
                        <form method="POST" action="/tasks/<?= $task->id ?>/delete" style="display: inline;"
                            onsubmit="return confirm('Are you sure you want to delete this task?')">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>