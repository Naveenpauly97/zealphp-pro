<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #f8fafc;
        line-height: 1.6;
    }

    .header {
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 1rem 0;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header h1 {
        color: #333;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
        height: 100vh;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
    }

    .stat-label {
        color: #666;
        margin-top: 0.5rem;
    }

    .actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    a {
        text-decoration: none;
    }

    .btn {
        padding: 14px 20px;
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .btn-danger {
        background: #e53e3e;
        color: white;
    }

    .btn-danger:hover {
        background: #c53030;
    }

    .filters {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .tasks-grid {
        display: grid;
        gap: 1rem;
    }

    .task-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #667eea;
    }

    .task-card.completed {
        border-left-color: #48bb78;
        opacity: 0.8;
    }

    .task-card.overdue {
        border-left-color: #e53e3e;
    }

    .task-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .task-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .task-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.9rem;
        color: #666;
    }

    .task-description {
        color: #666;
        margin-bottom: 1rem;
    }

    .task-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-pending {
        background: #fed7d7;
        color: #c53030;
    }

    .status-in_progress {
        background: #feebc8;
        color: #dd6b20;
    }

    .status-completed {
        background: #c6f6d5;
        color: #2f855a;
    }

    .priority-high {
        background: #fed7d7;
        color: #c53030;
    }

    .priority-medium {
        background: #feebc8;
        color: #dd6b20;
    }

    .priority-low {
        background: #bee3f8;
        color: #2b6cb0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .empty-state h3 {
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .actions {
            flex-direction: column;
            align-items: stretch;
        }

        .filters {
            justify-content: center;
        }
    }

    /* WebSocket notification styles */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    }

    .notification-success {
        background: rgb(38, 102, 65);
    }

    .notification-error {
        background: #e53e3e;
    }

    .notification-info {
        background: #4299e1;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Loading states */
    .task-card.loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .task-actions button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>