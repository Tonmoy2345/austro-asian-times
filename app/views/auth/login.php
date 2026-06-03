<div class="auth-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">Staff Login</h1>
        <p class="auth-subtitle"><?= e(SITE_NAME) ?> Publishing System</p>

        <form method="POST" action="<?= BASE_URL ?>/login" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="Enter your username"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <p class="auth-back"><a href="<?= BASE_URL ?>/">&larr; Back to site</a></p>
    </div>
</div>
