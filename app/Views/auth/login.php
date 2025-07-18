<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">
                    create a new account
                </a>
            </p>
        </div>

        <?php if (session()->get('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session()->get('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->get('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session()->get('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->get('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    <?php foreach (session()->get('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="/login" method="POST">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="username" class="sr-only">Username or Email</label>
                    <input id="username" name="username" type="text" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="Username or Email"
                           value="<?= old('username') ?>">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="Password">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-lock text-indigo-500 group-hover:text-indigo-400"></i>
                    </span>
                    Sign in
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Demo accounts:<br>
                    <strong>Admin:</strong> admin / admin123<br>
                    <strong>User:</strong> user1 / user123
                </p>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
