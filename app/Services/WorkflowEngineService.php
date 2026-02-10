<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class WorkflowEngineService
{
    public function evaluateRules(string $eventKey, array $context): array
    {
        $stmt = Database::readConnection()->prepare('SELECT * FROM workflow_rules WHERE is_active = 1 AND trigger_event = :event_key ORDER BY priority ASC');
        $stmt->execute(['event_key' => $eventKey]);
        $rules = $stmt->fetchAll();
        $matched = [];

        foreach ($rules as $rule) {
            $condition = json_decode((string) $rule['condition_json'], true) ?: [];
            if ($this->matches($condition, $context)) {
                $matched[] = $rule;
            }
        }
        return $matched;
    }

    public function execute(string $eventKey, array $context, bool $simulation = false): array
    {
        $matchedRules = $this->evaluateRules($eventKey, $context);
        $results = [];

        foreach ($matchedRules as $rule) {
            $actions = json_decode((string) $rule['action_json'], true) ?: [];
            $results[] = [
                'rule_key' => $rule['rule_key'],
                'actions' => $actions,
                'simulation' => $simulation,
            ];

            if (!$simulation) {
                $this->logExecution((int) $rule['id'], $context, $actions, 'executed');
            } else {
                $this->logExecution((int) $rule['id'], $context, $actions, 'simulated');
            }
        }

        return $results;
    }

    public function logExecution(int $ruleId, array $context, array $actions, string $status): void
    {
        $stmt = Database::writeConnection()->prepare('INSERT INTO workflow_execution_logs (workflow_rule_id, context_json, actions_json, status, created_at) VALUES (:rule_id, :context, :actions, :status, NOW())');
        $stmt->execute([
            'rule_id' => $ruleId,
            'context' => json_encode($context, JSON_UNESCAPED_SLASHES),
            'actions' => json_encode($actions, JSON_UNESCAPED_SLASHES),
            'status' => $status,
        ]);
    }

    private function matches(array $condition, array $context): bool
    {
        foreach ($condition as $key => $value) {
            if (!array_key_exists($key, $context) || (string) $context[$key] !== (string) $value) {
                return false;
            }
        }
        return true;
    }
}
