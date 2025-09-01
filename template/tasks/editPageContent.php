<div class="edit-container">
    <div class="edit-form-card">
        <div class="edit-form-header">
            <h2>Edit Task</h2>
            <div class="edit-breadcrumb">
                <a href="/tasks">Tasks</a> / Edit / <?= (isset($task->title)) ? htmlspecialchars($task->title) : '' ?>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="edit-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/tasks/update/<?= isset($task->id) ? htmlspecialchars($task->id) : '' ?>"
            class="edit-task-form">
            <div class="edit-form-group">
                <label for="ed-title">Title *</label>
                <input type="text" id="ed-title" name="title" required
                    value="<?= (isset($task->title)) ? htmlspecialchars($task->title) : '' ?>">
            </div>

            <div class="edit-form-group">
                <label for="ed-description">Description</label>
                <textarea id="ed-description" name="description"
                    placeholder="Enter task description..."><?= htmlspecialchars($task->description ?? '') ?></textarea>
            </div>

            <div class="edit-form-group">
                <label for="ed-status">Status</label>
                <select id="ed-status" name="status">
                    <option value="pending" <?= (isset($task->status) ? htmlspecialchars($task->status) : '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="in_progress" <?= (isset($task->status) ? htmlspecialchars($task->status) : '') === 'in_progress' ? 'selected' : '' ?>>In Progress
                    </option>
                    <option value="completed" <?= (isset($task->status) ? htmlspecialchars($task->status) : '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <div class="edit-form-group">
                <label for="ed-priority">Priority</label>
                <select id="ed-priority" name="priority">
                    <option value="low" <?= (isset($task->priority) ? htmlspecialchars($task->priority) : '') === 'low' ? 'selected' : '' ?>>Low</option>
                    <option value="medium" <?= (isset($task->priority) ? htmlspecialchars($task->priority) : '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="high" <?= (isset($task->priority) ? htmlspecialchars($task->priority) : '') === 'high' ? 'selected' : '' ?>>High</option>
                </select>
            </div>

            <div class="edit-form-group">
                <label for="ed-due_date">Due Date</label>
                <input type="date" id="ed-due_date" name="due_date" value="<?= $task->due_date ?? '' ?>">
            </div>

            <div class="edit-form-actions">
                <a href="/tasks" class="edit-btn edit-btn-secondary">Cancel</a>
                <button type="submit" class="edit-btn edit-btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</div>