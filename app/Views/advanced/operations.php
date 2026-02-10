<h1>Operations Command Center</h1>
<div class="row g-3 mb-3">
  <div class="col-md-3"><div class="card"><div class="card-body"><h6>Queued Jobs</h6><p class="display-6"><?= (int)($queue['queued'] ?? 0); ?></p></div></div></div>
  <div class="col-md-3"><div class="card"><div class="card-body"><h6>Running Jobs</h6><p class="display-6"><?= (int)($queue['running'] ?? 0); ?></p></div></div></div>
  <div class="col-md-3"><div class="card"><div class="card-body"><h6>Failed Jobs</h6><p class="display-6"><?= (int)($queue['failed'] ?? 0); ?></p></div></div></div>
  <div class="col-md-3"><div class="card"><div class="card-body"><h6>SLA Breach</h6><p class="display-6"><?= (int)($kpis['ticket_sla_breach'] ?? 0); ?></p></div></div></div>
</div>
<div class="card mb-3"><div class="card-header">Next Action Recommendations</div><ul class="list-group list-group-flush">
<?php foreach (($recommendations ?? []) as $action): ?><li class="list-group-item"><?= \Core\Security::e($action); ?></li><?php endforeach; ?>
</ul></div>
<button class="btn btn-dark" id="commandPaletteBtn">Open Command Palette (Ctrl+K)</button>
