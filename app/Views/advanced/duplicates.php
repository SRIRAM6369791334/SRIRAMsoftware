<h1>Duplicate Data Detection Engine</h1>
<div class="row">
  <div class="col-md-6">
    <h5>Duplicate Leads (by Email)</h5>
    <ul class="list-group">
      <?php foreach (($leadDuplicates ?? []) as $d): ?><li class="list-group-item"><?= \Core\Security::e((string)$d['email']); ?> (<?= (int)$d['total']; ?>)</li><?php endforeach; ?>
    </ul>
  </div>
  <div class="col-md-6">
    <h5>Duplicate Clients (by Company)</h5>
    <ul class="list-group">
      <?php foreach (($clientDuplicates ?? []) as $d): ?><li class="list-group-item"><?= \Core\Security::e((string)$d['company_name']); ?> (<?= (int)$d['total']; ?>)</li><?php endforeach; ?>
    </ul>
  </div>
</div>
