<div class="container">
    <div class="form-card">
        <div class="form-header">
            <h2>Edit Task</h2>
            <div class="breadcrumb">
                <a href="/tasks">Tasks</a> / Edit / <?= (isset($task->title)) ? htmlspecialchars($task->title) : '' ?>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/tasks/update/<?= isset($task->id) ? htmlspecialchars($task->id) : '' ?>"
            class="task-form">
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" required
                    value="<?= (isset($task->title)) ? htmlspecialchars($task->title) : '' ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                    placeholder="Enter task description..."><?= htmlspecialchars($task->description ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="pending" <?= (isset($task->status) ? htmlspecialchars($task->status) : '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="in_progress" <?= (isset($task->status) ? htmlspecialchars($task->status) : '') === 'in_progress' ? 'selected' : '' ?>>In Progress
                    </option>
                    <option value="completed" <?= (isset($task->status) ? htmlspecialchars($task->status) : '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority">
                    <option value="low" <?= (isset($task->priority) ? htmlspecialchars($task->priority) : '') === 'low' ? 'selected' : '' ?>>Low</option>
                    <option value="medium" <?= (isset($task->priority) ? htmlspecialchars($task->priority) : '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="high" <?= (isset($task->priority) ? htmlspecialchars($task->priority) : '') === 'high' ? 'selected' : '' ?>>High</option>
                </select>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" value="<?= $task->due_date ?? '' ?>">
            </div>

            <div class="form-actions">
                <a href="/tasks" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</div>