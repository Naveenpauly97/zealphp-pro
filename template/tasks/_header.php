<!-- Header Section -->
<header class="header">
    <div class="header-content">
        <h1><?= $title ?? 'ZealPHP Tasks' ?></h1>
        <div class="user-info">
            <span>Welcome,
                <strong><?= htmlspecialchars(is_array($user) ? $user['username'] : $user->username) ?></strong></span>
            <a href="/logout" class="btn btn-secondary">Logout</a>
        </div>
    </div>
</header>