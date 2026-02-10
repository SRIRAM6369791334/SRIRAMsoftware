<h1>Admin Dashboard</h1>
<div class="adaptive-grid">
  <?php foreach ($stats as $label => $value): ?>
  <div class="card hover-spot">
    <h6><?= \Core\Security::e(strtoupper(str_replace('_', ' ', $label))); ?></h6>
    <p class="display-6" data-counter="<?= (int) $value; ?>">0</p>
  </div>
  <?php endforeach; ?>
</div>
