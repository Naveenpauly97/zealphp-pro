<!-- Main Container -->
<div class="create-container">
    <div class="create-form-card">
        <div class="create-form-header">
            <h2>Create New Task</h2>
            <div class="create-breadcrumb">
                <a href="/tasks">Tasks</a> / Create
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="create-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form onsubmit="TaskHandler.createTask(event)">
            <div class="create-form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" required placeholder="Enter task title..."
                    >
            </div>

            <div class="create-form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                    placeholder="Enter task description..."></textarea>
            </div>

            <div class="create-form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="create-form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" required name="due_date" value="<?= date('Y-m-d') ?>">
            </div>

            <div class="create-form-actions">
                <a href="/tasks" class="create-btn create-btn-secondary">Cancel</a>
                <button type="submit" class="create-btn create-btn-primary">Create Task</button>
            </div>
        </form>
    </div>
</div>