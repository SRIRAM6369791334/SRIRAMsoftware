<div class="row justify-content-center mt-5">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">Secure Login</div>
      <div class="card-body">
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= \Core\Security::e($error); ?></div><?php endif; ?>
        <form method="post" action="/login">
          <input type="hidden" name="_csrf" value="<?= \Core\Security::e($csrf); ?>">
          <div class="mb-3"><label class="form-label">Email</label><input class="form-control" name="email" type="email" required></div>
          <div class="mb-3"><label class="form-label">Password</label><input class="form-control" name="password" type="password" required></div>
          <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
