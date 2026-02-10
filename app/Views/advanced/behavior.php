<h1>Behavior Intelligence</h1>
<p>Persona classification, power-user detection, inactivity decay, anomaly flags, access hints, dashboard defaults and friction heat analytics.</p>
<table class="table table-sm">
<thead><tr><th>Module</th><th>Event Count</th></tr></thead>
<tbody>
<?php foreach (($heat ?? []) as $row): ?>
<tr><td><?= \Core\Security::e((string)$row['module_name']); ?></td><td><?= (int)$row['total_events']; ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>
