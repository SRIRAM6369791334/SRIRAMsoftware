<h1>HR Intelligence Center</h1>
<ul class="list-group">
  <li class="list-group-item">Average Performance Score: <?= number_format((float)($summary['avg_performance_score'] ?? 0), 2); ?></li>
  <li class="list-group-item">Work-From-Home Days (Month): <?= (int)($summary['wfh_days'] ?? 0); ?></li>
  <li class="list-group-item">Overtime Hours (Month): <?= number_format((float)($summary['overtime_hours'] ?? 0), 2); ?></li>
</ul>
<p class="mt-3">Includes support for shift management, attendance approval workflows, anomaly alerts, geo-check attendance, and HR policy rule processing via backend tables/services.</p>
