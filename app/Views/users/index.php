<h1>User Management</h1>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Created</th></tr></thead>
  <tbody>
  <?php foreach ($users as $user): ?>
    <tr>
      <td><?= (int) $user['id']; ?></td>
      <td><?= \Core\Security::e($user['full_name']); ?></td>
      <td><?= \Core\Security::e($user['email']); ?></td>
      <td><?= \Core\Security::e($user['status']); ?></td>
      <td><?= \Core\Security::e($user['created_at']); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<p>Page <?= (int) $page; ?></p>
