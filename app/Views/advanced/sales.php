<h1>Sales & CRM Intelligence</h1>
<div class="card mb-3"><div class="card-header">Summary</div><div class="card-body">
  <p>Average Deal Probability: <?= number_format((float)($insights['deal_probability_avg'] ?? 0), 2); ?>%</p>
  <p>Lost Deals (30 days): <?= (int)($insights['lost_deals_last_30d'] ?? 0); ?></p>
</div></div>
<div class="card"><div class="card-header">Lead Source ROI</div><div class="card-body">
  <table class="table table-sm"><thead><tr><th>Source</th><th>Revenue</th><th>Cost</th></tr></thead><tbody>
  <?php foreach (($insights['lead_source_roi'] ?? []) as $row): ?>
    <tr><td><?= \Core\Security::e((string)$row['source']); ?></td><td><?= \Core\Security::e((string)$row['revenue']); ?></td><td><?= \Core\Security::e((string)$row['cost']); ?></td></tr>
  <?php endforeach; ?>
  </tbody></table>
</div></div>
<p class="mt-3">Covers CLV, lead scoring, source ROI, funnel analytics, lost-deal analysis, renewal reminders, follow-up automations, proposal/quotation pipeline, and engagement scoring foundations.</p>
