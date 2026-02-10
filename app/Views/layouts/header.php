<?php use Core\Security; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Business Suite</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body data-role="<?= \Core\Security::e((string)($_SESSION['user_role'] ?? 'admin')); ?>" class="noise-overlay">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="/dashboard">Business Suite</a>
    <div class="navbar-nav">
      <a class="nav-link" href="/users">Users</a>
      <a class="nav-link" href="/roles">Roles</a>
      <a class="nav-link" href="/employees">Employees</a>
      <a class="nav-link" href="/attendance">Attendance</a>
      <a class="nav-link" href="/clients">Clients</a>
      <a class="nav-link" href="/tickets">Tickets</a>
      <a class="nav-link" href="/tasks">Tasks</a>
      <a class="nav-link" href="/crm">CRM</a>
      <a class="nav-link" href="/reports">Reports</a>
      <a class="nav-link" href="/settings">Settings</a>
      <a class="nav-link" href="/operations">Ops</a>
      <a class="nav-link" href="/hr-intel">HR Intel</a>
      <a class="nav-link" href="/sales-intel">Sales Intel</a>
      <a class="nav-link" href="/workflows">Workflows</a>
      <a class="nav-link" href="/behavior-intel">Behavior</a>
      <a class="nav-link" href="/duplicates">Duplicates</a>
      <a class="nav-link text-warning" href="/logout">Logout</a>
    </div>
  </div>
</nav>
<main class="container">
