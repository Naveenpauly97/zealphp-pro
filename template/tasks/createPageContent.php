<!-- Main Container -->
<div class="container">
    <div class="form-card">
        <div class="form-header">
            <h2>Create New Task</h2>
            <div class="breadcrumb">
                <a href="/tasks">Tasks</a> / Create
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/tasks/create/1">
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" required placeholder="Enter task title..."
                    value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                    placeholder="Enter task description..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority">
                    <option value="low" <?= ($_POST['priority'] ?? 'medium') === 'low' ? 'selected' : '' ?>>Low
                    </option>
                    <option value="medium" <?= ($_POST['priority'] ?? 'medium') === 'medium' ? 'selected' : '' ?>>
                        Medium</option>
                    <option value="high" <?= ($_POST['priority'] ?? 'medium') === 'high' ? 'selected' : '' ?>>High
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" required name="due_date" value="<?= $_POST['due_date'] ?? '' ?>">
            </div>

            <div class="form-actions">
                <a href="/tasks" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Task</button>
            </div>
        </form>
    </div>
</div>