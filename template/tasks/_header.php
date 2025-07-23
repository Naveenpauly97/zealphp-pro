<!-- Header Section -->
<header class="header">
    <div class="header-content">
        <h1><?= $title ?? 'ZealPHP Tasks' ?></h1>
        <div class="user-info">
            <span>Welcome, <?= htmlspecialchars($user->username) ?>!</span>
            <a href="/logout" class="btn btn-secondary">Logout</a>
        </div>
    </div>
</header>