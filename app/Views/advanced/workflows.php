<h1>Workflow Builder & Simulation</h1>
<p>Supports visual workflow definitions, if-this-then-that rules, approval chains, conditional notifications, auto escalation, scheduled background actions, and execution logs.</p>
<div class="card"><div class="card-body">
  <form id="workflowSimForm" class="row g-2">
    <div class="col-md-4"><input class="form-control" name="event" value="ticket.created"></div>
    <div class="col-md-4"><input class="form-control" name="priority" value="high"></div>
    <div class="col-md-4"><button class="btn btn-primary" type="submit">Run Simulation</button></div>
  </form>
  <pre id="workflowSimResult" class="mt-3"></pre>
</div></div>
