
function markCompleted(taskId) {
    console.log("taskid ", taskId);
    fetch(`/api/tasks/update?id=${taskId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: 'completed' })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                send({
                    action: 'get_tasks',
                    status: currentStatus
                });
                showNotification('Task Completed', 'success');
                // console.log("Task marked as completed successfully ", data);
                // location.reload();
            } else {
                showNotification('Task Completed Failed', 'success');
            }
        })
        .catch(error => {
            setTimeout(() => {
                console.error('Error:', error);
                showNotification('Task Completed Failed', 'error');
            }, 1500);
            location.reload();
        });
}

export function deleteTask(taskId) {
    fetch(`/api/tasks/delete?id=${taskId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                send({
                    action: 'get_tasks',
                    status: currentStatus
                });
                showNotification('Deleted Successfully', 'success');
            } else {
                showNotification("Deleted Failed", 'error');
            }
        })
        .catch(error => {
            setTimeout(() => {
                console.error('Error:', error);
                showNotification('Task Delete Failed', 'error');
            }, 1500);
            location.reload();
        });
}

function getTaskByStatus(status) {
    const condition = status === 'all' ? '' : `status=${status}`;
    fetch(`/api/tasks?${condition}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.data);
                showNotification("Data Fetched ✅", 'success');
                send({
                    action: 'get_tasks',
                    status: currentStatus
                });
            } else {
                showNotification(data, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload();
        });
}

function updateTaskGrid(tasks) {
    const tasksGrid = document.querySelector('.tasks-grid');
    if (!tasksGrid) return;
    if (tasks.length === 0) {
        tasksGrid.innerHTML = `
                    <div class="empty-state">
                        <h3>No tasks found</h3>
                        <p>Create your first task to get started!</p>
                        <a href="/tasks/create" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Create Task</a>
                    </div>
                `;
        return;
    }
    tasksGrid.innerHTML = tasks.map(task => createTaskCard(task)).join('');
    // Re-attach event listeners
    // setupTaskEventListeners();
}

function createTaskCard(task) {
    const isCompleted = task.status === 'completed';
    const isOverdue = task.due_date && new Date(task.due_date) < new Date() && !isCompleted;
    return `
                <div class="task-card ${isCompleted ? 'completed' : ''} ${isOverdue ? 'overdue' : ''}">
                    <div class="task-header">
                        <div>
                            <div class="task-title">${escapeHtml(task.title)}</div>
                            <div class="task-meta">
                                <span class="status-badge status-${task.status}">${formatStatus(task.status)}</span>
                                <span class="status-badge priority-${task.priority}">${capitalize(task.priority)}</span>
                                ${task.due_date ? `<span>Due: ${formatDate(task.due_date)}</span>` : ''}
                            </div>
                        </div>
                    </div>
                    ${task.description ? `<div class="task-description">${escapeHtml(task.description)}</div>` : ''}
                    <div class="task-actions">
                        <a href="/tasks/${task.id}/edit" class="btn btn-secondary">Edit</a>
                        ${!isCompleted ? `<button onclick="markCompleted(${task.id})" class="btn btn-primary">Mark Complete</button>` : ''}
                        <button onclick="deleteTask(${task.id})" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            `;
}



// Utility methods


export function test() {
    console.log("testing ------");

}

function setupTaskEventListeners() {
    // Mark Complete buttons
    document.querySelectorAll('.task-card .btn-primary').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const taskId = closest('.task-card').querySelector('a.btn-secondary').href.match(/\/tasks\/(\d+)\/edit/)[1];
            markCompleted(taskId);
        });
    });

    // Delete buttons
    document.querySelectorAll('.task-card .btn-danger').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const taskId = closest('.task-card').querySelector('a.btn-secondary').href.match(/\/tasks\/(\d+)\/edit/)[1];
            if (confirm('Are you sure you want to delete this task?')) {
                deleteTask(taskId);
            }
        });
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatStatus(status) {
    return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
}

function setupEventListeners() {
    // Filter buttons
    document.querySelectorAll('.filters .btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = new URL(btn.href);
            const status = url.searchParams.get('status') || '';
            currentStatus = status;

            // Update URL without page reload
            window.history.pushState({}, '', btn.href);

            // Update active filter button
            document.querySelectorAll('.filters .btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-secondary');
            });
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-primary');

            // Request filtered tasks
            send({
                action: 'get_tasks',
                status: status
            });
        });
    });
}


function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelector('.notification');
    if (existing) {
        existing.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
export function handleMessage(data) {
    switch (data.type) {
        case 'task_update':
            updateTaskList(data.data);
            break;
        case 'success':
            showNotification(data.message, 'success');
            break;
        case 'error':
            showNotification(data.message, 'error');
            break;
    }
}

function updateTaskList(data) {
    // Update stats
    updateStats(data.stats);

    // Update task grid
    updateTaskGrid(data.tasks);

    // Update overdue count
    const overdueCount = data.overdue_tasks ? data.overdue_tasks.length : 0;
    const overdueElement = document.querySelector('.stat-card:last-child .stat-number');
    if (overdueElement) {
        overdueElement.textContent = overdueCount;
    }
}

function updateStats(stats) {
    const statCards = document.querySelectorAll('.stat-card .stat-number');
    if (statCards.length >= 4) {
        statCards[0].textContent = stats.total || 0;
        statCards[1].textContent = stats.pending || 0;
        statCards[2].textContent = stats.in_progress || 0;
        statCards[3].textContent = stats.completed || 0;
    }
}