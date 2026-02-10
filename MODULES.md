# Module-by-Module Implementation Map

## UI Foundation & Theming Engine
- `public/assets/css/app.css`: adaptive layout grid, spacing scale, radius/elevation tokens, transition system, skeleton/notification/scroll effects.
- `public/assets/js/app.js`: role-aware density, auto-theme by time, accent personalization, reduced-motion/focus/high-contrast switches.
- `/settings`: deep customization controls (font, line-height, density, shadow, radius, animation speed, focus/accessibility modes).

## Workflow & Approvals
- `WorkflowEngineService`: IFTTT rules, simulation, execution logs.
- `ApprovalChainService`: request creation and multi-step approval actions.
- UI: `/workflows` simulation panel.

## Behavior Intelligence & Personalization
- `BehaviorIntelligenceService`: persona, inactivity decay, anomalies, friction heat.
- `dashboard_preferences` + client-side personalized menu ordering + feature prominence scoring.
- UI: `/behavior-intel`.

## Duplicate Detection
- `DuplicateDetectionService` for duplicate leads/clients.
- UI/API: `/duplicates`, `resource=duplicates`.
